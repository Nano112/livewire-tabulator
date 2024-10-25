<?php

namespace Nano112\LivewireTabulator\Components;

use Livewire\Component;
use Illuminate\Support\Collection;

class TabulatorTable extends Component
{
    // Core properties
    public $data = [];
    public $columns = [];
    public $options = [];
    
    // Configuration properties
    public $enableSearch = true;
    public $searchPlaceholder = 'Search...';
    public $enableExport = false;
    public $exportFormats = ['csv', 'xlsx', 'pdf'];
    public $enableColumnVisibility = true;
    
    // Event handlers
    protected $listeners = [
        'refreshTable' => 'refreshData',
        'updateTableData' => 'updateData',
        'sortTable' => 'handleSort',
        'filterTable' => 'handleFilter'
    ];

    public function mount(
        $data = [], 
        $columns = [], 
        $options = [],
        $enableSearch = true,
        $enableExport = false,
        $enableColumnVisibility = true
    ) {
        $this->data = $data;
        $this->columns = $this->processColumns($columns);
        $this->options = array_merge($this->getDefaultOptions(), $options);
        $this->enableSearch = $enableSearch;
        $this->enableExport = $enableExport;
        $this->enableColumnVisibility = $enableColumnVisibility;
    }

    protected function processColumns($columns)
    {
        return collect($columns)->map(function ($column) {
            // Add formatter if not specified
            if (!isset($column['formatter'])) {
                $column['formatter'] = $this->getDefaultFormatter($column);
            }
            
            // Add default sorting if not specified
            if (!isset($column['sorter'])) {
                $column['sorter'] = 'string';
            }
            
            return $column;
        })->toArray();
    }

    protected function getDefaultFormatter($column)
    {
        // Define default formatters based on common field names or types
        $formatters = [
            'date' => 'datetime',
            'email' => 'link',
            'url' => 'link',
            'status' => 'traffic',
            'boolean' => 'tickCross',
        ];

        foreach ($formatters as $key => $formatter) {
            if (str_contains(strtolower($column['field']), $key)) {
                return $formatter;
            }
        }

        return 'plaintext';
    }

    protected function getDefaultOptions()
    {
        return [
            'pagination' => true,
            'paginationSize' => 10,
            'layout' => 'fitColumns',
            'responsiveLayout' => 'collapse',
            'placeholder' => 'No Data Available',
            'selectable' => true,
            'selectableRangeMode' => true,
        ];
    }

    public function refreshData()
    {
        $this->dispatch('tableRefreshed');
    }

    public function updateData($newData)
    {
        $this->data = $newData;
        $this->dispatch('dataUpdated', $newData);
    }

    public function handleSort($field, $direction)
    {
        // Handle sorting logic
        $this->dispatch('tableSorted', [
            'field' => $field,
            'direction' => $direction
        ]);
    }

    public function handleFilter($filters)
    {
        // Handle filtering logic
        $this->dispatch('tableFiltered', $filters);
    }

    public function exportTable($format)
    {
        if (!in_array($format, $this->exportFormats)) {
            return;
        }
        
        $this->dispatch('tableExported', $format);
    }

    public function render()
    {
        return view('livewire-tabulator::components.tabulator-table');
    }
}