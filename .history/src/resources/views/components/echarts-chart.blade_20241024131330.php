<div class="bg-white">
    @assets
        @once
            <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>
        @endonce
    @endassets

    <div wire:ignore x-data="echartsComponent()" style="width: 100%; height: 400px;">
        <div x-ref="chart" style="width: 100%; height: 100%;"></div>
    </div>

    @script
        <script>
            Alpine.data('echartsComponent', () => ({
                chart: null,
                init() {
                    // Initialize ECharts instance
                    this.chart = echarts.init(this.$refs.chart, @json($theme));
                    
                    // Set initial options with data
                    const chartOptions = @json($options);
                    chartOptions.dataset = {
                        source: @json($data)
                    };
                    
                    this.chart.setOption(chartOptions);

                    // Setup context for callbacks and events
                    const wire = $wire.$parent;
                    const context = {
                        wire,
                        chart: this.chart,
                        data: @json($data)
                    };

                    // Bind callbacks
                    const callbacks = @json($callbacks);
                    for (const [event, handlerStr] of Object.entries(callbacks)) {
                        try {
                            const handler = new Function('context', `
                                return function(params) {
                                    with(context) {
                                        ${handlerStr}
                                    }
                                }
                            `)(context);
                            
                            this.chart.on(event, handler);
                        } catch (e) {
                            console.error(`Error binding callback ${event}:`, e);
                        }
                    }

                    // Bind Livewire events
                    const events = @json($events);
                    for (const [event, actionStr] of Object.entries(events)) {
                        try {
                            const action = new Function('context', `
                                return function(params) {
                                    with(context) {
                                        ${actionStr}
                                    }
                                }
                            `)(context);
                            
                            Livewire.on(event, action);
                        } catch (e) {
                            console.error(`Error binding event ${event}:`, e);
                        }
                    }

                    // Handle window resize
                    window.addEventListener('resize', () => {
                        this.chart.resize();
                    });

                    // Handle Livewire updates
                    Livewire.on('updateChartData', (newData) => {
                        this.chart.setOption({
                            dataset: {
                                source: newData
                            }
                        });
                    });

                    Livewire.on('updateChartOptions', (newOptions) => {
                        this.chart.setOption(newOptions);
                    });
                }
            }));
        </script>
    @endscript
</div>