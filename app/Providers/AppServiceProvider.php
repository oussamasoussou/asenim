<?php

namespace App\Providers;

use App\Models\CompanyInfo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Site public : coordonnées gérées depuis l'admin (« Coordonnées »), avec valeurs par défaut.
        View::composer('front.*', function ($view) {
            // Mémorisé pour la requête (le layout et la page partagent la même valeur)
            $contact = request()->attributes->get("front.contact");

            if ($contact === null) {
                $company = Schema::hasTable('company_infos') ? CompanyInfo::current() : new CompanyInfo();
                $address = $company->address ?: "ENIM, Avenue Ibn El Jazzar\nMonastir 5000, Tunisie";
                $phone = $company->phone ?: '+216 73 500 000';

                $contact = [
                    'address' => $address,
                    'address_inline' => preg_replace('/\s*\R\s*/', ', ', trim($address)),
                    'phone' => $phone,
                    'phone_href' => preg_replace('/[^0-9+]/', '', $phone),
                    'email' => $company->email ?: 'contact@asenim.org',
                    'map_url' => 'https://www.google.com/maps/search/?api=1&query=' . urlencode(preg_replace('/\s+/', ' ', $address)),
                    // Réseaux sociaux renseignés dans l'admin (seuls ceux-ci sont affichés)
                    'socials' => $company->socialLinks(),
                ];
                request()->attributes->set("front.contact", $contact);
            }

            $view->with('contact', $contact);
        });
    }
}
