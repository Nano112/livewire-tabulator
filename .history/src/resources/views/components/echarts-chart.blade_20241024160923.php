<div class="bg-white">
    @assets
        @once
            <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>
        @endonce
    @endassets

    <div wire:ignore x-data="echartsComponent()" style="width: 100%; height: 100%;">
        <div x-ref="chart" style="width: 100%; height: 100%;"></div>
    </div>

    @script
        <script>
            Alpine.data('echartsComponent', () => ({
                chart: null,
                resizeObserver: null,

                init() {
                    // Initialize ECharts instance
                    this.chart = echarts.init(this.$refs.chart, @json($theme));
                    
                    // Set initial options
                    this.chart.setOption(@json($options));

                    // Setup context for callbacks and events
                    const wire = $wire.$parent;
                    const context = {
                        wire,
                        chart: this.chart
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
                    this.resizeObserver = new ResizeObserver(() => {
                        this.chart.resize();
                    });
                    this.resizeObserver.observe(this.$refs.chart);

                    // Handle Livewire updates
                    Livewire.on('updateChartOptions', (newOptions) => {
                        this.chart.setOption(newOptions, { 
                            replaceMerge: ['series', 'xAxis', 'yAxis'],
                            transition: {
                                duration: 300
                            }
                        });
                    });

                    // Handle theme changes
                    Livewire.on('updateChartTheme', (newTheme) => {
                        const option = this.chart.getOption();
                        this.chart.dispose();
                        this.chart = echarts.init(this.$refs.chart, newTheme);
                        this.chart.setOption(option);
                    });

                    // Cleanup on destroy
                    this.$watch('$root', () => {
                        if (!this.$root.contains(this.$refs.chart)) {
                            this.cleanup();
                        }
                    });
                },

                cleanup() {
                    if (this.resizeObserver) {
                        this.resizeObserver.disconnect();
                    }
                    if (this.chart) {
                        this.chart.dispose();
                    }
                },

                // Ensure cleanup when component is destroyed
                destroy() {
                    this.cleanup();
                }
            }));
        </script>
    @endscript
</div>