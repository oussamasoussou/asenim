<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyInfoController extends Controller
{
    public function edit()
    {
        $userConnected = Auth::user();
        $company = CompanyInfo::current();

        return view('pages.company.edit', compact('userConnected', 'company'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+().\s-]{6,30}$/'],
            'email' => ['nullable', 'email', 'max:255'],
        ], [
            'phone.regex' => 'Le numéro de téléphone ne doit contenir que des chiffres, espaces et les caractères + ( ) . -',
        ]);

        $company = CompanyInfo::current();
        $company->fill($validated);
        $company->updated_by = Auth::id();
        $company->save();

        return redirect()->route('company.edit')->with('success', 'Coordonnées enregistrées.');
    }
}
