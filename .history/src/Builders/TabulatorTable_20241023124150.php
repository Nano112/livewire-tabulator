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

        if (!view()->exists('livewire-tabulator::tabulator-table')) {
            throw new \Exception('View not found. Available paths: ' . implode(', ', app('view.finder')->getPaths()));
        }

        $viewPath = __DIR__ . '/../resources/views/tabulator-table.blade.php';
        $viewExists = file_exists($viewPath);
        $registeredPaths = view()->getFinder()->getPaths();
        $namespaces = view()->getFinder()->getHints();

        // Dump debug information
        dump([
            'View Path' => $viewPath,
            'View Exists' => $viewExists,
            'File Contents' => $viewExists ? file_get_contents($viewPath) : 'File not found',
            'Registered Paths' => $registeredPaths,
            'Registered Namespaces' => array_keys($namespaces),
            'Livewire Tabulator Paths' => $namespaces['livewire-tabulator'] ?? 'Not registered'
        ]);
        
        return view('livewire-tabulator::tabulator-table', [
            'data'    => $this->data,
            'columns' => $this->columns,
            'options' => $this->options,
            'events'  => $this->events,
        ])->render();
    }
}
