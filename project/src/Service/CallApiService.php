<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{

    private $client;

    public function __construct(HttpClientInterface $client){
        $this->client = $client;
    }


    public function getFranceData(): array
    {
        return $this->getApi('FranceLiveGlobalData');
    }

    public function getAllData(): array
    {
        $response = $this->getApi('AllLiveData')['allLiveFranceData'];
        foreach($response as $data){
            if(preg_match('/DEP/', $data['code']) ){
                $departement[] = $data;
            }elseif(preg_match('/REG/', $data['code'])){
                $region[] = $data;
            }elseif(preg_match('/FRA/', $data['code']) && preg_match('/ministere-sante/', $data['sourceType'])){
                $france[] = $data;
            }
        }

        $return = ['departements' => $departement, 'regions' => $region, 'france' => $france];
        return $return;
    }

    public function getDataDepartement(string $departement): array
    {
        $response = $this->getApi('AllDataByDepartement?Departement=' . $departement);
        return $response['allDataByDepartement'];
    }


    private function getApi(string $var){
        $response = $this->client->request(
            'GET',
            'https://coronavirusapi-france.now.sh/' . $var
        );

        $content = $response->toArray();

        return $content;
    }
}