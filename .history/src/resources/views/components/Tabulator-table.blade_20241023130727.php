<div>
    <div wire:ignore x-data="{
        table: null,
        init() {
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