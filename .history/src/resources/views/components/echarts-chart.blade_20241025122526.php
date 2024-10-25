<div class="bg-white">
    @assets
        @once
            <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>
        @endonce
    @endassets

    <div wire:ignore x-data="echartsComponent()" x-on:beforeunmount.window="cleanup" style="width: 100%; height: 100%;">
        <div x-ref="chart" style="width: 100%; min-width: 200px; height: 600px;"></div>
    </div>

    @script
        <script>
            Alpine.data('echartsComponent', () => ({
                chart: null,
                resizeObserver: null,
                updateListener: null,

                init() {
                    if (!this.$refs.chart) {
                        console.error('Chart container not found');
                        return;
                    }

                    Alpine.nextTick(() => {
                        try {
                            // Initialize chart with theme if provided
                            this.chart = echarts.init(this.$refs.chart, @json($theme));

                            const initialOptions = @json($options);
                            
                            // Validate and process initial options
                            if (initialOptions && Array.isArray(initialOptions.series)) {
                                initialOptions.series = initialOptions.series.filter(serie => {
                                    // Validate series structure
                                    if (!serie || !Array.isArray(serie.data)) {
                                        console.warn('Invalid series structure:', serie);
                                        return false;
                                    }
                                    // Check for valid data points
                                    return serie.data.some(value => value !== null && value !== undefined);
                                });

                                // Ensure at least one valid series exists
                                if (initialOptions.series.length === 0) {
                                    console.warn('No valid series after filtering');
                                    initialOptions.series = [{
                                        type: 'line',
                                        data: []
                                    }];
                                }
                            } else {
                                console.error('Invalid series structure in options');
                                return;
                            }

                            console.log('Processed initial options:', initialOptions);

                            // Set initial options
                            if (initialOptions) {
                                this.chart.setOption(initialOptions, true);
                            } else {
                                console.error('No options provided for the chart');
                            }

                            // Set up resize observer
                            this.setupResizeObserver();

                            // Set up Livewire update listener
                            this.setupUpdateListener();

                        } catch (error) {
                            console.error('Chart initialization error:', error);
                            console.log('Options at error:', initialOptions);
                        }
                    });
                },

                setupResizeObserver() {
                    if (this.resizeObserver) {
                        this.resizeObserver.disconnect();
                    }

                    this.resizeObserver = new ResizeObserver(() => {
                        if (this.chart) {
                            try {
                                this.chart.resize();
                            } catch (error) {
                                console.error('Error resizing chart:', error);
                            }
                        }
                    });

                    try {
                        this.resizeObserver.observe(this.$refs.chart);
                    } catch (error) {
                        console.error('Error setting up resize observer:', error);
                    }
                },

                setupUpdateListener() {
                    // Remove existing listener if it exists
                    if (this.updateListener) {
                        this.updateListener();
                    }

                    this.updateListener = Livewire.on('updateChartOptions', (data) => {
                        console.log('Update received:', data);
                        if (!this.chart || !data) {
                            console.warn('Chart or update data not available');
                            return;
                        }

                        try {
                            const options = data.newOptions || data;

                            // Validate update options
                            if (options.series && Array.isArray(options.series)) {
                                options.series = options.series.filter(serie => {
                                    return serie && 
                                           Array.isArray(serie.data) && 
                                           serie.data.some(value => value !== null && value !== undefined);
                                });

                                if (options.series.length === 0) {
                                    console.warn('No valid series in update');
                                    return;
                                }
                            }

                            // Apply update with transition
                            this.chart.setOption(options, {
                                replaceMerge: ['series', 'xAxis', 'yAxis'],
                                transition: {
                                    duration: 300
                                }
                            });
                        } catch (error) {
                            console.error('Error updating chart:', error);
                            // Attempt recovery by reinitializing
                            this.cleanup();
                            this.init();
                        }
                    });
                },

                cleanup() {
                    // Clean up resize observer
                    if (this.resizeObserver) {
                        try {
                            this.resizeObserver.disconnect();
                        } catch (e) {
                            console.error('Error disconnecting observer:', e);
                        }
                        this.resizeObserver = null;
                    }

                    // Clean up chart instance
                    if (this.chart) {
                        try {
                            this.chart.dispose();
                        } catch (e) {
                            console.error('Error disposing chart:', e);
                        }
                        this.chart = null;
                    }

                    // Clean up Livewire listener
                    if (this.updateListener) {
                        try {
                            this.updateListener();
                        } catch (e) {
                            console.error('Error removing update listener:', e);
                        }
                        this.updateListener = null;
                    }
                }
            }));
        </script>
    @endscript
</div>