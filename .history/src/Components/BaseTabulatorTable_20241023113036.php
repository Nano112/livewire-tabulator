<?php

namespace Nano112\LivewireTabulator\Components;

use Livewire\Component;

abstract class BaseTabulatorTable extends Component
{
    public $data = [];
    public $columns = [];
    public $options = [];

    public function mount()
    {
        $config = $this->table()->getConfig();
        $this->data = $config['data'];
        $this->columns = $config['columns'];
        $this->options = $config['options'];
    }

    abstract protected function table();

    public function render()
    {
        return view('livewire-tabulator::components.tabulator-table');
    }

    public function updateData($newData)
    {
        $this->data = $newData;
    }
}
