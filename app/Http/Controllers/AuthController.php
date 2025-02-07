<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.login');
    }
   

    public function showAdminDashboard()
    {
        $userConnected = Auth::user();
        $users = User::all();
        $news = News::all();
        $documents = Documents::all();

        return view('index', compact('users', 'news', 'userConnected', 'documents'));
    }


    public function showMembreDashboard()
    {
        return view('membre');
    }

    public function login(Request $request)
    {
        try {
            // Valider les champs email et password
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
    
            // Tenter de connecter l'utilisateur
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate(); // Régénérer la session pour éviter les attaques de fixation de session
                $user = Auth::user(); // Récupérer l'utilisateur connecté
                $user->update(['user_connected' => true]);
    
                // Vérifier si l'utilisateur est un administrateur
                if ($user->isAdmin()) {
                    return redirect()->route('index'); // Rediriger vers l'index pour les administrateurs
                }
    
                // Vérifier si l'utilisateur est un membre
                if ($user->isMembre()) {
                    // Vérifier si c'est la première connexion
                    if ($user->first_connection == 1) {
                        return redirect()->route('user.edit.personnel.membre.first', $user->id); // Rediriger vers la page de mise à jour du profil
                    } else {
                        return redirect()->route('index'); // Rediriger vers l'index pour les membres déjà connectés
                    }
                }
            }
    
            // Si l'authentification échoue, retourner une erreur
            return back()->withErrors([
                'email' => 'Les identifiants fournis sont incorrects.',
            ])->onlyInput('email');
    
        } catch (\Exception $e) {
            // En cas d'erreur, retourner un message d'erreur générique
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors de la tentative de connexion. Veuillez réessayer.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            if (auth()->check()) {
                $user = auth()->user();
                $user->update(['user_connected' => false]);
            }

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'Vous êtes maintenant déconnecté.');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Une erreur est survenue lors de la déconnexion. Veuillez réessayer.');
        }
    }

    public function userConnected()
    {
        try {
            $userConnected = Auth::user();

            if (!$userConnected) {
                return redirect()->route('login')->with('error', 'Utilisateur non connecté.');
            }

            return view('layouts.nav', compact('userConnected'));
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

}
