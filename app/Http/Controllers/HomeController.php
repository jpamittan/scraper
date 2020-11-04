<?php

namespace App\Http\Controllers;

use App\Http\Requests\CsvImportRequest;
use App\Models\Result;
use App\Models\Snov;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    protected $data;
    protected $ids = [];
    protected $company_names = [];
    protected $scrape_data = [];

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
        session(['csv_data' => $this->data]);
        $csv_data = array_slice($data, 0, 2);

        return view('importFields', compact('csv_data'));
    }

    public function processImport(Request $request)
    {
        ini_set('max_execution_time', 0);
        $this->data = $request->session()->get('csv_data');
        $searchKeys = [];
        foreach ($request->input('fields') as $field => $value) {
            if($value == 0) {
                array_push($searchKeys, $field);
            }
        }
        foreach ($this->data as $row) {
            $searchQuery = "";
            foreach ($row as $key => $value) {
                if (in_array($key, $searchKeys)) {
                    $searchQuery .= "$value ";
                }
            }
            $searchQuery = trim($searchQuery);
            $searchQuery = str_replace("  ", " ", $searchQuery);
            $result = Result::where('company_name', $searchQuery)->first();
            if ($result) {
                $this->ids[] = $result->id;
                $this->company_names[] = $result->company_name;
                $this->scrape_data[] = json_decode($result->json_data);
            } else {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, false);
                $this->company_names[] = $searchQuery;
                $data = [
                    "q" => $searchQuery,
                    "tbm" => "lcl",
                    "device" => "desktop",
                    "location" => "United States",
                ];
                curl_setopt($ch, CURLOPT_URL, "https://app.zenserp.com/api/v2/search?" . http_build_query($data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/json",
                    "apikey: 8b317920-fe7d-11ea-8074-a74c24313909",
                ));
                $response = curl_exec($ch);
                curl_close($ch);
                $saveData = Result::create([
                    'company_name' => $searchQuery,
                    'json_data' => $response
                ]);
                $this->ids[] = $saveData->id;
                $this->scrape_data[] = json_decode($response);
            }
        }

        return view('results', [
            "ids" => $this->ids,
            "company_names" => $this->company_names,
            'maps_results' => $this->scrape_data
        ]);
    }

    public function processSnovIo(Request $request)
    {
        $snovData = [];
        $removeChars = ["https://www.", "http://www.", "https://", "http://"];
        foreach ($request->input('snovCheckList') as $record) {
            $recordArr = explode(' | ', $record);
            $resultId =  $recordArr[0];
            $companyName =  $recordArr[1];
            $domain = $recordArr[2];
            $domain = str_replace($removeChars, "", $domain);
            $domainArr = explode('/', $domain);
            $domainName = $domainArr[0];
            $snov = Snov::where('company_name', $companyName)
                ->where('results_id', $resultId)
                ->first();
            if (! $snov) {
                $snov = Snov::create([
                    'results_id' => $resultId,
                    'company_name' => $companyName,
                    'domain_name' => $domainName,
                    'snov_data' => json_encode($this->getDomainSearch($domainName))
                ]);
            }
            $snovData[] = $snov;
        }
        $results = Result::all()
            ->sortByDesc('created_at');

        return view('snov', [
            'results' => $results,
            'snov_data' => $snovData
        ]);
    }

    private function getAccessToken(): ?string
    {
        $params = [
            'grant_type'    => 'client_credentials',
            'client_id'     => '65ba79ccd6d70b13bbd1de748ee3e6c6',
            'client_secret' => 'da044702c3fd8c5f13cf426ab85b75f0'
        ];
        $options = [
            CURLOPT_URL            => 'https://api.snov.io/v1/oauth/access_token',
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true
        ];
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);
    
        return $res['access_token'];
    }

    private function getDomainSearch(string $domain): ?array
    {
        $token = $this->getAccessToken();
        $params = [
            'access_token' => $token,
            'domain'       => $domain,
            'type'         => 'all',
            'limit'        => 100,
            'lastId'       => 0
        ];
        $params = http_build_query($params);
        $options = [
            CURLOPT_URL            => 'https://api.snov.io/v2/domain-emails-with-info?'.$params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true
        ];
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $res;
    }

    public function results()
    {
        ini_set('max_execution_time', 0);
        $results = Result::all();
        $snovData = Snov::all();

        return view('snov', [
            'results' => $results,
            'snov_data' => $snovData
        ]);
    }
}
