<div class="bg-white">
    @assets
        @once
            <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>
        @endonce
    @endassets

    <div wire:ignore x-data="echartsComponent()" x-on:beforeunmount.window="cleanup" style="width: 100%; height: 100%;">
        <div x-ref="chart" style="width: 100%; height: 600px;"></div>
    </div>

    @script
        <script>
            Alpine.data('echartsComponent', () => ({
                chart: null,
                resizeObserver: null,

                init() {
                    init() {
                        try {
                            this.chart = echarts.init(this.$refs.chart, @json($theme));
                    
                            const initialOptions = @json($options);
                            
                            // More defensive series filtering
                            if (initialOptions && Array.isArray(initialOptions.series)) {
                                initialOptions.series = initialOptions.series.filter(serie => {
                                    // Check if serie exists and has valid structure
                                    if (!serie || !Array.isArray(serie.data)) {
                                        return false;
                                    }
                                    // Check if series has any non-null data points
                                    return serie.data.some(value => value !== null && value !== undefined);
                                });
                    
                                // Ensure at least one valid series exists
                                if (initialOptions.series.length === 0) {
                                    console.warn('No valid series after filtering');
                                    // Add a dummy series to prevent the error
                                    initialOptions.series = [{
                                        type: 'line',
                                        data: []
                                    }];
                                }
                            } else {
                                console.error('Invalid series structure in options');
                                return;
                            }
                    
                            // Ensure options are properly structured before setting
                            if (initialOptions && initialOptions.series) {
                                this.chart.setOption(initialOptions, true); // Use true for clear previous options
                            }
                    
                        } catch (error) {
                            console.error('Chart initialization error:', error);
                            console.log('Options at error:', initialOptions);
                        }
                    }

                    Livewire.on('updateChartOptions', (data) => {
                        console.log('Update received:', data);
                        if (this.chart && data) {
                            const options = data.newOptions || data;
                            this.chart.setOption(options, {
                                replaceMerge: ['series', 'xAxis', 'yAxis'],
                                transition: {
                                    duration: 300
                                }
                            });
                        }
                    });

                    this.resizeObserver = new ResizeObserver(() => {
                        if (this.chart) {
                            this.chart.resize();
                        }
                    });
                    this.resizeObserver.observe(this.$refs.chart);

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
                }
            }));
        </script>
    @endscript
</div>
