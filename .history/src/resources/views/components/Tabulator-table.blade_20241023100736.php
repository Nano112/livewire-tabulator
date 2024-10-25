<div>
    @assets
        @once
            <link href="{{ config('tabulator.cdn.css', 'https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css') }}" rel="stylesheet">
            <script src="{{ config('tabulator.cdn.js', 'https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js') }}"></script>
        @endonce
    @endassets

    <div wire:ignore x-data="tabulatorComponent()" class="space-y-4">
        {{-- Table Controls --}}
        @if($enableSearch || $enableExport || $enableColumnVisibility)
            <div class="flex justify-between items-center">
                @if($enableSearch)
                    <div class="flex items-center space-x-2">
                        <input 
                            type="text" 
                            x-ref="searchInput"
                            placeholder="{{ $searchPlaceholder }}"
                            class="px-4 py-2 border rounded-lg"
                            @input="handleSearch($event.target.value)"
                        >
                    </div>
                @endif

                <div class="flex items-center space-x-4">
                    @if($enableColumnVisibility)
                        <button 
                            @click="toggleColumnVisibility"
                            class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200"
                        >
                            Toggle Columns
                        </button>
                    @endif

                    @if($enableExport)
                        <div class="relative" x-data="{ open: false }">
                            <button 
                                @click="open = !open"
                                class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200"
                            >
                                Export
                            </button>
                            <div 
                                x-show="open" 
                                @click.away="open = false"
                                class="absolute right-0 mt-2 bg-white border rounded-lg shadow-lg"
                            >
                                @foreach($exportFormats as $format)
                                    <button 
                                        @click="exportData('{{ $format }}')"
                                        class="block w-full px-4 py-2 text-left hover:bg-gray-100"
                                    >
                                        {{ strtoupper($format) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Tabulator Table --}}
        <div x-ref="table"></div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Alpine.data('tabulatorComponent', () => ({
                table: null,
                
                init() {
                    this.initTable();
                    this.setupEventListeners();
                },

                initTable() {
                    this.table = new Tabulator(this.$refs.table, {
                        data: @json($data),
                        columns: @json($columns),
                        ...@json($options),
                        dataChanged: (data) => {
                            @this.updateData(data);
                        },
                    });
                },

                setupEventListeners() {
                    // Listen for Livewire events
                    @this.on('refreshTable', () => {
                        this.table.setData(@json($data));
                    });

                    @this.on('updateTableData', (newData) => {
                        this.table.setData(newData);
                    });
                },

                handleSearch(value) {
                    this.table.setFilter('multi', value);
                },

                toggleColumnVisibility() {
                    const columns = this.table.getColumns();
                    const menu = document.createElement('div');
                    menu.classList.add('column-visibility-menu');
                    
                    columns.forEach(column => {
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.checked = column.isVisible();
                        checkbox.addEventListener('change', () => {
                            column.toggle();
                        });
                        
                        const label = document.createElement('label');
                        label.appendChild(checkbox);
                        label.appendChild(document.createTextNode(column.getDefinition().title));
                        
                        menu.appendChild(label);
                    });
                },

                exportData(format) {
                    switch(format) {
                        case 'csv':
                            this.table.download(format, 'data.csv');
                            break;
                        case 'xlsx':
                            this.table.download(format, 'data.xlsx');
                            break;
                        case 'pdf':
                            this.table.download(format, 'data.pdf');
                            break;
                    }
                    @this.exportTable(format);
                }
            }));
        });
    </script>
</div>