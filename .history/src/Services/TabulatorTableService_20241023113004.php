<?php

namespace Nano112\LivewireTabulator\Services;

use Illuminate\Support\Traits\Macroable;

class TabulatorTableService
{
    use Macroable;

    protected $data = [];
    protected $columns = [];
    protected $options = [];

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public static function make($data = [])
    {
        return new static($data);
    }

    public function column($title, $field, $options = [])
    {
        $column = array_merge(['title' => $title, 'field' => $field], $options);
        $this->columns[] = $column;
        return $this;
    }

    public function option($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }

    public function getConfig()
    {
        return [
            'data' => $this->data,
            'columns' => $this->columns,
            'options' => $this->options,
        ];
    }
}
