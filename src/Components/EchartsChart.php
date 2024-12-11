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
        // Common style constants
        $axisLineColor = '#E0E6F1';
        $axisFontColor = '#6E7079';
        $tooltipBgColor = '#FFFFFF';
        $tooltipBorderColor = '#E0E6F1';
        $gridLineColor = '#E0E6F1';
        $emphasisColor = '#5470C6';

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
                'trigger' => 'axis',
                'backgroundColor' => $tooltipBgColor,
                'borderWidth' => 1,
                'borderColor' => $tooltipBorderColor,
                'padding' => [8, 12],
                'textStyle' => [
                    'color' => '#333',
                    'fontSize' => 12
                ],
                'axisPointer' => [
                    'type' => 'shadow',
                    'shadowStyle' => [
                        'color' => 'rgba(0,0,0,0.05)'
                    ]
                ]
            ],
            'grid' => [
                'left' => '3%',
                'right' => '4%',
                'bottom' => '15%', // Increased to accommodate data zoom
                'top' => '15%',    // Increased to accommodate legend
                'containLabel' => true
            ],
            'toolbox' => [
                'show' => true,
                'feature' => [
                    'saveAsImage' => [
                        'title' => 'Save',
                        'icon' => 'path://M19,12v7L5,19v-7h14m0-5H5c-1.1,0-2,0.9-2,2v10c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V9C21,7.9,20.1,7,19,7z M17,16H7v-2h10V16z M17,12H7v-2h10V12z'
                    ],
                    'dataZoom' => [
                        'yAxisIndex' => 'none',
                        'title' => [
                            'zoom' => 'Zoom',
                            'back' => 'Reset Zoom'
                        ]
                    ],
                    'restore' => [
                        'title' => 'Reset'
                    ]
                ],
                'itemSize' => 15,
                'emphasis' => [
                    'iconStyle' => [
                        'borderColor' => $emphasisColor
                    ]
                ]
            ],
            'dataZoom' => [
                [
                    'type' => 'inside',
                    'start' => 0,
                    'end' => 100,
                    'minValueSpan' => 5
                ],
                [
                    'show' => true,
                    'type' => 'slider',
                    'start' => 0,
                    'end' => 100,
                    'height' => 20,
                    'bottom' => 0,
                    'borderColor' => 'transparent',
                    'backgroundColor' => '#f7f7f7',
                    'fillerColor' => 'rgba(84,112,198,0.2)',
                    'handleStyle' => [
                        'color' => '#fff',
                        'shadowBlur' => 3,
                        'shadowColor' => 'rgba(0, 0, 0, 0.6)',
                        'shadowOffsetX' => 2,
                        'shadowOffsetY' => 2
                    ]
                ]
            ],
            'legend' => [
                'data' => $dataPoints,
                'type' => 'scroll',
                'orient' => $chartType === 'pie' ? 'vertical' : 'horizontal',
                'left' => $chartType === 'pie' ? 10 : 'center',
                'top' => 5,
                'textStyle' => [
                    'color' => $axisFontColor
                ],
                'pageTextStyle' => [
                    'color' => $axisFontColor
                ],
                'pageIconColor' => $axisFontColor,
                'pageIconInactiveColor' => '#aaa'
            ]
        ];

        $typeSpecificOptions = [
            'line' => [
                'xAxis' => [
                    'type' => 'category',
                    'boundaryGap' => false,
                    'axisLine' => [
                        'show' => true,
                        'lineStyle' => ['color' => $axisLineColor]
                    ],
                    'axisTick' => ['show' => false],
                    'axisLabel' => [
                        'show' => true,
                        'color' => $axisFontColor
                    ],
                    'splitLine' => [
                        'show' => true,
                        'lineStyle' => [
                            'color' => $gridLineColor,
                            'type' => 'dashed'
                        ]
                    ]
                ],
                'yAxis' => [
                    'type' => 'value',
                    'axisLine' => ['show' => false],
                    'axisTick' => ['show' => false],
                    'axisLabel' => [
                        'color' => $axisFontColor
                    ],
                    'splitLine' => [
                        'show' => true,
                        'lineStyle' => [
                            'color' => $gridLineColor,
                            'type' => 'dashed'
                        ]
                    ]
                ],
                'series' => [[
                    'type' => 'line',
                    'smooth' => true,
                    'symbol' => 'emptyCircle',
                    'symbolSize' => 8,
                    'showSymbol' => true,
                    'lineStyle' => [
                        'width' => 2
                    ],
                    'emphasis' => [
                        'focus' => 'series',
                        'itemStyle' => [
                            'borderWidth' => 2
                        ]
                    ],
                    'animation' => true,
                    'animationDuration' => 1000,
                    'animationEasing' => 'cubicOut'
                ]]
            ],
            'bar' => [
                'xAxis' => [
                    'type' => 'category',
                    'axisLine' => [
                        'show' => true,
                        'lineStyle' => ['color' => $axisLineColor]
                    ],
                    'axisTick' => ['show' => false],
                    'axisLabel' => [
                        'show' => true,
                        'color' => $axisFontColor
                    ]
                ],
                'yAxis' => [
                    'type' => 'value',
                    'axisLine' => ['show' => false],
                    'axisTick' => ['show' => false],
                    'axisLabel' => [
                        'color' => $axisFontColor
                    ],
                    'splitLine' => [
                        'show' => true,
                        'lineStyle' => [
                            'color' => $gridLineColor,
                            'type' => 'dashed'
                        ]
                    ]
                ],
                'series' => [[
                    'type' => 'bar',
                    'barMaxWidth' => 50,
                    'itemStyle' => [
                        'borderRadius' => [4, 4, 0, 0]
                    ],
                    'emphasis' => [
                        'focus' => 'series',
                        'itemStyle' => [
                            'borderWidth' => 2
                        ]
                    ],
                    'animation' => true,
                    'animationDuration' => 1500,
                    'animationEasing' => 'elasticOut'
                ]]
            ],
            'pie' => [
                'series' => [[
                    'type' => 'pie',
                    'radius' => ['50%', '70%'],
                    'center' => ['50%', '50%'],
                    'avoidLabelOverlap' => true,
                    'itemStyle' => [
                        'borderRadius' => 4,
                        'borderColor' => '#fff',
                        'borderWidth' => 2
                    ],
                    'label' => [
                        'show' => false
                    ],
                    'emphasis' => [
                        'label' => [
                            'show' => true,
                            'fontSize' => 14,
                            'fontWeight' => 'bold'
                        ],
                        'itemStyle' => [
                            'shadowBlur' => 10,
                            'shadowOffsetX' => 0,
                            'shadowColor' => 'rgba(0, 0, 0, 0.5)'
                        ]
                    ],
                    'labelLine' => [
                        'show' => false
                    ],
                    'animation' => true,
                    'animationDuration' => 1000,
                    'animationEasing' => 'circularOut'
                ]]
            ],
            'scatter' => [
                'xAxis' => [
                    'type' => 'value',
                    'scale' => true,
                    'axisLine' => [
                        'show' => true,
                        'lineStyle' => ['color' => $axisLineColor]
                    ],
                    'axisTick' => ['show' => false],
                    'axisLabel' => [
                        'color' => $axisFontColor
                    ],
                    'splitLine' => [
                        'show' => true,
                        'lineStyle' => [
                            'color' => $gridLineColor,
                            'type' => 'dashed'
                        ]
                    ]
                ],
                'yAxis' => [
                    'type' => 'value',
                    'scale' => true,
                    'axisLine' => ['show' => false],
                    'axisTick' => ['show' => false],
                    'axisLabel' => [
                        'color' => $axisFontColor
                    ],
                    'splitLine' => [
                        'show' => true,
                        'lineStyle' => [
                            'color' => $gridLineColor,
                            'type' => 'dashed'
                        ]
                    ]
                ],
                'series' => [[
                    'type' => 'scatter',
                    'symbolSize' => 12,
                    'symbol' => 'circle',
                    'itemStyle' => [
                        'opacity' => 0.8
                    ],
                    'emphasis' => [
                        'focus' => 'series',
                        'itemStyle' => [
                            'borderWidth' => 2,
                            'shadowBlur' => 10,
                            'shadowColor' => 'rgba(0,0,0,0.3)'
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
