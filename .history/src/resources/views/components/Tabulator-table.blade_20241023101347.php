<div>
    @assets
        @once
            <link href="{{ config('tabulator.cdn.css', 'https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css') }}" rel="stylesheet">
            <script src="{{ config('tabulator.cdn.js', 'https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js') }}"></script>
        @endonce
    @endassets

    <div wire:ignore x-data="tabulatorComponent()">
        <div x-ref="table"></div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Alpine.data('tabulatorComponent', () => ({
                table: null,
                init() {
                    this.table = new Tabulator(this.$refs.table, {
                        data: @json($data),
                        columns: @json($columns),
                        ...@json($options),
                        dataChanged: (data) => {
                            @this.updateData(data);
                        },
                        // Add sort event handler
                        dataSorted: (sorters) => {
                            if (sorters.length > 0) {
                                @this.dispatch('sortTable', {
                                    field: sorters[0].field,
                                    direction: sorters[0].dir
                                });
                            }
                        }
                    });

                    // Listen for Livewire events
                    @this.on('tableRefreshed', () => {
                        this.table.setData(@json($data));
                    });

                    @this.on('tableSorted', ({ field, direction }) => {
                        this.table.setSort(field, direction);
                    });

                    @this.on('tableFiltered', () => {
                        this.table.setData(@json($data));
                    });
                }
            }));
        });
    </script>
</div>