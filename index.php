<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link       rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
                integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
                crossorigin=""/>
                <script src="./libs/js/jquery-2.2.3.min.js"></script>
    <script src="./libs/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin="anonymous"></script>
    

    <link rel="stylesheet" href="./libs/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="./libs/css/own.css"/>
    <script src="./libs/js/preload.js"></script>
    

    <title>Codename: Gazetteer</title>
</head>
<body>

<div id="selector"><select class="shadow" id="country">
    <option  selected value="none">Choose one</option>
</select></div>

<div id="result" style="display:none" class="container-fluid hidden text-dark">
<div class="row">
        <div class="col"></div>
        <div id="info" class="text-center col-8 col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xl-6 bg-light shadow">
            <img src="./loading.gif" style="display:none">
            <h1 class="display-3 text-center">United Kingdom</h1>
            <ul class="text-left">
                <li>Currency:GBP</li>
                <li>Exchange rate to USD:<strong>£0.68</strong></li>
                <li>Neighbours:<strong>Ireland</strong>,<strong>France</strong></li>
            </ul>
            <div class="container text-center">
            <button class="mb-3 btn btn-danger" id="close">Close</button>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>






<div id="mapid">
</div>

<script src="https://code.jquery.com/ui/1.8.23/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin="anonymous"></script>
    
<script src="./libs/js/afterload.js"></script>
</body>
</html>