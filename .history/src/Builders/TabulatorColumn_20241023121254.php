<?php

namespace Nano112\LivewireTabulator\Builders;

class TabulatorColumn
{
    protected $attributes = [];

    public function __construct($title, $field)
    {
        $this->attributes['title'] = $title;
        $this->attributes['field'] = $field;
    }

    public function sortable($value = true)
    {
        $this->attributes['sortable'] = $value;
        return $this;
    }

    public function align($alignment)
    {
        $this->attributes['align'] = $alignment;
        return $this;
    }

    public function width($width)
    {
        $this->attributes['width'] = $width;
        return $this;
    }

    public function formatter($formatter, $params = [])
    {
        $this->attributes['formatter'] = $formatter;
        if (!empty($params)) {
            $this->attributes['formatterParams'] = $params;
        }
        return $this;
    }

    public function formatterParams(array $params)
    {
        $this->attributes['formatterParams'] = $params;
        return $this;
    }

    public function editor($editor)
    {
        $this->attributes['editor'] = $editor;
        return $this;
    }

    public function validator($validator)
    {
        $this->attributes['validator'] = $validator;
        return $this;
    }

    public function toArray()
    {
        return $this->attributes;
    }
}
