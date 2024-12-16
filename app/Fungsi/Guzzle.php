<?php 
namespace App\Fungsi;

use GuzzleHttp\Client;
use App\Helpers;

class Guzzle
{
 
    public static function makeRequest_hriss($method, $requestUrl, $queryParams = [], $formParams = [], $hasFile = false, $isapi = false, $apikey = null)
    {

		$env_url = env('HRISS_API_URL', 'http://localhost:81/hriss/public/api').$requestUrl;

        // dd($env_url);

		$headers = Array();
		$headers["Content-Type"] = "application/json";
		$headers["token"] =  env('HRISS_API_TOKEN', 'API-INTRASS');
		
        $client = new \GuzzleHttp\Client(['headers' => $headers]);

        $bodyType = 'form_params';

        if ($hasFile) {
            $bodyType = 'multipart';
            $multipart = [];

            foreach ($formParams as $name => $contents) {
                $multipart[] = [
                    'name' => $name,
                    'contents' => $contents
                ];
            }
        }

        try {
	        $response = $client->request($method, $env_url, [
	            'query' => $queryParams,
	            $bodyType => $hasFile ? $multipart : $formParams,
	        ]);

	 		$httpcode = $response->getStatusCode();


	        $response = $response->getBody()->getContents();


        } catch(\Exception $exception){
		    $response = $exception->getResponse();
	 		$httpcode = $response->getStatusCode();
		    $response = $response->getBody()->getContents();

		}

		// $simpan = T_Apilog::create([
		// 			"method" => $method,
		// 			"url" => $env_url,
		// 			"request" => json_encode($queryParams),
		// 			"result" => $response,
		// 			"httpcode" => $httpcode,
		// 			"updated" => Helpers::generateUpdated($isapi, $apikey),

		// 		]);


		return $response;


    }

    /* Upload data dengan data post di body */
    public static function makeRequest_body($requestUrl, $body, $apikey = null)
    {

		$env_url = env('SS_API_URL', 'https://serbasepeda.icubic.xyz/api').$requestUrl;

		$headers = Array();
		$headers["Content-Type"] = "application/json";
		
		$headers["username"] =  env('SS_API_USERNAME', 'API-INTRASS');
		$headers["password"] =  env('SS_API_PASSWORD', 'yerWW23ab_8&6fsf');
		
        $client = new \GuzzleHttp\Client(['headers' => $headers]);

        try {
	        $response = $client->request("POST", $env_url, [
	            'body' => $body
	        ]);

	 		$httpcode = $response->getStatusCode();


	        $response = $response->getBody()->getContents();

        } catch(\Exception $exception){
		    $response = $exception;
	 		$httpcode = 0;
		}

		$simpan = T_Apilog::create([
					"method" => "POST",
					"url" => $env_url,
					"request" => $body,
					"result" => $response,
					"httpcode" => $httpcode,
					"updated" => Helpers::generateUpdated(True, $apikey),

				]);

		return $response;

    }


    /* Request data dengan sistem intrass 2 */
    public static function makeRequest_intrass($method, $requestUrl, $queryParams = [], $formParams = [], $hasFile = false, $isapi = false, $apikey = null)
    {

		$env_url = env('Intrass_API_URL', 'https://2.intrass.win/api').$requestUrl;

		$headers = Array();
		$headers["Content-Type"] = "application/json";
		$headers["token"] =  env('Intrass_API_token', 'API-INTRASS');
		
        $client = new \GuzzleHttp\Client(['headers' => $headers]);

        $bodyType = 'form_params';

        if ($hasFile) {
            $bodyType = 'multipart';
            $multipart = [];

            foreach ($formParams as $name => $contents) {
                $multipart[] = [
                    'name' => $name,
                    'contents' => $contents
                ];
            }
        }

        try {
	        $response = $client->request($method, $env_url, [
	            'query' => $queryParams,
	            $bodyType => $hasFile ? $multipart : $formParams,
	        ]);

	 		$httpcode = $response->getStatusCode();


	        $response = $response->getBody()->getContents();


        } catch(\Exception $exception){
		    $response = $exception->getResponse();
	 		$httpcode = $response->getStatusCode();
		    $response = $response->getBody()->getContents();

		}

		// $simpan = T_Apilog::create([
		// 			"method" => $method,
		// 			"url" => $env_url,
		// 			"request" => json_encode($queryParams),
		// 			"result" => $response,
		// 			"httpcode" => $httpcode,
		// 			"updated" => Helpers::generateUpdated($isapi, $apikey),

		// 		]);

		return $response;

    }



}
