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

        //dump the available views in livewire-tabulator to debug since components.tabulator-table is not found
        //list the views in livewire-tabulator
        $path = view()->getFinder()->getHints()['livewire-tabulator'];
        //list the content 
        dump($path);
        dd(scandir("/var/www/html/packages/Nano112/livewire-tabulator/src/../src/resources", SCANDIR_SORT_DESCENDING));
        
        return view('livewire-tabulator::src.resources.tabulator-table', [
            'data'    => $this->data,
            'columns' => $this->columns,
            'options' => $this->options,
            'events'  => $this->events,
        ])->render();
    }
}
