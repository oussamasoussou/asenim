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
        $userConnected = Auth::user();
        return view('pages.news.edit', compact('userConnected', 'news'));
    }

    public function index()
    {
        $userConnected = Auth::user();

        $newsQuery = News::where('events_news', 'news')
            ->whereHas('user', function ($query) {
                $query->whereNull('deleted_at'); // Exclure les utilisateurs supprimés
            });

        $eventsQuery = News::where('events_news', 'event')
            ->whereHas('user', function ($query) {
                $query->whereNull('deleted_at');
            });

        if (!$userConnected->isAdmin()) {
            $newsQuery->where('user_id', $userConnected->id);
            $eventsQuery->where('user_id', $userConnected->id);
        }

        $news = $newsQuery->with('user')->get();
        $events = $eventsQuery->with('user')->get();

        return view('pages.news.index', compact('news', 'events', 'userConnected'));
    }



    public function indexArchived()
    {
        $userConnected = Auth::user();


        $newsQuery = News::onlyTrashed()->where('events_news', 'news')
            ->whereHas('user', function ($query) {
                $query->whereNull('deleted_at');
            });

        $eventsQuery = News::onlyTrashed()->where('events_news', 'event')
            ->whereHas('user', function ($query) {
                $query->whereNull('deleted_at');
            });


        if (!$userConnected->isAdmin()) {
            $newsQuery->where('user_id', $userConnected->id);
            $eventsQuery->where('user_id', $userConnected->id);
        }

        $news = $newsQuery->get();
        $events = $eventsQuery->get();

        return view('pages.news.archive', compact('news', 'events', 'userConnected'));
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

        return redirect()->route('news.index')->with('success', 'Actualité ou événement ajouté avec succès.');
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'events_news' => 'required|in:news,event',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $news->title = $request->input('title');
        $news->events_news = $request->input('events_news');
        $news->content = $request->input('content');

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::delete('public/' . $news->image);
            }

            $path = $request->file('image')->store('news_images', 'public');
            $news->image = $path;
        }

        $news->save();

        return redirect()->route('news.index')->with('success', 'Actualité/Événement mis à jour avec succès!');
    }


    /**
     * Supprimer un news (archiver).
     */
    public function delete($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return redirect()->route('news.index')->with('success', 'actualité ou événement supprimé avec succès.');
    }

    /**
     * Restaurer un news archivé.
     */
    public function restore($id)
    {
        $news = News::withTrashed()->findOrFail($id);

        if ($news->trashed()) {
            $news->restore();
        }

        return redirect()->route('news.index')->with('success', 'actualité ou événement restaure avec succès.');
    }

}
