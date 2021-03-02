<?php

namespace App\Http\Controllers;

use App\Models\Listname;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export(Listname $listname) 
    {
        shell_exec('sudo service php-fpm restart');
        sleep(1);
        $listnameName = str_replace([" ", ".csv", ".xlsx"], "_", $listname->name);
        $currentDateTime = date('Y-m-d_H:i:s');
        $filename = "{$listnameName}{$currentDateTime}_results.xlsx";

        return Excel::download(
            new ResultsExport($listname->id),
            $filename
        );
    }
}
