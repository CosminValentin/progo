<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useTailwind();

        // Morph map con claves cortas (como ya usabas en Documents)
        Relation::enforceMorphMap([
            'participants' => \App\Models\Participant::class,
            'companies'    => \App\Models\Company::class,
            'offers'       => \App\Models\Offer::class,
            'users'        => \App\Models\User::class,
        ]);
    }
}
