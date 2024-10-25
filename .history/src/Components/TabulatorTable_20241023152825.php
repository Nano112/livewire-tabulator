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
        dd($data);
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
}