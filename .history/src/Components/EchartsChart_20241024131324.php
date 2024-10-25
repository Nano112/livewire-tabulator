<?php

namespace Nano112\LivewireEcharts\Components;

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
        // Ensure events and callbacks are strings
        $this->events = array_map('strval', $events);
        $this->callbacks = array_map('strval', $callbacks);
        $this->theme = $theme;
    }

    public function render()
    {
        return view('livewire-echarts::components.echarts-chart');
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