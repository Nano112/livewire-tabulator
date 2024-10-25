<?php

namespace Nano112\LivewireTabulator\Components;

use Livewire\Component;

class EchartsChart extends Component
{
    public $options = [];
    public $events = [];
    public $callbacks = [];
    public $theme = 'default';

    public static function getDefaultOptions($dataPoints = [])
    {
        return [
            'color' => ['#c23531', '#2f4554', '#61a0a8', '#d48265', '#91c7ae', 
                       '#749f83', '#ca8622', '#bda29a', '#6e7074', '#546570', '#c4ccd3'],
            'title' => [
                'text' => 'Time Series',
                'left' => 'center',
            ],
            'tooltip' => [
                'trigger' => 'axis'
            ],
            'legend' => [
                'data' => array_map(function($dataPoint) {
                    $parts = explode('/', $dataPoint);
                    return end($parts);
                }, $dataPoints),
                'top' => '30px',
            ],
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
                        'yAxisIndex' => 'none'
                    ],
                    'magicType' => [
                        'type' => ['line', 'bar']
                    ],
                    'restore' => [],
                    'saveAsImage' => [],
                    'dataView' => [
                        'readOnly' => false
                    ]
                ]
            ],
            'xAxis' => [
                'type' => 'category',
                'boundaryGap' => false,
            ],
            'yAxis' => [
                'type' => 'value',
                'name' => 'Value',
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
                ]
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

    public function placeholder()
    {
        return <<<'HTML'
        <div class="w-full h-full bg-gray-100 animate-pulse flex items-center justify-center">
            <div class="text-gray-500">Loading chart...</div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire-tabulator::components.echarts-chart');
    }
}
