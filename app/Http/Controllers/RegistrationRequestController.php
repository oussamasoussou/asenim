<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Demandes d'inscription reçues depuis le site public (comptes en attente de validation).
 */
class RegistrationRequestController extends Controller
{
    public function index()
    {
        $userConnected = Auth::user();
        $search = trim((string) request('search'));
        $perPage = max(1, min(100, (int) request('perPage', 5)));
        $page = max(1, (int) request('page', 1));

        $query = User::pending()->when($search !== '', function ($q) use ($search) {
            $q->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        });

        $totalUsers = $query->count();
        $totalPages = max(1, (int) ceil($totalUsers / $perPage));
        $page = min($page, $totalPages);
        $users = $query->latest()->skip(($page - 1) * $perPage)->take($perPage)->get();

        return view('pages.users.pending', compact('users', 'userConnected', 'page', 'totalPages', 'perPage', 'totalUsers'));
    }

    public function approve(int $id)
    {
        $user = User::pending()->findOrFail($id);
        $user->approved_at = now();
        $user->save();

        return redirect()->back()->with('success', "Inscription de {$user->first_name} {$user->last_name} validée : la personne peut maintenant se connecter.");
    }

    public function reject(int $id)
    {
        $user = User::pending()->findOrFail($id);
        $name = trim("{$user->first_name} {$user->last_name}");
        $user->forceDelete(); // Demande jamais validée : aucune donnée à conserver

        return redirect()->back()->with('success', "Demande d’inscription de {$name} refusée et supprimée.");
    }
}
