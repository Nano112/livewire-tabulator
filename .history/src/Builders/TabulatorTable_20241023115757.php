<?php

namespace Nano112\LivewireTabulator\Builders;

class TabulatorTable
{
    protected $data = [];
    protected $columns = [];
    protected $options = [];
    protected $events = [];

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

    public function on($event, $callback)
    {
        $this->events[$event] = $callback;
        return $this;
    }

    public function render()
    {
        // Return the Blade view with necessary data
        return view('livewire-tabulator::components.tabulator-table', [
            'data'    => $this->data,
            'columns' => $this->columns,
            'options' => $this->options,
            'events'  => $this->events,
        ])->render();
    }
}
