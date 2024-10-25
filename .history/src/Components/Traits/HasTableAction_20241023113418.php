<?php

namespace Nano112\LivewireTabulator\Components\Traits;

trait HasTableActions
{
    public function refreshTable()
    {
        $this->mount(); // Remount to refresh data and configuration
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'data') {
            $this->emit('dataUpdated', $this->data);
        }
    }

    // Placeholder for future event handling methods
    public function handleRowClick($rowData)
    {
        // Logic to handle row click event
    }

    public function handleCellClick($cellData)
    {
        // Logic to handle cell click event
    }
}
