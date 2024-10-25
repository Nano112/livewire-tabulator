<?php

namespace Nano112\LivewireTabulator\Components;

use Livewire\Component;

class EchartsChart extends Component
{
    public $data = [];
    public $options = [];
    public $events = [];
    public $callbacks = [];
    public $theme = 'default';

    public function mount($data = [], $options = [], $events = [], $callbacks = [], $theme = 'default')
    {
        $this->data = $data;
        $this->options = $options;
        $this->events = $events;
        $this->callbacks = $callbacks;
        $this->theme = $theme;
    }

    public function render()
    {
        return view('livewire-tabulator::components.echarts-chart');
    }

    public function updateData($newData)
    {
        $this->data = $newData;
    }

    public function updateOptions($newOptions)
    {
        $this->options = array_merge($this->options, $newOptions);
    }
}