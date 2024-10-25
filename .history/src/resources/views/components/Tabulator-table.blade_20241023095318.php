<div>
    @assets
        @once
            <link href="{{ config('tabulator.cdn.css', 'https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css') }}" rel="stylesheet">
            <script src="{{ config('tabulator.cdn.js', 'https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js') }}"></script>
        @endonce
    @endassets

    <div wire:ignore x-data="{
        table: null,
        init() {
            console.log('Tabulator init');
            let component = this;
            this.table = new Tabulator(this.$refs.table, {
                data: @json($data),
                columns: @json($columns),
                ...@json(array_merge(config('tabulator.defaults', []), $options)),
                dataChanged(data) {
                    @this.updateData(data);
                }
            });
        }
    }">
        <div x-ref="table"></div>
    </div>
</div>