<div class="bg-white">
    @assets
        @once
            <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>
        @endonce
    @endassets

    <div wire:ignore x-data="echartsComponent()" 
         x-on:beforeunmount.window="cleanup"
         style="width: 100%; height: 100%;">
        <div x-ref="chart" style="width: 100%; height: 600px;"></div>
    </div>

    @script
        <script>
            Alpine.data('echartsComponent', () => ({
                chart: null,
                resizeObserver: null,

                init() {
                    this.chart = echarts.init(this.$refs.chart, @json($theme));
                    
                    // Set initial options if available
                    const initialOptions = @json($options);
                    if (initialOptions) {
                        this.chart.setOption(initialOptions);
                        console.log('Initial options set:', initialOptions);
                    }

                    // Handle Livewire updates - FIXED EVENT HANDLER
                    Livewire.on('updateChartOptions', (data) => {
                        if (this.chart && data) {
                            try {
                                const options = data.newOptions ? data.newOptions : data;
                                this.chart.setOption(options, { 
                                    replaceMerge: ['series', 'xAxis', 'yAxis'],
                                    transition: {
                                        duration: 300
                                    }
                                });
                                console.log('Chart updated with options:', options);
                            } catch (error) {
                                console.error('Error updating chart:', error);
                            }
                        }
                    });

                    // Handle window resize
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