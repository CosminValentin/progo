<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Opcional, pero frecuente en MySQL antiguos / índices
        Schema::defaultStringLength(191);

        // Tailwind para la paginación
        Paginator::useTailwind();

        // Claves cortas para relaciones polimórficas (documents.owner_type)
        Relation::enforceMorphMap([
            'participants' => \App\Models\Participant::class,
            'companies'    => \App\Models\Company::class,
            'offers'       => \App\Models\Offer::class,
            'users'        => \App\Models\User::class,
        ]);
    }
}
