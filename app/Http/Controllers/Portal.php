<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Portal extends Controller
{
    public function index(Request $request)
    {   $tableData=null;
         @$tableName= $request->query('t');
            if($tableName){
                $tableData=DB::table('verticalsname')->where('table_name', $tableName)->get();
            }
        
        $tableNames= DB::table('verticalsname')->get()->groupBy('table_name');
        return view('portal.index',compact('tableName', 'tableNames','tableData'));
    }
}
