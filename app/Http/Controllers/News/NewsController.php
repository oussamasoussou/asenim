<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;



class NewsController extends Controller
{
    public function showStoreNews()
    {
        $userConnected = Auth::user();
        return view('pages.news.store', compact('userConnected'));
    }

    public function edit(News $news)
    {
        $this->authorizeOwner($news);
        $userConnected = Auth::user();
        return view('pages.news.edit', compact('userConnected', 'news'));
    }

    public function index()
    {
        return view('pages.news.index', $this->listing(false));
    }

    public function indexArchived()
    {
        return view('pages.news.archive', $this->listing(true));
    }

    /**
     * Données d'une liste paginée d'actualités ou d'événements (onglet ?tab=news|events),
     * avec recherche (?search=) et pagination (?page=, ?perPage=, 5 par défaut).
     */
    private function listing(bool $archived): array
    {
        $userConnected = Auth::user();
        $tab = request('tab') === 'events' ? 'events' : 'news';
        $search = trim((string) request('search'));
        $perPage = max(1, min(100, (int) request('perPage', 5)));
        $page = max(1, (int) request('page', 1));

        $base = function (string $type) use ($archived, $userConnected, $search) {
            $query = ($archived ? News::onlyTrashed() : News::query())
                ->where('events_news', $type)
                ->whereHas('user', fn ($q) => $q->whereNull('deleted_at')); // Exclure les auteurs archivés

            if (!$userConnected->isAdmin()) {
                $query->where('user_id', $userConnected->id);
            }

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('content', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($u) use ($search) {
                            $u->where('first_name', 'LIKE', "%{$search}%")
                                ->orWhere('last_name', 'LIKE', "%{$search}%")
                                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                        });
                });
            }

            return $query;
        };

        $newsCount = $base('news')->count();
        $eventsCount = $base('event')->count();
        $total = $tab === 'events' ? $eventsCount : $newsCount;
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);

        $query = $base($tab === 'events' ? 'event' : 'news')->with('user');
        if ($archived) {
            $query->latest('deleted_at');
        } elseif ($tab === 'events') {
            // Événements : à venir d'abord (du plus proche au plus lointain), puis passés (du plus récent au plus ancien)
            $query->orderByRaw('CASE WHEN date >= ? THEN 0 ELSE 1 END', [now()])
                ->orderByRaw('CASE WHEN date >= ? THEN date END ASC', [now()])
                ->orderByDesc('date');
        } else {
            $query->latest();
        }

        $items = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        return compact('items', 'tab', 'newsCount', 'eventsCount', 'total', 'totalPages', 'page', 'perPage', 'userConnected');
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'events_news' => 'required|in:news,event',
            'content' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public'); // Enregistre dans le dossier "storage/app/public/news_images"
        }

        News::create([
            'title' => $validatedData['title'],
            'events_news' => $validatedData['events_news'],
            'content' => $validatedData['content'],
            'date' => $validatedData['date'],
            'image' => $imagePath,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('news.index', ['tab' => $validatedData['events_news'] === 'event' ? 'events' : 'news'])->with('success', $validatedData['events_news'] === 'event' ? 'Événement publié.' : 'Actualité publiée.');
    }

    public function update(Request $request, News $news)
    {
        $this->authorizeOwner($news);
        $request->validate([
            'title' => 'required|string|max:255',
            'events_news' => 'required|in:news,event',
            'content' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $news->date = $request->input('date');
        $news->title = $request->input('title');
        $news->events_news = $request->input('events_news');
        $news->content = $request->input('content');

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }

            $path = $request->file('image')->store('news_images', 'public');
            $news->image = $path;
        }

        $news->save();

        return redirect()->route('news.index', ['tab' => $news->events_news === 'event' ? 'events' : 'news'])->with('success', 'Publication mise à jour.');
    }


    /**
     * Supprimer un news (archiver).
     */
    public function delete($id)
    {
        $news = News::findOrFail($id);
        $this->authorizeOwner($news);
        $news->delete();

        return redirect()->back()->with('success', 'Publication archivée.');
    }

    /**
     * Restaurer un news archivé.
     */
    public function restore($id)
    {
        $news = News::withTrashed()->findOrFail($id);
        $this->authorizeOwner($news);

        if ($news->trashed()) {
            $news->restore();
        }

        return redirect()->back()->with('success', 'Publication restaurée.');
    }

    /**
     * Seul l'admin ou l'auteur peut modifier / supprimer / restaurer.
     */
    private function authorizeOwner(News $news): void
    {
        $user = Auth::user();
        abort_unless($user && ($user->isAdmin() || (int) $news->user_id === (int) $user->id), 403);
    }
}
