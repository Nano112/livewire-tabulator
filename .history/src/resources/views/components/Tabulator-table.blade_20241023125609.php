<div>
    @assets
        @once
            <link href="{{ config('tabulator.cdn.css', 'https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css') }}" rel="stylesheet">
            <script src="{{ config('tabulator.cdn.js', 'https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js') }}"></script>
        @endonce
    @endassets
    <h1> wdvs </h1>

    <script>
        console.log("Hello");
        function tabulatorComponent() {
            console.log('tabulatorComponent');
            return {
                table: null,
                initTable() {
                    this.table = new Tabulator(this.$refs.table, {
                        data: @json($data),
                        columns: @json($columns),
                        ...@json($options),
                        @if (!empty($events))
                            @foreach ($events as $event => $methodName)
                                {{ $event }}: (e, component) => {
                                    @this.call('{{ $methodName }}', component.getData());
                                },
                            @endforeach
                        @endif
                        dataChanged: (data) => {
                            @this.set('data', data);
                        },
                    });

                    // Listen for Livewire events to refresh or update the table
                    Livewire.on('dataUpdated', (data) => {
                        this.table.replaceData(data);
                    });
                }
            }
        }
    </script>
    <h1> Hello </h1>
    <div wire:ignore x-data="tabulatorComponent()" x-init="initTable">
        <div x-ref="table"></div>
    </div>

    
</div>