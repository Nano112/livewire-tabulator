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
                    try {
                        this.chart = echarts.init(this.$refs.chart, @json($theme));
                        
                        const initialOptions = @json($options);
                        console.log('Initial options:', initialOptions);
                        
                        if (initialOptions) {
                            this.chart.setOption(initialOptions);
                        }

                        Livewire.on('updateChartOptions', (data) => {
                            console.log('Update received:', data);
                            if (this.chart && data) {
                                const options = data.newOptions || data;
                                this.chart.setOption(options, { 
                                    replaceMerge: ['series', 'xAxis', 'yAxis'],
                                    transition: { duration: 300 }
                                });
                            }
                        });


                    } catch (error) {
                        console.error('Chart initialization error:', error);
                        console.log('Options at error:', initialOptions);
                    }
                },

               
            }));
        </script>
    @endscript
</div>