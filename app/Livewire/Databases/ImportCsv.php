<?php

namespace App\Livewire\Databases;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportCsv extends Component
{use WithFileUploads;

    public $file;
    public $tableName;

    protected $rules = [
        'file' => 'required|file|mimes:csv,txt',
    ];

    public function updatedFile()
    {
        $this->validate();
    }

    public function import()
    {
        $this->validate();

        // Check if the file name matches the table name
        if ($this->file->getClientOriginalName() !== "{$this->tableName}.csv") {
            session()->flash('error', 'File name does not match the required format.');
            return;
        }

        // Excel::import(new UsersImport, $this->file->getRealPath());

        session()->flash('success', 'Table updated successfully.');
    }

    public function render()
    {
        return view('livewire.import-csv');
    }
}
