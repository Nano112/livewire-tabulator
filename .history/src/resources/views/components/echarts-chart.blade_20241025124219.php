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
                    const options = @json($options);

                    this.chart = echarts.init(this.$refs.chart, @json($theme));
                    if (!options) {
                        console.error('No options provided for the chart');
                        return;
                    }


                    if (options.tooltip && options.tooltip.formatter) {
                        options.tooltip.formatter = new Function('params', options.tooltip.formatter);
                    }
                    
                    this.chart.setOption(options);
                    

                    Livewire.on('updateChartOptions', (data) => {
                        if (this.chart && data) {
                            const newOptions = data.newOptions || data;
                            // Convert formatter here too if needed
                            if (newOptions.tooltip && newOptions.tooltip.formatter) {
                                newOptions.tooltip.formatter = new Function('params', `
                                    var result = params[0].name + '<br />';
                                    params.forEach(function(param) {
                                        result += param.marker + ' ' + param.seriesName + ': ' + param.value + '<br />';
                                    });
                                    return result;
                                `);
                            }
                            this.chart.setOption(newOptions, { 
                                replaceMerge: ['series', 'xAxis', 'yAxis'],
                                transition: { duration: 300 }
                            });
                        }
                    });

                },


            }));
        </script>
    @endscript
</div>