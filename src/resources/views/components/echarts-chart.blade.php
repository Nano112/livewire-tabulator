<div class="bg-white h-full w-full">
    @assets
        @once
            <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.1/dist/echarts.min.js"></script>
        @endonce
    @endassets

    <div wire:ignore class="w-full h-full" id="{{ $chartId }}"></div>

    @script
        <script data-navigate-once>
        (function() {
            let chart = null;
            let resizeObserver = null;
            const chartElement = document.getElementById('{{ $chartId }}');
            
            function parseOptions(options) {
                // Deep clone the options to avoid reference issues
                const parsedOptions = JSON.parse(JSON.stringify(options));
                
                // Convert formatter string to function if it exists
                if (parsedOptions.tooltip?.formatter && 
                    typeof parsedOptions.tooltip.formatter === 'string' && 
                    parsedOptions.tooltip.formatter.includes('function')) {
                    parsedOptions.tooltip.formatter = eval('(' + parsedOptions.tooltip.formatter + ')');
                }
                
                return parsedOptions;
            }

            function initChart() {
                if (!chartElement) {
                    console.warn('Chart element not found');
                    return;
                }

                const initialOptions = parseOptions(@json($options));

                chart = echarts.init(chartElement, @json($theme));
                chart.setOption(initialOptions);
                setupResizeObserver();
                setupEventHandlers();
            }

            function setupEventHandlers() {
                Livewire.on('updateChartOptions', (data) => {
                    if (!data.chartId || data.chartId !== '{{ $chartId }}') return;
                    if (chart) {
                        const updatedOptions = parseOptions(data.options);
                        chart.setOption(updatedOptions, true);
                    }
                });
            }

            function setupResizeObserver() {
                if (resizeObserver) {
                    resizeObserver.disconnect();
                }

                resizeObserver = new ResizeObserver(entries => {
                    if (chart) {
                        chart.resize();
                    }
                });

                if (chartElement) {
                    resizeObserver.observe(chartElement);
                }

                // Also listen for window resize as a fallback
                window.addEventListener('resize', () => {
                    if (chart) {
                        chart.resize();
                    }
                });
            }

            function cleanup() {
                if (resizeObserver) {
                    resizeObserver.disconnect();
                    resizeObserver = null;
                }
                if (chart) {
                    chart.dispose();
                    chart = null;
                }
                // Remove window resize listener
                window.removeEventListener('resize', () => {
                    if (chart) {
                        chart.resize();
                    }
                });
            }

            // Initialize the chart
            initChart();

            // Cleanup on page navigation or component removal
            window.addEventListener('beforeunload', cleanup);
            document.addEventListener('livewire:navigating', cleanup);
        })();
        </script>
    @endscript
</div>