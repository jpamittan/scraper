<?php

namespace App\Http\Controllers;

use DB;
use App\Http\Requests\CsvImportRequest;
use App\Jobs\ScrapeQueue;
use App\Models\{
    Listname,
    Result,
    Snov
};
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function parseImport(CsvImportRequest $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $this->data = $data;
        if ($request->has('header')) {
            array_shift($this->data);
        }
        session([
            'csv_file_name' => $request->file('csv_file')->getClientOriginalName(),
            'csv_data' => $this->data
        ]);
        $csv_data = array_slice($data, 0, 2);

        return view('importFields', compact('csv_data'));
    }

    public function processImport(Request $request)
    {
        $listname = Listname::create([
            'name' => $request->session()->get('csv_file_name'),
            'row_count' => count($request->session()->get('csv_data')),
            'status' => 'progress'
        ]);
        $searchKeys = [];
        foreach ($request->fields as $field => $value) {
            array_push($searchKeys, $field);
        }
        $this->dispatch(new ScrapeQueue(
            $request->session()->get('csv_data'),
            $listname,
            $searchKeys
        ));

        return redirect()->route('home.download');
    }

    public function download()
    {
        $inProgress = Listname::where('status', 'progress')
            ->get();
        foreach ($inProgress as $progress) {
            $count = Result::where('listnames_id', $progress->id)
                ->count();
            if ($count == $progress->row_count) {
                $list = Listname::find($progress->id);
                $list->status = 'done';
                $list->save();
            }
        }
        $listnames = Listname::leftJoin('results', 'results.listnames_id', '=', 'listnames.id')
            ->select([
                'listnames.*',
                DB::raw('COUNT(results.listnames_id) as done')
            ])
            ->groupBy('listnames.id')
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        return view('download', [
            'listnames' => $listnames,
        ]);
    }

    public function delete(Listname $listname)
    {
        $listname->delete();

        return redirect()->route('home.download');
    }

    public function results()
    {
        $results = Result::all();
        $snovData = Snov::all();

        return view('snov', [
            'results' => $results,
            'snov_data' => $snovData
        ]);
    }
}
