<?php

namespace Nano112\LivewireTabulator\Builders;

class TabulatorTable
{
    protected $data = [];
    protected $columns = [];
    protected $options = [];
    protected $events = [];

    public static function create()
    {
        return new static();
    }

    public function data($data)
    {
        $this->data = $data;
        return $this;
    }

    public function addColumn($title, $field, $callback = null)
    {
        $column = new TabulatorColumn($title, $field);
        if (is_callable($callback)) {
            $callback($column);
        }
        $this->columns[] = $column->toArray();
        return $this;
    }

    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    public function on($event, $methodName)
    {
        $this->events[$event] = $methodName;
        return $this;
    }
    

    public function render()
    {
        return <<<HTML
        <div>
            @assets
                @once
                    <link href="{{ config('tabulator.cdn.css', 'https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css') }}" rel="stylesheet">
                    <script src="{{ config('tabulator.cdn.js', 'https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js') }}"></script>
                @endonce
            @endassets
        
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
        HTML;
        
    }
}
