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
        $news = News::where('events_news', 'news')->get();
        $events = News::where('events_news', 'event')->get();
        $userConnected = Auth::user();
        return view('pages.news.index', compact('news', 'events', 'userConnected'));
    }

    public function indexArchived()
    {
        $news = News::onlyTrashed()->where('events_news', 'news')->get();
        $events = News::onlyTrashed()->where('events_news', 'event')->get();
        $userConnected = Auth::user();
        return view('pages.news.archive', compact('news', 'events', 'userConnected'));
    }


    public function store(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'events_news' => 'required|in:news,event',
            'content' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max pour les images
        ]);

        // Traitement de l'image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public'); // Enregistre dans le dossier "storage/app/public/news_images"
        }

        // Création de l'actualité ou événement
        News::create([
            'title' => $validatedData['title'],
            'events_news' => $validatedData['events_news'],
            'content' => $validatedData['content'],
            'date' => $validatedData['date'],
            'image' => $imagePath,
            'user_id' => auth()->id(), // ID de l'utilisateur connecté
        ]);

        // Redirection avec un message de succès
        return redirect()->route('news.index')->with('success', 'Actualité ou événement ajouté avec succès.');
    }

    public function update(Request $request, News $news)
    {
        // Validation des données
        $request->validate([
            'title' => 'required|string|max:255',
            'events_news' => 'required|in:news,event',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'image
        ]);

        // Mise à jour des données
        $news->title = $request->input('title');
        $news->events_news = $request->input('events_news');
        $news->content = $request->input('content');

        // Si une nouvelle image a été téléchargée, traiter l'upload
        if ($request->hasFile('image')) {
            // Supprimer l'image précédente, si elle existe
            if ($news->image) {
                Storage::delete('public/' . $news->image);
            }

            // Enregistrer la nouvelle image
            $path = $request->file('image')->store('news_images', 'public');
            $news->image = $path; // Sauvegarder le chemin de l'image dans la base de données
        }

        // Sauvegarder les modifications
        $news->save();

        // Rediriger vers la liste des articles avec un message de succès
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
