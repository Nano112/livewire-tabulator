<?php

namespace Nano112\LivewireTabulator\Components;

use Livewire\Component;

class TabulatorTable extends Component
{
    public $data = [];
    public $columns = [];
    public $options = [];
    public $enableSearch = true;
    public $enableExport = false;
    public $enableColumnVisibility = true;
    public $currentSort = null;

    protected $listeners = [
        'sortTable' => 'handleSort',
        'filterTable' => 'handleFilter',
        'refreshTable' => 'refresh',
        'updateTableData' => 'updateData'
    ];

    public function mount($data = [], $columns = [], $options = [])
    {
        $this->data = $data;
        $this->columns = $columns;
        $this->options = array_merge([
            'pagination' => true,
            'paginationSize' => 10,
            'layout' => 'fitColumns',
            'responsiveLayout' => 'collapse',
            'placeholder' => 'No Data Available',
        ], $options);
    }

    public function handleSort($params)
    {
        // Ensure we have the correct parameters
        if (!isset($params['field']) || !isset($params['direction'])) {
            return;
        }

        $field = $params['field'];
        $direction = $params['direction'];

        // Store current sort state
        $this->currentSort = [
            'field' => $field,
            'direction' => $direction
        ];

        // Sort the data array
        usort($this->data, function ($a, $b) use ($field, $direction) {
            if (!isset($a[$field]) || !isset($b[$field])) {
                return 0;
            }

            $comparison = $a[$field] <=> $b[$field];
            return $direction === 'desc' ? -$comparison : $comparison;
        });

        // Notify that sorting has been completed
        $this->dispatch('tableSorted', [
            'field' => $field,
            'direction' => $direction
        ]);
    }

    public function handleFilter($params)
    {
        $this->js(<<<JS
            console.log('Filtering table...');
        JS);
        $this->js(json_encode($params));
        if (!isset($params['field']) || !isset($params['value'])) {
            return;
        }

        $field = $params['field'];
        $value = $params['value'];

        $this->data = array_filter($this->data, function ($row) use ($field, $value) {
            return isset($row[$field]) && $row[$field] == $value;
        });

        $this->dispatch('tableFiltered');
    }

    public function refresh()
    {
        $this->dispatch('tableRefreshed');
    }

    public function updateData($newData)
    {
        $this->data = $newData;
        $this->dispatch('dataUpdated', $newData);
    }

    public function render()
    {
        return view('livewire-tabulator::components.tabulator-table');
    }
}