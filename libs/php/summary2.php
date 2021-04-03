<?php

$executionStartTime = microtime(true);

$data['countryCode']=$_REQUEST['countryCode'];

$countryData = json_decode(file_get_contents("../data/countryBorders.geo.json"), true);


    foreach ($countryData['features'] as $feature) {

      if($feature["properties"]['iso_a2']==$data['countryCode']) {
        $data['geometry']=$feature['geometry'];
        $data['name']=$feature['properties']['name'];
        break;
        } 

    }

$url="https://restcountries.eu/rest/v2/alpha/".$data['countryCode'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL,$url);
    
$result=curl_exec($ch);
    
curl_close($ch);

$decode = json_decode($result,true);

$data['population']=$decode['population'];
$data['LatLng']=$decode['latlng'];
$data['flag']=$decode['flag'];
$data['capital']['name']=$decode['capital'];
$data['currency']=$decode['currencies'][0]['code'];

$url="https://api.openweathermap.org/data/2.5/weather?units=metric&q=" . $data['capital']['name'] . "&appid=c1971719726daf169fca2b88c5735c99";
    
    //echo $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);
    
    $result=curl_exec($ch);
    
    curl_close($ch);

    $decode = json_decode($result,true);

    $data['capital']['coords']=$decode['coord'];
    $data['temperature']=$decode['main']['temp'];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://currencyapi-net.p.rapidapi.com/rates?output=JSON",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: currencyapi-net.p.rapidapi.com",
            "x-rapidapi-key: 807b721f6emsh4bd1bf5ebeb1093p170c01jsn59423a99c467"
        ],
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    $decode = json_decode($response,true);

    
        $data['exchangeRate']=$decode['rates'][$data['currency']];
    
     
    

    
    
        $endPoint = "https://en.wikipedia.org/w/api.php";
        $params = [
            "action" => "opensearch",
            "search" => $data['name'],
            "limit" => "1",
            "namespace" => "0",
            "format" => "json"
        ];
        
        $url = $endPoint . "?" . http_build_query( $params );
        
        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec( $ch );
        curl_close( $ch );
        
        $decode = json_decode( $result, true );
         
    $data['wiki']=$decode[3][0];


/*    
// radio dont forget to uncomment
    $curl = curl_init();

    curl_setopt_array($curl, [
	CURLOPT_URL => "https://radio-world-50-000-radios-stations.p.rapidapi.com/v1/radios/getTopByCountry?query=". $data['countryCode'],
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"x-rapidapi-host: radio-world-50-000-radios-stations.p.rapidapi.com",
		"x-rapidapi-key: 807b721f6emsh4bd1bf5ebeb1093p170c01jsn59423a99c467"
	],
]);

$response = curl_exec($curl);


curl_close($curl);

$decode=json_decode($response,true);
*/

$response=file_get_contents("http://91.132.145.114/json/stations/bycountrycodeexact/".$data['countryCode']);

$decode=json_decode($response,true);

$radiores=[];

foreach($decode as $radio){
    $station['name']=$radio['name'];
    $station['url']=$radio['url_resolved'];
    
    array_push($radiores,$station);
}

$data['radios']=$radiores;


$url="http://api.worldbank.org/v2/country/".$data['countryCode']."?format=json";
    
    //echo $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);
    
    $result=curl_exec($ch);
    
    curl_close($ch);

    $decode = json_decode($result,true);

    

    $data['incomeLevel']=$decode[1][0]['incomeLevel']['value'];

    
    $url="https://api.covid19api.com/summary";
    
    //echo $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);
    
    $result=curl_exec($ch);
    
    curl_close($ch);

    $decode= json_decode($result,true);
    
    $temp=[];
    if($decode!=null){
    foreach($decode['Countries'] as $record){
        if($data['countryCode']==$record['CountryCode']){
            $temp['Total']=$record['TotalConfirmed'];
            $temp['Deaths']=$record['TotalDeaths'];
            $temp['Recovered']=$record['TotalRecovered'];
            //echo $temp;
        }
        
    }
}


    $data['covidCases']=$temp;


    $sanitized=str_replace(' ','%20',$data['name']);
$url="https://contextualwebsearch-websearch-v1.p.rapidapi.com/api/Search/WebSearchAPI?q=". $sanitized ."%20travel%20guide&pageNumber=1&pageSize=5&autoCorrect=true";
    $curl = curl_init();

    



//echo $url;

curl_setopt_array($curl, [
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"x-rapidapi-host: contextualwebsearch-websearch-v1.p.rapidapi.com",
		"x-rapidapi-key: 807b721f6emsh4bd1bf5ebeb1093p170c01jsn59423a99c467"
	],
]);

$response = curl_exec($curl);


curl_close($curl);

//echo $response;
$decode= json_decode($response,true);

$searchRes=[];

foreach($decode['value'] as $link){

$tmp['title']=$link['title'];
$tmp['url']=$link['url'];
$tmp['description']=$link['description'];

array_push($searchRes,$tmp);

}

$data['travelLinks']=$searchRes;




$decode=json_decode(file_get_contents("http://api.geonames.org/countryInfoJSON?formatted=true&lang=it&country=".$data['countryCode']."&username=gergo&style=full"),true);
//print_r($decode['geonames'][0]['bbox']);
$data['bounds']['north']=$decode['geonames'][0]['north'];
$data['bounds']['east']=$decode['geonames'][0]['east'];
$data['bounds']['west']=$decode['geonames'][0]['west'];
$data['bounds']['south']=$decode['geonames'][0]['south'];
//print_r($data['bounds']);


$decode=json_decode(file_get_contents("http://api.geonames.org/earthquakesJSON?formatted=true&north=".$data['bounds']['north']."&south=".$data['bounds']['south']."&east=".$data['bounds']['east']."&west=".$data['bounds']['west']."&username=gergo&style=full"),true);

//print_r($decode['earthquakes']);

$data['earthquakes']=$decode['earthquakes'];


$decode=json_decode(file_get_contents("http://api.geonames.org/wikipediaBoundingBoxJSON?formatted=true&north=".$data['bounds']['north']."&south=".$data['bounds']['south']."&east=".$data['bounds']['east']."&west=".$data['bounds']['west']."&username=gergo&style=full"),true);

//print_r($decode);

$wiki_results=[];

//print_r($decode['geonames']);


foreach($decode['geonames'] as $result){
    $temporary['title']=$result['title'];
    $temporary['lat']=$result['lat'];
    $temporary['lng']=$result['lng'];
    
    $temporary['img']='wiki.ico';
    
    $temporary['wiki']='https:\/\/'.$result['wikipediaUrl'];
    $temporary['sum']=$result['summary'];


    array_push($wiki_results,$temporary);
    
}

$data['wikiResults']=$wiki_results;

$data['executedIn'] = intval((microtime(true) - $executionStartTime) * 1000) . " ms";
$output['data']=$data;
$output['status']='OK';
$output['statusCode']=200;
$output['success']=True;
echo json_encode($output);

?>