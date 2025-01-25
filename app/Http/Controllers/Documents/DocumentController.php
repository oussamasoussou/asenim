<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Documents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{
    public function showStoreDocument()
    {
        $userConnected = Auth::user();
        return view('pages.document.store', compact('userConnected'));
    }
    public function edit($id)
    {
        $documents = Documents::findOrFail($id);
        $userConnected = Auth::user();
        return view('pages.document.edit', compact('documents', 'userConnected'));
    }

    /**
     * Liste des documents non archivés.
     */
    public function indexNonArchived()
    {
        $userConnected = Auth::user();
        $perPage = request()->get('perPage', 10); // Nombre d'éléments par page
        $page = request()->get('page', 1);        // Page actuelle
        $sort = request()->get('sort', 'file_name');  // Colonne de tri
        $order = request()->get('order', 'asc'); // Ordre de tri
    
        // Récupération des documents avec pagination et tri
        $documents = Documents::whereNull('deleted_at')
            ->orderBy($sort, $order)
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
    
        // Calcul des totaux pour la pagination
        $totalDocuments = Documents::whereNull('deleted_at')->count();
        $totalPages = ceil($totalDocuments / $perPage);
    
        return view('pages.document.index', compact('documents', 'userConnected', 'page', 'totalPages', 'perPage'));
    }
    
    /**
     * Liste des documents archivés.
     */
    public function indexArchived()
    {
        $userConnected = Auth::user();
        $perPage = request()->get('perPage', 10); // Nombre d'éléments par page
        $page = request()->get('page', 1);        // Page actuelle
        $sort = request()->get('sort', 'file_name');  // Colonne de tri
        $order = request()->get('order', 'asc'); // Ordre de tri
    
        // Récupération des documents avec pagination et tri
        $documents = Documents::onlyTrashed()
            ->orderBy($sort, $order)
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
    
        // Calcul des totaux pour la pagination
        $totalDocuments = Documents::onlyTrashed()->count();
        $totalPages = ceil($totalDocuments / $perPage);
    
        return view('pages.document.archive', compact('documents', 'userConnected', 'page', 'totalPages', 'perPage'));
    }

    /**
     * Stocker un nouveau document.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'file_name' => 'required|string|max:255',
            'member_type' => 'nullable|in:permanent,non_permanent,all_members',
            'file' => 'required|file|mimes:pdf,jpeg,png,jpg|max:10240', // Validation du fichier
        ]);
    
        // Téléchargement du fichier
        $filePath = $request->file('file')->store('documents', 'public'); // Vous pouvez spécifier un dossier, ici 'documents'
    
        // Créer le document dans la base de données
        $document = Documents::create([
            'file_name' => $validated['file_name'],
            'file_path' => $filePath, // Enregistrer le chemin du fichier téléchargé
            'member_type' => $validated['member_type'] ?? 'all_members', // Défaut à 'all_members' si non défini
        ]);
    
        // Redirection avec message de succès
        return redirect()->route('documents.non_archived')->with('success', 'Document créé avec succès.');
    }
    

    /**
     * Mettre à jour un document existant.
     */
    public function update(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'file_name' => 'required|string|max:255',
            'member_type' => 'required|in:all_members,permanent,non_permanent',
            'file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);
    
        // Récupérer le document existant
        $document = Documents::findOrFail($id);
    
        // Mettre à jour les champs du document
        $document->file_name = $request->input('file_name');
        $document->member_type = $request->input('member_type');
    
        // Vérifier si un fichier a été téléchargé
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier s'il existe
            if (Storage::exists('documents/' . $document->file)) {
                Storage::delete('documents/' . $document->file);
            }
    
            // Enregistrer le nouveau fichier
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName);
    
            // Mettre à jour le nom du fichier dans la base de données
            $document->file = $fileName;
        }
    
        // Sauvegarder les modifications
        $document->save();
    
        return redirect()->route('documents.non_archived')->with('success', 'Document mis à jour avec succès.');
    }
    

    /**
     * Supprimer un document (archiver).
     */
    public function delete($id)
    {
        $document = Documents::findOrFail($id);
        $document->delete();

        return redirect()->route('documents.non_archived')->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Restaurer un document archivé.
     */
    public function restore($id)
    {
        $document = Documents::withTrashed()->findOrFail($id);

        if ($document->trashed()) {
            $document->restore();
        }

        return redirect()->route('documents.non_archived')->with('success', 'Document restore avec succès.');
    }
}
