<?php

namespace Nano112\LivewireTabulator\Components;

use Livewire\Component;

class EchartsChart extends Component
{
    public $chartId;
    public $options = [];
    public $events = [];
    public $callbacks = [];
    public $theme = 'default';
    public $chartType = 'line';

    public static function getDefaultOptions($dataPoints = [], $chartType = 'line')
    {
        $tooltipFormatter = <<<'JS'
            function(params) {
                if (Array.isArray(params)) {
                    return params[0].name + '<br/>' + 
                        params.map(param => 
                            `<span style="display:inline-block;margin-right:5px;border-radius:50%;width:10px;height:10px;background-color:${param.color};"></span>` +
                            `${param.seriesName}: ${param.value}`
                        ).join('<br/>');
                }
                return `${params.seriesName}<br/>${params.name}: ${params.value}`;
            }
        JS;
    
        $pieTooltipFormatter = <<<'JS'
            function(params) {
                return `${params.seriesName}<br/>${params.name}: ${params.value} (${params.percent}%)`;
            }
        JS;
    
        $baseOptions = [
            'tooltip' => [
                'show' => true,
                'trigger' => $chartType === 'pie' ? 'item' : 'item',
                'formatter' => $chartType === 'pie' ? $pieTooltipFormatter : $tooltipFormatter,
                'backgroundColor' => 'rgba(255, 255, 255, 0.9)',
                'borderColor' => '#ccc',
                'borderWidth' => 1,
                'textStyle' => [
                    'color' => '#333'
                ],
                'axisPointer' => [
                    'type' => 'cross',
                    'label' => [
                        'backgroundColor' => '#6a7985'
                    ],
                    'snap' => true
                ]
            ],
            'grid' => [
                'left' => '3%',
                'right' => '4%',
                'bottom' => '3%',
                'containLabel' => true
            ],
            'toolbox' => [
                'feature' => [
                    'saveAsImage' => ['title' => 'Save'],
                    'dataZoom' => ['title' => ['zoom' => 'Zoom', 'back' => 'Reset Zoom']],
                    'restore' => ['title' => 'Reset']
                ]
            ],
            'legend' => [
                'data' => $dataPoints,
                'type' => 'scroll',
                'orient' => $chartType === 'pie' ? 'vertical' : 'horizontal',
                'left' => $chartType === 'pie' ? 10 : 'center',
                'top' => $chartType === 'pie' ? 'middle' : 10
            ]
        ];
    
        $typeSpecificOptions = [
            'line' => [
                'xAxis' => [
                    'type' => 'category',
                    'boundaryGap' => false,
                    'axisLine' => ['show' => true],
                    'axisLabel' => ['show' => true],
                    'splitLine' => ['show' => true, 'lineStyle' => ['type' => 'dashed']]
                ],
                'yAxis' => [
                    'type' => 'value',
                    'splitLine' => ['show' => true, 'lineStyle' => ['type' => 'dashed']]
                ],
                'series' => [[
                    'type' => 'line',
                    'smooth' => true,
                    'symbol' => 'circle',
                    'symbolSize' => 6,
                    'showSymbol' => true,
                    'emphasis' => [
                        'focus' => 'series',
                        'itemStyle' => [
                            'borderWidth' => 2
                        ]
                    ]
                ]]
            ],
            'bar' => [
                'xAxis' => [
                    'type' => 'category',
                    'axisLabel' => ['show' => true],
                    'axisLine' => ['show' => true],
                    'splitLine' => ['show' => false]
                ],
                'yAxis' => [
                    'type' => 'value',
                    'splitLine' => ['show' => true, 'lineStyle' => ['type' => 'dashed']]
                ],
                'series' => [[
                    'type' => 'bar',
                    'barMaxWidth' => 50,
                    'emphasis' => [
                        'focus' => 'series'
                    ]
                ]]
            ],
            'pie' => [
                'series' => [[
                    'type' => 'pie',
                    'radius' => ['50%', '70%'],
                    'avoidLabelOverlap' => true,
                    'label' => [
                        'show' => false,
                        'position' => 'center'
                    ],
                    'emphasis' => [
                        'label' => [
                            'show' => true,
                            'fontSize' => 14,
                            'fontWeight' => 'bold'
                        ]
                    ],
                    'labelLine' => [
                        'show' => false
                    ]
                ]]
            ],
            'scatter' => [
                'xAxis' => [
                    'type' => 'value',
                    'splitLine' => ['show' => true, 'lineStyle' => ['type' => 'dashed']]
                ],
                'yAxis' => [
                    'type' => 'value',
                    'splitLine' => ['show' => true, 'lineStyle' => ['type' => 'dashed']]
                ],
                'series' => [[
                    'type' => 'scatter',
                    'symbolSize' => 10,
                    'emphasis' => [
                        'focus' => 'series',
                        'itemStyle' => [
                            'borderWidth' => 2
                        ]
                    ]
                ]]
            ]
        ];
    
        if (!isset($typeSpecificOptions[$chartType])) {
            return array_merge($baseOptions, $typeSpecificOptions['line']);
        }
    
        return array_merge($baseOptions, $typeSpecificOptions[$chartType]);
    }

    public function mount($chartId = null, $options = [], $events = [], $callbacks = [], $theme = 'default', $chartType = 'line')
    {
        $this->chartId = $chartId ?? 'chart-' . uniqid();
        $this->chartType = $chartType;
        $this->theme = $theme;
        $this->events = $events;
        $this->callbacks = $callbacks;
    
        $defaultOptions = self::getDefaultOptions($options['legend']['data'] ?? [], $chartType);
        
        // Don't modify series configuration
        $this->options = array_replace_recursive($defaultOptions, $options);
    }

    public function placeholder()
    {
        return <<<HTML
        <div>
            <script src="{{ config('echarts.cdn.js', 'https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.js') }}">
            </script>
            <div class="flex items-center justify-center p-4">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire-tabulator::components.echarts-chart');
    }
}