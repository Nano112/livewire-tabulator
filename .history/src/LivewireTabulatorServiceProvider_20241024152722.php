<?php

namespace Nano112\LivewireTabulator;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Nano112\LivewireTabulator\Components\TabulatorTable;
use Nano112\LivewireTabulator\Components\EchartsChart;

class LivewireTabulatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'livewire-tabulator');
        
        // Publish config
        $this->publishes([
            __DIR__.'/../config/tabulator.php' => config_path('tabulator.php'),
        ], 'config');

        // Register blade directive for loading assets
        $this->registerBladeDirectives();
        
        // Register Livewire component
        Livewire::component('tabulator-table', TabulatorTable::class);
        Livewire::component('echarts-chart', EchartsChart::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/tabulator.php', 'tabulator'
        );
    }

}