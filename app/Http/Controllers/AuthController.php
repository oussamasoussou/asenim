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

        $stats = [
            'users' => $userConnected->isAdmin() ? User::count() : null,
            'online' => User::where('user_connected', true)->count(),
            'documents' => Documents::count(),
            'news' => News::where('events_news', 'news')->count(),
            'events' => News::where('events_news', 'event')->where('date', '>=', now())->count(),
        ];

        $news = News::with('user')->where('events_news', 'news')->latest()->take(5)->get();
        $events = News::with('user')->where('events_news', 'event')
            ->where('date', '>=', now()->startOfDay())
            ->orderBy('date')->take(5)->get();
        $documents = Documents::with('user')->latest()->take(6)->get();

        return view('index', compact('stats', 'news', 'events', 'userConnected', 'documents'));
    }


    public function showMembreDashboard()
    {
        return redirect()->route('index');
    }

    public function login(Request $request)
    {
        // Les erreurs de validation sont renvoyées telles quelles (JSON pour la modale du site public)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            if (!Auth::attempt($credentials, $request->boolean('remember'))) {
                return $this->loginFailed($request, 'Les identifiants fournis sont incorrects.');
            }

            $user = Auth::user();

            // Inscription faite depuis le site public, pas encore validée par un administrateur
            if (!$user->isApproved()) {
                Auth::logout();
                return $this->loginFailed($request, 'Votre inscription est en attente de validation par un administrateur. Vous recevrez l’accès dès qu’elle sera acceptée.');
            }

            if (!$user->isAdmin() && !$user->isMembre()) {
                // Rôle inconnu : on ne laisse pas la session ouverte
                Auth::logout();
                return $this->loginFailed($request, 'Les identifiants fournis sont incorrects.');
            }

            $request->session()->regenerate(); // Évite la fixation de session
            $user->update(['user_connected' => true]);

            $redirect = $user->isMembre() && $user->first_connection == 1
                ? route('user.edit.personnel.membre.first') // Première connexion : compléter le profil
                : route('index');

            return $request->expectsJson()
                ? response()->json(['redirect' => $redirect])
                : redirect()->to($redirect);
        } catch (\Exception $e) {
            report($e);
            return $this->loginFailed($request, 'Une erreur est survenue lors de la tentative de connexion. Veuillez réessayer.', 'error');
        }
    }

    private function loginFailed(Request $request, string $message, string $field = 'email')
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message, 'errors' => [$field => [$message]]], 422);
        }

        return back()->withErrors([$field => $message])->onlyInput('email');
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
