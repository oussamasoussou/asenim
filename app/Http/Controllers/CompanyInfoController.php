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
        // « facebook.com/asenim » → « https://facebook.com/asenim »
        foreach (array_keys(CompanyInfo::SOCIALS) as $field) {
            $value = trim((string) $request->input($field));
            if ($value !== '' && !preg_match('#^[a-z][a-z0-9+.-]*://#i', $value)) {
                $value = 'https://' . ltrim($value, '/');
            }
            $request->merge([$field => $value === '' ? null : $value]);
        }

        $rules = [
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+().\s-]{6,30}$/'],
            'email' => ['nullable', 'email', 'max:255'],
        ];
        $messages = [
            'phone.regex' => 'Le numéro de téléphone ne doit contenir que des chiffres, espaces et les caractères + ( ) . -',
        ];
        $attributes = [];

        foreach (CompanyInfo::SOCIALS as $field => [$label, , , $domains]) {
            $rules[$field] = ['nullable', 'url:https,http', 'max:255', function ($attribute, $value, $fail) use ($label, $domains) {
                $host = strtolower((string) parse_url($value, PHP_URL_HOST));
                $host = preg_replace('/^(www\.|m\.|[a-z]{2}\.)/', '', $host);
                if (!in_array($host, $domains, true)) {
                    $fail("Le lien {$label} doit pointer vers " . implode(' ou ', $domains) . '.');
                }
            }];
            $attributes[$field] = "lien {$label}";
        }

        $validated = $request->validate($rules, $messages, $attributes);

        $company = CompanyInfo::current();
        $company->fill($validated);
        $company->updated_by = Auth::id();
        $company->save();

        return redirect()->route('company.edit')->with('success', 'Coordonnées enregistrées.');
    }
}
