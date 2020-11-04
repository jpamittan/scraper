<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export() 
    {
        return Excel::download(
            new ResultsExport, 
            date('Y-m-d_H:i:s_') . 'results.xlsx'
        );
    }
}
