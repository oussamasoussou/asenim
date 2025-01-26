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
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                if (auth()->user()->isMembre()) {
                    return redirect()->route('membre');
                } elseif (auth()->user()->isAdmin()) {
                    return redirect()->route('index');
                }
            }

            return back()->withErrors([
                'email' => 'Les identifiants fournis sont incorrects.',
            ])->onlyInput('email');
        } catch (\Exception $e) {
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
