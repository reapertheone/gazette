<?php
$result=file_get_contents('../data/countryBorders.geo.json');

$decode=json_decode($result,true);

// $decode['features'][0]['properties']['iso_a2'];



//echo json_encode($decode);

echo json_encode($decode)

?>