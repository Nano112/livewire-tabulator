<?php

namespace Nano112\LivewireTabulator;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Nano112\LivewireTabulator\Components\TabulatorTable;

class LivewireTabulatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'livewire-tabulator');

        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/livewire-tabulator'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../config/tabulator.php' => config_path('tabulator.php'),
        ], 'config');
    }
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/tabulator.php', 'tabulator'
        );
    }
}