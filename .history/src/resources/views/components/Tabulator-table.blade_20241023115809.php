<div>
    @once
        @push('styles')
            <link href="{{ config('tabulator.cdn.css') }}" rel="stylesheet">
        @endpush
        @push('scripts')
            <script src="{{ config('tabulator.cdn.js') }}"></script>
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
</div>
