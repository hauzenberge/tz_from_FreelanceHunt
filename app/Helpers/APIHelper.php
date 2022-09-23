<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class APIHelper
{
    public function ProjectsParce($page_number)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.freelancehunt.com/v2/projects?page[number]='.$page_number,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.env('TOKEN')
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return json_decode($response, true);
    }
}
