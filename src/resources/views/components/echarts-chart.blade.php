<div class="bg-white h-full w-full">
    @assets
        @once
            <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.js"></script>
        @endonce
    @endassets

    <div wire:ignore x-data="echartsComponent_{!! $chartId !!}" x-init="init" x-on:beforeunmount.window="cleanup" class="w-full h-full">
        <div x-ref="chart" class="w-full h-full"></div>
    </div>

    @script
        <script data-navigate-once>
            Alpine.data('echartsComponent_{!! $chartId !!}', () => ({
                chart: null,
                eventHandlers: {},
                resizeObserver: null,

                init() {
                    const options = @json($options);
                    
                    // Handle tooltip formatters by converting strings to functions
                    if (options.tooltip?.formatter && typeof options.tooltip.formatter === 'string') {
                        try {
                            // Get just the function body between the curly braces
                            const matches = options.tooltip.formatter.match(/\{([\s\S]*)\}/);
                            if (matches && matches[1]) {
                                const functionBody = matches[1].trim();
                                options.tooltip.formatter = new Function('params', functionBody);
                            }
                        } catch (e) {
                            console.error('Error parsing tooltip formatter:', e);
                        }
                    }
            
                    if (!this.$refs.chart) {
                        console.warn('Chart element not found, skipping initialization');
                        return;
                    }
            
                    this.chart = echarts.init(this.$refs.chart, @json($theme));
                    this.chart.setOption(options);
                    
                    this.setupEventHandlers();
                    this.setupResizeObserver();
                },

                setupEventHandlers() {
                    const chartId = @json($chartId);
                    const events = @json($events);
                    
                    // Setup default updateChartOptions handler
                    Livewire.on('updateChartOptions', (data) => {
                        if (!data.chartId) {
                            console.error('Chart ' + @json($chartId) +
                                ': No chartId provided in the updateChartOptions event');
                            console.log(data);
                            return;
                        }
                        if (data.chartId !== @json($chartId)) {
                            return;
                        }
                        if (this.chart && data) {
                            this.chart.setOption(data.options, data.notMerge, data.lazyUpdate);
                            
                        }
                    });

                    // Setup custom events
                    const context = { chart: this.chart, chartId };
                    
                    for (const [event, handler] of Object.entries(events)) {
                        try {
                            const wrappedHandler = new Function(
                                ...Object.keys(context),
                                `return ${handler}`
                            );
                            
                            this.eventHandlers[event] = ((data) => {
                                if (!data.chartId || data.chartId !== chartId) return;
                                wrappedHandler(...Object.values(context))(data);
                            }).bind(this);

                            Livewire.on(event, this.eventHandlers[event]);
                        } catch (e) {
                            console.error(`Error binding event ${event}:`, e);
                        }
                    }
                },

                setupResizeObserver() {
                    if (this.resizeObserver) {
                        this.resizeObserver.disconnect();
                        this.resizeObserver = null;
                    }

                    this.resizeObserver = new ResizeObserver(() => {
                        if (this.chart) {
                            this.chart.resize();
                        }
                    });

                    if (this.$refs.chart) {
                        this.resizeObserver.observe(this.$refs.chart);
                    }
                },

                cleanup() {
                    if (this.resizeObserver) {
                        this.resizeObserver.disconnect();
                        this.resizeObserver = null;
                    }
                    if (this.chart) {
                        this.chart.dispose();
                        this.chart = null;
                    }
                    // Clean up event handlers
                    for (const [event, handler] of Object.entries(this.eventHandlers)) {
                        Livewire.off(event, handler);
                    }
                    this.eventHandlers = {};
                }
            }));
        </script>
    @endscript
</div>