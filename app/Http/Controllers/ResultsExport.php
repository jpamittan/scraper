<?php

namespace App\Http\Controllers;

use App\Models\{
    Result,
    Snov
};
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;

class ResultsExport implements FromView
{
    protected $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        ini_set('max_execution_time', 0);
        $results = Result::where('listnames_id', $this->id)
            ->rightJoin('snovs', 'snovs.results_id', '=', 'results.id')
            ->get();

        return view('exports.results', [
            'results' => $results
        ]);
    }
}
