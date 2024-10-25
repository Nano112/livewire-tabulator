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
    @script
    <script>
        Alpine.data('tabulatorComponent', () => ({
                table: null,
                init() {

                    console.log('Alpine init called');
                    this.table = new Tabulator(this.$refs.table, {
                        data: @json($data),
                        columns: @json($columns),
                        ...@json($options),
                        dataChanged(data) {
                            @this.updateData(data);
                        }
                    });
                    let context = {$wire};
                    for (let [callback, handler] of Object.entries(@json($callbacks))) {
                        try{
                            const wrappedHandler = new Function(
                        ...Object.keys(context), // spread the parameter names
                        'return ' + handler
                    );
                    
                    // Bind the handler with the context variables
                    this.table.on(callback, wrappedHandler(...Object.values(context)));
                        } catch (e) {
                            console.error(`Error binding event ${callback}: ${e.message}`);
                        }
                    }
                }
            }));
    </script>
    @endscript
</div>