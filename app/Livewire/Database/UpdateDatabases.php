<?php
namespace App\Livewire\Database;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Schema;
class UpdateDatabases extends Component
{
    public $tables;
    public $tableData=[];
    public $tableSchema = [];
    public $tableName='';
    public function mount()
    {
        $this->tables = $this->getDatabaseTables();
    }

    public function getDatabaseTables()
    {
        $databaseName = config('database.connections.mysql.database');
        $tables = DB::select("SHOW TABLES FROM $databaseName");
        return array_column($tables, 'Tables_in_' . $databaseName);
    }

    public function handleClick($table)
    {
        $this->tableData = DB::table($table)->get();

    // Fetch table schema using Schema facade
    $columns = Schema::getColumnListing($table);
    $tableSchema = [];
    // dd($columns);
    // foreach ($columns as $column) {
    //     $columnType = Schema::getColumnType($table, $column);
    //     // $nullable = Schema::getColumnNullable($table, $column);
    //     $default = Schema::getColumnDefault($table, $column);

    //     $tableSchema[] = [
    //         'name' => $column,
    //         'type' => $columnType,
    //         // 'nullable' => $nullable,
    //         'default' => $default,
    //     ];
    // }

    // Assign table schema and name
    $this->tableName = $table;
    $this->tableSchema = $columns;

    }

    public function render()
    {
        return view('livewire.database.update-databases', [
            'tables' => $this->tables,
        ]);
    }
}
