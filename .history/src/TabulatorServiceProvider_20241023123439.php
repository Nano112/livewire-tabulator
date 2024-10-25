<?php

namespace Nano112\LivewireTabulator;

use Illuminate\Support\ServiceProvider;
use Nano112\LivewireTabulator\Builders\TabulatorTable;

class TabulatorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('tabulator', function () {
            return new TabulatorTable();
        });
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-tabulator');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/livewire-tabulator'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../config/tabulator.php' => config_path('tabulator.php'),
        ], 'config');
    }
}
