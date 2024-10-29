<div class="bg-white h-full w-full">
    @assets
        @once
            <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>
        @endonce
    @endassets

    <div wire:ignore x-data="echartsComponent_{!! $chartId !!}" x-init="init" x-on:beforeunmount.window="cleanup"
        class="w-full h-full">
        <div x-ref="chart" class="w-full h-full"></div>
    </div>

    @script
        <script>
            Alpine.data('echartsComponent_{!! $chartId !!}', () => ({
                chart: null,
                resizeObserver: null,

                init() {
                    const options = @json($options);
                    console.groupCollapsed("Options for chart " + @json($chartId) + ":");
                    console.log(options);
                    console.groupEnd();

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
                                transition: {
                                    duration: 300
                                }
                            });
                        }
                    });

                },


            }));
        </script>
    @endscript
</div>
