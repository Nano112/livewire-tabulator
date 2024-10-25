<div>
    @once
        @push('styles')
            <link href="{{ config('tabulator.cdn.css', 'https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css') }}" rel="stylesheet">
        @endpush
        @push('scripts')
            <script src="{{ config('tabulator.cdn.js', 'https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js') }}"></script>
        @endpush
    @endonce

    <div wire:ignore x-data="tabulatorComponent()" x-init="initTable">
        <div x-ref="table"></div>
    </div>

    <script>
        function tabulatorComponent() {
            return {
                table: null,
                initTable() {
                    this.table = new Tabulator(this.$refs.table, {
                        data: @json($data),
                        columns: @json($columns),
                        ...@json($options),
                        cellClick: (e, cell) => {
                            @this.call('handleCellClick', cell.getData());
                        },
                        rowClick: (e, row) => {
                            @this.call('handleRowClick', row.getData());
                        },
                        dataChanged: (data) => {
                            @this.updateData(data);
                        }
                    });

                    Livewire.on('dataUpdated', (data) => {
                        this.table.replaceData(data);
                    });
                }
            }
        }
    </script>
</div>
