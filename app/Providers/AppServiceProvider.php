<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        Relation::enforceMorphMap([
            'task' => 'App\Models\Task',
            'user' => 'App\Models\User',
            'document' => 'App\Models\Document',
            'department' => 'App\Models\Department',
            'category' => 'App\Models\Category',
        ]);
    }
}
