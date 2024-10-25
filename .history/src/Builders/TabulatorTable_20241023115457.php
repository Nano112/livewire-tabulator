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
        // Render the table HTML and JavaScript
        // This method should return the HTML string
        // For simplicity, we'll return a placeholder
        return '<div id="tabulator-table"></div><script>// Tabulator initialization script</script>';
    }
}
