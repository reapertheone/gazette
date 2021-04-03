<?php

 

    $executionStartTime = microtime(true);

 

    $countryData = json_decode(file_get_contents("../data/countryBorders.geo.json"), true);

 

    $country = [];

    if(isset($_GET['lat'])&&isset($_GET['lng'])){
        $curl = curl_init();

    curl_setopt_array($curl, [
	CURLOPT_URL => "https://geocodeapi.p.rapidapi.com/GetNearestCities?latitude=".$_GET['lat']."&longitude=".$_GET['lng']."&range=10000",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"x-rapidapi-host: geocodeapi.p.rapidapi.com",
		"x-rapidapi-key: 807b721f6emsh4bd1bf5ebeb1093p170c01jsn59423a99c467"
	],
]);

$response = curl_exec($curl);
$decode=json_decode($response,true);




    
}else{

}

 

    foreach ($countryData['features'] as $feature) {

 

        $temp = null;

        $temp['code'] = $feature["properties"]['iso_a2'];

        $temp['name'] = $feature["properties"]['name'];
        if(isset($decode)){
        $temp['current']=$feature["properties"]['iso_a2']==$decode[0]["CountryId"];
        }
 

        array_push($country, $temp);

        

    }

 

    usort($country, function ($item1, $item2) {

 

        return $item1['name'] <=> $item2['name'];

 

    });

 

    $output['status']['code'] = "200";

    $output['status']['name'] = "ok";

    $output['status']['description'] = "success";

   

    $output['data']['countries'] = $country;

    

    header('Content-Type: application/json; charset=UTF-8');


    
$output['status']['executedIn'] = intval((microtime(true) - $executionStartTime) * 1000) . " ms";
 

   echo json_encode($output);

 

?>