<?php

namespace Nano112\LivewireTabulator\Components;

use Livewire\Component;

class EchartsChart extends Component
{
    public $options = [];
    public $events = [];
    public $callbacks = [];
    public $theme = 'default';

    public static function getDefaultOptions()
    {
        return [
            'grid' => [
                'left' => '3%',
                'right' => '4%',
                'bottom' => '3%',
                'containLabel' => true,
            ],
            'toolbox' => [
                'show' => true,
                'feature' => [
                    'dataZoom' => [
                        'yAxisIndex' => 'none',
                    ],
                    'magicType' => [
                        'type' => ['line', 'bar'],
                    ],
                    'restore' => [],
                    'saveAsImage' => [],
                    'dataView' => [
                        'readOnly' => false,
                    ],
                ],
            ],
            'dataZoom' => [
                [
                    'type' => 'inside',
                    'start' => 0,
                    'end' => 100,
                ],
                [
                    'type' => 'slider',
                    'start' => 0,
                    'end' => 100,
                ],
            ],
            'tooltip' => [
                'trigger' => 'axis',
                'axisPointer' => [
                    'type' => 'cross',
                ],
            ],
        ];
    }

    public function mount($options = [], $events = [], $callbacks = [], $theme = 'default')
    {
        $this->options = array_merge(self::getDefaultOptions(), $options);
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
