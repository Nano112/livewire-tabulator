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
                    
                    // Set initial options
                    let chartOptions = {
                        ...@json($options),
                        dataset: {
                            source: @json($data)
                        }
                    };
                    
                    this.chart.setOption(chartOptions);

                    // Setup context for callbacks and events
                    let wireSet = $wire.$parent.$set;
                    let wire = $wire.$parent;

                    let context = {
                        $wire,
                        wire,
                        wireSet,
                        chart: this.chart,
                        data: this.data,
                    };

                    // Bind callbacks
                    for (let [event, handler] of Object.entries(@json($callbacks))) {
                        try {
                            const wrappedHandler = new Function(
                                ...Object.keys(context),
                                'return ' + handler
                            );
                            this.chart.on(event, wrappedHandler(...Object.values(context)));
                        } catch (e) {
                            console.error(`Error binding event ${event}: ${e.message}`);
                        }
                    }

                    // Bind Livewire events
                    for (let [event, action] of Object.entries(@json($events))) {
                        try {
                            const wrappedAction = new Function(
                                ...Object.keys(context),
                                'return ' + action
                            );
                            Livewire.on(event, wrappedAction(...Object.values(context)));
                        } catch (e) {
                            console.error(`Error binding event ${event}: ${e.message}`);
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