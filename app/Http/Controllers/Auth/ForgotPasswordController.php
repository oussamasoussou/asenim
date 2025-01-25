<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Password as FacadesPassword;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        $userConnected = Auth::user();
        return view('pages.auth.forgot-password',compact('userConnected'));
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $response = Password::sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return back()->with('status', 'Un lien de réinitialisation a été envoyé à votre e-mail.');
        }

        return back()->withErrors(['email' => trans($response)]);
    }
}
