<?php

namespace App\Providers;

use App\Service\RosterImport\Parser\HtmlRosterParser;
use App\Service\RosterImport\RosterImporter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->when(RosterImporter::class)->needs('$parsers')->give(function (Application $app) {
            return [
                $app->make(HtmlRosterParser::class)
            ];
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
