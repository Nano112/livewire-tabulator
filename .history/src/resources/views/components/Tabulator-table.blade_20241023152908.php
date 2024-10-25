<div class="bg-red p-4">
    @assets
        @once
            <link href="{{ config('tabulator.cdn.css', 'https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css') }}"
                rel="stylesheet">
            <script src="{{ config('tabulator.cdn.js', 'https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js') }}">
            </script>
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
                    let tableData = @json($data);
                    console.log(tableData)
                    console.log('Alpine init called');
                    this.table = new Tabulator(this.$refs.table, {
                        reactiveData:true,
                        data: tableData,
                        columns: @json($columns),
                        ...@json($options),
                        dataChanged(data) {
                            @this.updateData(data);
                        }
                    });

                    let wireSet = $wire.$parent.$set;
                    let wire =$wire.$parent

                    let context = {
                        $wire,
                        wire,
                        wireSet,
                        table: this.table,
                        tableData,
                    };
                    for (let [callback, handler] of Object.entries(@json($callbacks))) {
                        try {
                            const wrappedHandler = new Function(
                                ...Object.keys(context), // spread the parameter names
                                'return ' + handler
                            );

                            this.table.on(callback, wrappedHandler(...Object.values(context)));
                        } catch (e) {
                            console.error(`Error binding event ${callback}: ${e.message}`);
                        }
                    }

                    //an event might need some action for example the toggledEnable event might need to update the data and the table
                    for (let [event, action] of Object.entries(@json($events))) {
                        try {
                            const wrappedAction = new Function(
                                ...Object.keys(context), // spread the parameter names
                                'return ' + action
                            );

                            Livewire.on(event, wrappedAction(...Object.values(context)));
                            console.log('Event bound', event);
                        } catch (e) {
                            console.error(`Error binding event ${event}: ${e.message}`);
                        }
                    }

                    
                    
                    
                }
            }));

        </script>
    @endscript
</div>
