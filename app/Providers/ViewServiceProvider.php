<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\AnneeScolaire;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partager l'année scolaire active avec toutes les vues établissement
        View::composer(['layouts.etablissement', 'etablissement.*'], function ($view) {
            $anneeScolaireActive = AnneeScolaire::getActive();
            $view->with('anneeScolaireActive', $anneeScolaireActive);
        });
    }
}
