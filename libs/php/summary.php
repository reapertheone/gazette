<?php
$url="http://api.geonames.org/timezoneJSON?formatted=true&lat=". $_REQUEST['lat'] ."&lng=". $_REQUEST['lng'] ."&username=gergo&style=full";
//echo $url;
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL,$url);

$result=curl_exec($ch);

curl_close($ch);

$decode = json_decode($result,true);

    $output['status']['code'] = "200";
	$output['status']['name'] = "ok";
    $output['data']['countryCode']=$decode['countryCode'];
    $output['data']['countryName']=$decode['countryName'];
	$output['data']['currentTime']=$decode['time'];

    $url="http://api.geonames.org/countryInfoJSON?formatted=true&lang=en&country=".$decode['countryCode']."&username=gergo&style=full";
    
    //echo $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);
    
    $result=curl_exec($ch);
    
    curl_close($ch);

    $decode = json_decode($result,true);
    //echo json_encode($decode);

    $output['data']['currencyCode']=$decode['geonames'][0]['currencyCode'];
    $output['data']['population']=$decode['geonames'][0]['population'];
    $output['data']['capital']=$decode['geonames'][0]['capital'];

    $today = date("Y-m-d-H:i:s");  
    $url="https://api.covid19api.com/total/country/".$output['data']['countryName']."/status/confirmed?from=2020-03-01T00:00:00Z&to=".$today."";
    
    //echo $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);
    
    $result=curl_exec($ch);
    
    curl_close($ch);

    $decode= json_decode($result,true);
    //echo json_encode($decode);
    $last= end($decode);
    //echo $last;
    //print_r($decode[$last-1]);
    //echo json_encode($last);
    $output['data']['covidCases']=$last;


    $url="https://restcountries.eu/rest/v2/alpha/" . $output['data']['countryCode'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);
    
    $result=curl_exec($ch);
    
    curl_close($ch);

    $decode = json_decode($result,true);

    $output['data']['flag']=$decode['flag'];

   // echo json_encode($output);


    $url="https://api.openweathermap.org/data/2.5/weather?units=metric&q=" . $output['data']['capital'] . "&appid=c1971719726daf169fca2b88c5735c99";
    
    //echo $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);
    
    $result=curl_exec($ch);
    
    curl_close($ch);

    $decode = json_decode($result,true);

    $output['data']['temperature']=$decode['main']['temp'];


    //"http://api.worldbank.org/v2/country/".$output['data']['currencyCode']."?format=json"

    $url="http://api.worldbank.org/v2/country/".$output['data']['countryCode']."?format=json";
    
    //echo $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);
    
    $result=curl_exec($ch);
    
    curl_close($ch);

    $decode = json_decode($result,true);

    

    $output['data']['incomeLevel']=$decode[1][0]['incomeLevel']['value'];

    echo json_encode($output);
   //echo $last;


    



?>