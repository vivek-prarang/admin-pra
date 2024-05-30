<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImportTable extends Component
{
    use WithFileUploads;
    public $file;
    public $tableName;
    public $mismatchingFields = [];
    public $diff;
    public $header;
    public $message;
    public $ignorebtn = false;
    public $ignoreIt=[];
    public $processing = false;
    protected $rules = [
        'file' => 'required|file|mimes:csv,txt',
    ];
    public $importBtn=false;
    public function ignore(){
        $this->processing = true;
        $this->message[] ="Ignored and prossed to next step.";
        $this->ignorebtn = false;
        $this->ignoreIt= $this->mismatchingFields;  
        $this->importBtn=true;
        $this->updatedFile();  
        $this->processing = false;   
    }
    public function updatedFile()
    {   
        // $this->message= [];
        $this->processing = True;
        $this->validate();

        // Check if the file name matches the table name
        if ($this->file->getClientOriginalName() !== "{$this->tableName}.csv") {
            $this->message[] = 'Please Select Correct file with extantation.';
            $this->processing = false;
            return;
        }      
        $fields = DB::table('verticalsname')->where('table_name', $this->tableName)->pluck('id')->toArray();
       
        if (($handle = fopen($this->file->getRealPath(), 'r')) !== false) {
            // Read the first line (header row)
            $header = fgetcsv($handle, 0, ',');
            fclose($handle);
            // Check for mismatching fields
            $mismatchingFields = [];
            if (!empty($this->ignoreIt)) {
            $toMatchedArray = array_diff($header,$this->ignoreIt);
            }else{
                $toMatchedArray = $header;
            } 
                    
            foreach ($toMatchedArray as $field) {
                if (!in_array($field, $fields)) {
                    $mismatchingFields[] = $field;
                }

            }
          
            $this->header = $header;
            if (!empty($mismatchingFields)) {
                $this->mismatchingFields = $mismatchingFields;
                $this->message[] = implode(', ', $mismatchingFields) . " not in table.";
                if(count($toMatchedArray) !=count($fields) && !empty($this->ignoreIt)){
                    $this->message[] = "There are some fields in the file that are not in the table.";
                    $this->processing = false;
                    return;
               }
                $this->ignorebtn = true;
                $this->processing = false;
                return;
            }
            $this->importBtn=true;
            $data2 = [];
            array_push($data2, $this->header);
            $data1 = $this->parseCsv($this->file->getRealPath());
            // $data2 =$this->parseCsv($this->filex->getRealPath());
            $dbData = DB::table($this->tableName)->get()->toArray();
            foreach ($dbData as $value) {
                $valuesArray = array_values((array)$value);
                $stringValuesArray = array_map('strval', $valuesArray);
                array_push($data2, $stringValuesArray);
            }            
            $differences = $this->compareData($data2, $data1);
            $this->diff = $differences;
            if (count($differences) > 0) {
                $this->message[] = 'There are differences between the imported file and the existing data.';
            }
            $this->processing = false;
        }
    }
    private function parseCsv($filePath)
    {
        $data = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        }
        return $data;
    }
    public function render()
    {
        return view('livewire.import-table');
    }
    private function compareData($data1, $data2)
    {
        $differences = [];

        // Assuming the first column is the matching criteria
        $keyColumn = 0;

        foreach ($data1 as $row1) {
            $foundMatch = false;
            $row1Key = $row1[$keyColumn];
            foreach ($data2 as $row2) {
                $row2Key = $row2[$keyColumn];
                if ($row1Key === $row2Key) {
                    $foundMatch = true;
                    // Check for differences in other columns (skip key column)
                    for ($i = 1; $i < count($row1); $i++) {
                        if ($row1[$i] !== $row2[$i]) {
                            $differences[] = [
                                'row_1' => $row1,
                                'row_2' => $row2,
                                'difference_column' => $i, // Column index (1-based)
                                'difference_value' => [
                                    'old_value' => $row1[$i],
                                    'new_value' => $row2[$i],
                                ],
                            ];
                        }
                    }
                    break; // Exit inner loop once a match is found
                }
            }
            if (!$foundMatch) {
                $differences[] = [
                    'row_1' => $row1,
                    'row_2' => null, // Missing row in data2
                    'difference_column' => null,
                ];
            }
        }

        return $differences;
    }
    public function import()
    {    
        $this->processing = True;
        $csvData = array_map('str_getcsv', file($this->file->getRealPath()));
        $header = array_shift($csvData);
        $this->importBtn=false;
        try {
            DB::table($this->tableName)->truncate();
            $this->message[] = 'Table Truncated successfully, processing to import new data'; 
            
        } catch (\Exception $e) {
            $this->message[] = 'Error: ' . $e->getMessage();
            $this->processing = false;
            return;
        }
    
        $chunkSize = 100;
        $chunks = array_chunk($csvData, $chunkSize);
    
        try {
            foreach ($chunks as $chunk) {
                $insertData = [];
                
                foreach ($chunk as $row) {
                    $data = array_combine($header, $row);
                    $insertData[] = $data;
                }
    
                DB::table($this->tableName)->insert($insertData);
            }
    
            $this->message[] = 'New Data Imported successfully';
            $this->file = null;
            $this->diff=[];
            $this->processing = false;
            return;
        } catch (\Exception $e) {
            $this->message[] = 'Error: ' . $e->getMessage();
            $this->processing = false;
            return;
        } 
    }
    
}
