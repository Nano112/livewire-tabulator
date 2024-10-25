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
        // Clean up the datapoint names for legend
        $legendData = array_map(function($dataPoint) {
            $parts = explode('/', $dataPoint);
            return end($parts);
        }, $dataPoints);

        return [
            'title' => [
                'text' => 'time series',
                'left' => 'center',
            ],
            'tooltip' => [
                'trigger' => 'axis',
                'formatter' => <<<JS
                function (params) {
                    var tooltipText = params[0].axisValue + '<br/>';
                    params.forEach(function (item) {
                        if (item.value !== null && item.value !== undefined) {
                            tooltipText += item.marker + item.seriesName + ': <b>' + item.value + '</b><br/>';
                        }
                    });
                    return tooltipText;
                }
                JS
            ],
            'legend' => [
                'data' => $legendData,
                'top' => '30px',
            ],
            'grid' => [
                'left' => '3%',
                'right' => '4%',
                'bottom' => '3%',
                'containLabel' => true,
            ],
            'toolbox' => [
                'feature' => [
                    'dataZoom' => [
                        'yAxisIndex' => 'none',
                    ],
                    'restore' => [],
                    'saveAsImage' => [],
                    'dataView' => [
                        'readOnly' => false,
                    ],
                ],
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
                ],
            ]
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
