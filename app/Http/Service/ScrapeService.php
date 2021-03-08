<?php

namespace App\Http\Service;

use App\Models\{
    Listname,
    Result,
    Snov
};

class ScrapeService
{
    public function google(array $data, object $listname, array $searchKeys)
    {
        foreach ($data as $row) {
            $response = null;
            $searchQuery = "";
            foreach ($row as $key => $value) {
                if (in_array($key, $searchKeys)) {
                    $searchQuery .= "$value ";
                }
            }
            $searchQuery = trim($searchQuery);
            $searchQuery = str_replace("  ", " ", $searchQuery);
            $result = Result::where('company_name', $searchQuery)
                ->latest()
                ->first();
            if (
                $result &&
                $result->json_data != '{"error":"Not enough requests."}'
            ) {
                $response = $result->json_data;
            } else {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, false);
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
            }
            $saveData = Result::create([
                'listnames_id' => $listname->id,
                'company_name' => $searchQuery,
                'json_data' => $response
            ]);
            $this->snovio($saveData);
        }
    }

    private function snovio(object $result)
    {
        $removeChars = ["https://www.", "http://www.", "https://", "http://"];
        $records = json_decode($result->json_data);
        if (isset($records->maps_results)) {
            foreach ($records->maps_results as $record) {
                if (isset($record->url)) {
                    if (str_contains($record->url, 'http')) {
                        $domain = str_replace($removeChars, "", $record->url);
                        $domainArr = explode('/', $domain);
                        $domainName = $domainArr[0];
                        $snovExist = Snov::where('domain_name', $domainName)
                            ->latest()
                            ->first();
                        if (
                            $snovExist &&
                            $snovExist->snov_data != '{"success":false,"message":"Sorry, you ran out of credits, please order more credits"}'
                        ) {
                            $snov = Snov::create([
                                'results_id' => $result->id,
                                'company_name' => $result->company_name,
                                'domain_name' => $domainName,
                                'snov_data' => $snovExist->snov_data,
                                'payload' => "Record exists"
                            ]);
                            break;
                        } else {
                            $snovResponse = $this->getDomainSearch($domainName);
                            $payload = $snovResponse['payload'];
                            $snovData = $snovResponse['response'];
                            if ($snovData != '{"success":false,"message":"Sorry, you ran out of credits, please order more credits"}') {
                                $snov = Snov::create([
                                    'results_id' => $result->id,
                                    'company_name' => $result->company_name,
                                    'domain_name' => $domainName,
                                    'snov_data' => $snovData,
                                    'payload' => $payload
                                ]);
                            }
                            break;
                        }
                    }
                }
            }
        }
    }

    private function getDomainSearch(string $domain): ?array
    {
        $token = $this->getAccessToken();
        $params = [
            'access_token' => $token,
            'domain'       => $domain,
            'type'         => 'all',
            'limit'        => 20,
            'lastId'       => 0
        ];
        $params = http_build_query($params);
        $options = [
            CURLOPT_URL            => 'https://api.snov.io/v2/domain-emails-with-info?' . $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true
        ];
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return [
            'payload' => 'https://api.snov.io/v2/domain-emails-with-info?' . $params,
            'response' => json_encode($res)
        ];
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
}