<?php



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

$radiores=[];

foreach($decode['radios'] as $radio){
    $station['name']=$radio['name'];
    $station['uri']=$radio['uri'];
    $station['image_url']=$radio['image_url'];
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
    
    $temp=0;
    if($decode!=null){
    foreach($decode['Countries'] as $record){
        if($data['countryCode']==$record['CountryCode']){
            $temp=$record['TotalConfirmed'];
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


$output['data']=$data;
$output['status']='OK';
$output['statusCode']=200;
$output['success']=True;
echo json_encode($output);
?>