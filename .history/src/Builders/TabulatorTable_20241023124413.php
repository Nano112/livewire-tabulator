<?php

namespace Nano112\LivewireTabulator\Builders;
use Illuminate\Support\Facades\File;
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

    protected function resolveViewPath()
    {
        // Try multiple possible locations
        $locations = [
            __DIR__ . '/../resources/views/tabulator-table.blade.php',
            base_path('packages/Nano112/livewire-tabulator/src/resources/views/tabulator-table.blade.php'),
            resource_path('views/vendor/livewire-tabulator/tabulator-table.blade.php'),
        ];

        foreach ($locations as $path) {
            if (File::exists($path)) {
                return $path;
            }
        }

        return null;
    }
    

    public function render()
    {

        if (!view()->exists('livewire-tabulator::tabulator-table')) {
            throw new \Exception('View not found. Available paths: ' . implode(', ', app('view.finder')->getPaths()));
        }

        $viewPath = $this->resolveViewPath();
        
        // Debug information
        $debug = [
            'Possible View Paths' => [
                __DIR__ . '/../resources/views/tabulator-table.blade.php',
                base_path('packages/Nano112/livewire-tabulator/src/resources/views/tabulator-table.blade.php'),
                resource_path('views/vendor/livewire-tabulator/tabulator-table.blade.php'),
            ],
            'Found View Path' => $viewPath,
            'View Exists' => $viewPath ? 'Yes' : 'No',
            'Current Directory' => __DIR__,
            'Base Path' => base_path(),
            'Resource Path' => resource_path(),
            'Registered View Paths' => app('view.finder')->getPaths(),
            'View Namespaces' => array_keys(app('view.finder')->getHints()),
        ];
        dd($debug);

        return view('livewire-tabulator::tabulator-table', [
            'data'    => $this->data,
            'columns' => $this->columns,
            'options' => $this->options,
            'events'  => $this->events,
        ])->render();
    }
}
