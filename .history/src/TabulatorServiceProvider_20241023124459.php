<?php

namespace Nano112\LivewireTabulator;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Illuminate\Support\Facades\File;

class TabulatorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('tabulator', function () {
            return new \Nano112\LivewireTabulator\Builders\TabulatorTable();
        });

        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/tabulator.php', 
            'tabulator'
        );
    }

    public function boot()
    {
        // Define the package's view path
        $viewsPath = __DIR__ . '/resources/views';

        // Register the view namespace with absolute path
        $this->loadViewsFrom($viewsPath, 'livewire-tabulator');

        // Register Livewire component
        Livewire::component('tabulator-table', \Nano112\LivewireTabulator\Components\TabulatorTable::class);

        if ($this->app->runningInConsole()) {
            // Publish views
            $this->publishes([
                $viewsPath => resource_path('views/vendor/livewire-tabulator'),
            ], 'livewire-tabulator-views');

            // Publish config
            $this->publishes([
                __DIR__ . '/../config/tabulator.php' => config_path('tabulator.php'),
            ], 'livewire-tabulator-config');

            // Ensure view directory exists
            if (!File::exists($viewsPath)) {
                File::makeDirectory($viewsPath, 0755, true);
            }
        }

        // Debug view paths
        $this->app->terminating(function () use ($viewsPath) {
            if (app()->environment('local')) {
                info('Tabulator View Paths:', [
                    'Package Views Path' => $viewsPath,
                    'Views Exist' => File::exists($viewsPath),
                    'Published Path' => resource_path('views/vendor/livewire-tabulator'),
                    'Registered Namespaces' => array_keys(app('view.finder')->getHints()),
                ]);
            }
        });
    }
}