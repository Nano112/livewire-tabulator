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
        // Debug view resolution
        $debug = [
            'View Namespaces' => array_keys(View::getFinder()->getHints()),
            'View Exists' => View::exists('livewire-tabulator::tabulator-table'),
            'Package Views Path' => __DIR__ . '/../resources/views',
            'Published Views Path' => resource_path('views/vendor/livewire-tabulator'),
        ];
        
        dd($debug);

        if (!View::exists('livewire-tabulator::tabulator-table')) {
            throw new \Exception('Tabulator view not found. Please ensure the package views are published.');
        }

        return View::make('livewire-tabulator::tabulator-table', [
            'data' => $this->data,
            'columns' => $this->columns,
            'options' => $this->options,
            'events' => $this->events,
        ])->render();
    }
}
