<?php

namespace Nano112\LivewireTabulator\Components;

use Livewire\Component;

class TabulatorTable extends Component
{
    public $data = [];
    public $columns = [];
    public $options = [];
    public $events = [];
    public $callbacks = [];


    public function mount($data = [], $columns = [], $options = [], $events = [], $callbacks = [])
    {
        $this->data = $data;
        $this->columns = $columns;
        $this->options = $options;
        $this->events = $events;
        $this->callbacks = $callbacks;
    }

    public function render()
    {
        return view('livewire-tabulator::components.tabulator-table');
    }

    public function updateData($newData)
    {
        $this->data = $newData;
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <!-- Preload Tabulator assets -->
            <link href="{{ config('tabulator.cdn.css', 'https://unpkg.com/tabulator-tables@6.3.0/dist/css/tabulator.min.css') }}" rel="stylesheet">
            <script src="{{ config('tabulator.cdn.js', 'https://unpkg.com/tabulator-tables@6.3.0/dist/js/tabulator.min.js') }}"></script>
            
            <!-- Loading spinner -->
            <div class="flex items-center justify-center p-4">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
            </div>
        </div>
        HTML;
    }
}