<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    // public function register(): void
    // {
    //     //
    // }

    /**
     * The event to listener mappings for the application.
     */
    protected $listen = [
        \App\Events\LeadStatusChanged::class => [
            \App\Listeners\CreateAutoFollowUp::class,
        ],
    ];


    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
