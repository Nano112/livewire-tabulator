<div class="bg-red p-4">
    <div wire:ignore x-data="tabulatorComponent">
        <div x-ref="table"></div>
    </div>
    
    @script
        <script data-navigate-once>
            Alpine.data('tabulatorComponent', () => ({
                table: null,
                
                init() {
                    this.initTable();
                    
                    console.log("Tabulator: Registering navigated event listener");

                    document.addEventListener('livewire:navigated', () => {
                        console.log('Navigated Tabulator table');
                        this.initTable();
                    });
                },

                loadTabulator() {
                    return new Promise((resolve, reject) => {
                        // Check if Tabulator is already loaded
                        if (typeof Tabulator !== 'undefined') {
                            return resolve();
                        }

                        // Load CSS
                        if (!document.querySelector('link[href*="tabulator"]')) {
                            const link = document.createElement('link');
                            link.rel = 'stylesheet';
                            link.href = '{{ config('tabulator.cdn.css', 'https://unpkg.com/tabulator-tables@6.3.0/dist/css/tabulator.min.css') }}';
                            document.head.appendChild(link);
                        }

                        // Load JS
                        if (!document.querySelector('script[src*="tabulator"]')) {
                            const script = document.createElement('script');
                            script.src = '{{ config('tabulator.cdn.js', 'https://unpkg.com/tabulator-tables@6.3.0/dist/js/tabulator.min.js') }}';
                            script.onload = () => resolve();
                            script.onerror = () => reject(new Error('Failed to load Tabulator'));
                            document.head.appendChild(script);
                        }
                    });
                },
                
                async initTable() {
                    try {
                        // Load Tabulator if not already loaded
                        await this.loadTabulator();
                        
                        // Cleanup existing instance
                        if (this.table) {
                            this.table.destroy();
                        }

                        let tableData = @json($data);
                        this.table = new Tabulator(this.$refs.table, {
                            reactiveData: true,
                            data: tableData,
                            columns: @json($columns).map(column => {
                                if (typeof column.formatter === 'string' && column.formatter.includes('function')) {
                                    column.formatter = eval('(' + column.formatter + ')');
                                }
                                return column;
                            }),
                            ...@json($options),
                            dataChanged(data) {
                                @this.updateData(data);
                            }
                        });

                        this.setupEventHandlers();
                    } catch (error) {
                        console.error('Failed to initialize Tabulator:', error);
                    }
                },

                setupEventHandlers() {
                    let wireSet = $wire.$parent.$set;
                    let wire = $wire.$parent;
                    let context = {
                        $wire,
                        wire,
                        wireSet,
                        table: this.table,
                        tableData: this.table.getData()
                    };
                    console.log(wire);

                    // Set up callbacks
                    for (let [callback, handler] of Object.entries(@json($callbacks))) {
                        try {
                            const wrappedHandler = new Function(
                                ...Object.keys(context),
                                'return ' + handler
                            );
                            this.table.on(callback, wrappedHandler(...Object.values(context)));
                        } catch (e) {
                            console.error(`Error binding event ${callback}: ${e.message}`);
                        }
                    }

                    // Set up events
                    for (let [event, action] of Object.entries(@json($events))) {
                        try {
                            const wrappedAction = new Function(
                                ...Object.keys(context),
                                'return ' + action
                            );
                            Livewire.on(event, wrappedAction(...Object.values(context)));
                        } catch (e) {
                            console.error(`Error binding event ${event}: ${e.message}`);
                        }
                    }
                }
            }));
        </script>
    @endscript
</div>