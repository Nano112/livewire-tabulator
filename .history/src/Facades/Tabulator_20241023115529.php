<?php

namespace Nano112\LivewireTabulator\Facades;

use Illuminate\Support\Facades\Facade;

class Tabulator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tabulator';
    }
}
