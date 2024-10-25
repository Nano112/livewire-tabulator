<?php

namespace Nano112\LivewireTabulator\Components\Traits;

trait HasTableActions
{
    /**
     * Refresh the table by remounting the component.
     */
    public function refreshTable()
    {
        $this->mount();
    }

    /**
     * Listen for updates to the data property and emit an event when it changes.
     *
     * @param string $propertyName
     */
    public function updated($propertyName)
    {
        if ($propertyName === 'data') {
            $this->emit('dataUpdated', $this->data);
        }
    }

    /**
     * Handle row click events from the Tabulator table.
     *
     * @param array $rowData
     */
    public function handleRowClick($rowData)
    {
        // Example: Emit an event or perform an action with the clicked row data
        $this->emit('rowClicked', $rowData);
    }

    /**
     * Handle cell click events from the Tabulator table.
     *
     * @param array $cellData
     */
    public function handleCellClick($cellData)
    {
        // Example: Emit an event or perform an action with the clicked cell data
        $this->emit('cellClicked', $cellData);
    }
}
