<?php

namespace Nano112\LivewireTabulator;

use Illuminate\Support\ServiceProvider;
use Nano112\LivewireTabulator\Builders\TabulatorTable;
use Livewire\Livewire;

class TabulatorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('tabulator', function () {
            return new TabulatorTable();
        });

        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/tabulator.php', 'tabulator'
        );
    }

    public function boot()
    {
        // Load views from the correct path
        $this->loadViewsFrom(__DIR__.'/resources/views', 'livewire-tabulator');

        // Register Livewire component
        Livewire::component('tabulator-table', \Nano112\LivewireTabulator\Components\TabulatorTable::class);

        // Publishing options
        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/livewire-tabulator'),
        ], 'livewire-tabulator-views');

        $this->publishes([
            __DIR__.'/../config/tabulator.php' => config_path('tabulator.php'),
        ], 'livewire-tabulator-config');
    }
}