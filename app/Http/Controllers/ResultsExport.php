<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Snov;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;

class ResultsExport implements FromView
{
    protected $data;
    protected $ids = [];
    protected $company_names = [];
    protected $scrape_data = [];

    public function view(): View
    {
        ini_set('max_execution_time', 0);
        $results = Result::all();
        $snovData = Snov::all();

        return view('exports.results', [
            'results' => $results,
            'snov_data' => $snovData
        ]);
    }
}
