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
}
