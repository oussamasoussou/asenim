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
        $this->authorizeOwner($documents);
        $userConnected = Auth::user();
        return view('pages.document.edit', compact('documents', 'userConnected'));
    }

    /**
     * Seul l'admin ou l'auteur du document peut le modifier / supprimer / restaurer.
     */
    private function authorizeOwner(Documents $document): void
    {
        $user = Auth::user();
        abort_unless($user && ($user->isAdmin() || (int) $document->user_id === (int) $user->id), 403);
    }

    /**
     * Normalise les paramètres de tri / pagination reçus en query string.
     */
    private function listParams(): array
    {
        $sort = request()->get('sort', 'file_name');
        if (!in_array($sort, ['file_name', 'member_type', 'created_at', 'updated_at', 'deleted_at'], true)) {
            $sort = 'file_name';
        }
        $order = strtolower(request()->get('order', 'asc')) === 'desc' ? 'desc' : 'asc';

        return [
            max(1, min(100, (int) request()->get('perPage', 5))),
            max(1, (int) request()->get('page', 1)),
            $sort,
            $order,
        ];
    }

    /**
     * Liste des documents non archivés.
     */
    public function indexNonArchived()
    {
        $userConnected = Auth::user();
        [$perPage, $page, $sort, $order] = $this->listParams();
        $search = request()->get('search');
    
        // Vérification de l'utilisateur connecté
        $userConnected = Auth::user();
        if (!$userConnected) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        // Vérification du rôle de l'utilisateur
        $query = Documents::with('user')->whereNull('deleted_at');
    
        if (!$userConnected->isAdmin()) { // Si ce n'est pas un admin, filtrer par user_id
            $query->where('user_id', $userConnected->id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('file_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('first_name', 'LIKE', "%{$search}%")
                         ->orWhere('last_name', 'LIKE', "%{$search}%")
                         ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                  });
            });
        }
    
        // Calcul des totaux pour la pagination
        $totalDocuments = $query->count();
        $totalPages = ceil($totalDocuments / $perPage);
        $page = min($page, max(1, (int) $totalPages));

        // Appliquer tri et pagination
        $documents = $query->orderBy($sort, $order)
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
    
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'documents' => $documents,
                'page' => (int)$page,
                'totalPages' => (int)$totalPages,
                'perPage' => (int)$perPage,
                'totalDocuments' => (int)$totalDocuments,
                'isAdmin' => $userConnected->isAdmin()
            ]);
        }

        return view('pages.document.index', compact('documents', 'userConnected', 'page', 'totalPages', 'perPage', 'totalDocuments'));
    }
    
    
    /**
     * Liste des documents archivés.
     */
    public function indexArchived()
    {
        $userConnected = Auth::user();
        [$perPage, $page, $sort, $order] = $this->listParams();
        $search = request()->get('search');
    
        // Vérification de l'utilisateur connecté
        $userConnected = Auth::user();
        if (!$userConnected) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        // Vérification du rôle de l'utilisateur
        $query = Documents::with('user')->onlyTrashed();
    
        if (!$userConnected->isAdmin()) { // Si ce n'est pas un admin, filtrer par user_id
            $query->where('user_id', $userConnected->id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('file_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('first_name', 'LIKE', "%{$search}%")
                         ->orWhere('last_name', 'LIKE', "%{$search}%")
                         ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                  });
            });
        }
    
        // Calcul des totaux pour la pagination (avant skip/take, sinon le count est faussé)
        $totalDocuments = $query->count();
        $totalPages = ceil($totalDocuments / $perPage);
        $page = min($page, max(1, (int) $totalPages));

        // Appliquer tri et pagination
        $documents = $query->orderBy($sort, $order)
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        if (request()->ajax()) {
            return response()->json([
                'documents' => $documents,
                'page' => $page,
                'totalPages' => $totalPages,
                'perPage' => $perPage,
                'totalDocuments' => $totalDocuments,
                'isAdmin' => $userConnected->isAdmin()
            ]);
        }

        return view('pages.document.archive', compact('documents', 'userConnected', 'page', 'totalPages', 'perPage', 'totalDocuments'));
    }
    
    /**
     * Stocker un nouveau document.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file_name' => 'required|string|max:255',
            'member_type' => 'nullable|in:permanent,non_permanent,all_members',
            'file' => 'required|file|mimes:pdf,jpeg,png,jpg|max:10240', // Validation du fichier
        ]);
    
        $filePath = $request->file('file')->store('documents', 'public'); // Vous pouvez spécifier un dossier, ici 'documents'
    
        $document = Documents::create([
            'file_name' => $validated['file_name'],
            'file_path' => $filePath,
            'member_type' => $validated['member_type'] ?? 'all_members',
            'user_id' => auth()->id(), 
        ]);
    
        return redirect()->route('documents.non_archived')->with('success', 'Document publié.');
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
            'file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:10240',
        ]);

        // Récupérer le document existant
        $document = Documents::findOrFail($id);
        $this->authorizeOwner($document);

        // Mettre à jour les champs du document
        $document->file_name = $request->input('file_name');
        $document->member_type = $request->input('member_type');

        // Vérifier si un fichier a été téléchargé
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier s'il existe
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            // Enregistrer le nouveau fichier (même disque que store())
            $document->file_path = $request->file('file')->store('documents', 'public');
        }
    
        // Sauvegarder les modifications
        $document->save();
    
        return redirect()->route('documents.non_archived')->with('success', 'Document mis à jour.');
    }
    

    /**
     * Supprimer un document (archiver).
     */
    public function delete($id)
    {
        $document = Documents::findOrFail($id);
        $this->authorizeOwner($document);
        $document->delete();

        return redirect()->route('documents.non_archived')->with('success', 'Document archivé. Vous pouvez le restaurer depuis les archives.');
    }

    /**
     * Restaurer un document archivé.
     */
    public function restore($id)
    {
        $document = Documents::withTrashed()->findOrFail($id);
        $this->authorizeOwner($document);

        if ($document->trashed()) {
            $document->restore();
        }

        return redirect()->route('documents.non_archived')->with('success', 'Document restauré.');
    }
}
