<?php

namespace App\Livewire;

use Livewire\Component;

class TableName extends Component
{
    public $tableName;
    public function render()
    {
        return view('livewire.table-name');
    }
}
