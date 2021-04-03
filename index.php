<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
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
    <option value="none">Choose one</option>
</select></div>


<div id="general-modal" class="container-fluid text-center info-box rounded shadow mod">
<h1>General Data about the country</h1>
                <table id="general-info" class="container-fluid">
                    <thead>
                        <tr>
                            <td>Capital</td>
                            <td>Population</td>
                            <td>Currency</td>
                            <td>Flag</td>
                            <td>Wikipedia</td>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
                
</div>
<div id="audio-modal" class="container text-center info-box rounded shadow mod">
<h1>Country's radio stations</h1>
<audio controls>
    <source src="">
</audio>
<select id="audioSelector">
    <option value="none">Choose one</option>
</select>

</div>
<div id="links-modal" class="container-fluid text-center info-box rounded shadow mod">
<h1>Useful links</h1>
<div id="travel" class="left text-center">
    <h4>Travel guides</h4>
    <ul>

    </ul>
</div>
<div id="wiki" class="right text-center">
    <h4>Wiki links</h4>
    <ul>

    </ul>
</div>

</div>

<div id="financial-modal" class="container text-center info-box rounded shadow mod">
<h1>Country's Financial information</h1>
<table id="financial-data" class="container-fluid">
    <thead>
        <tr>
            <td>
                Amount
            </td>
            <td></td>
            <td>==</td>
            <td>
                Amount
            </td>
            <td>
                
            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><input id="amount" type="number" step="0.01" placeholder="12.12"></td>
            <td>USD</td>
            <td>==</td>
            <td>0</td>
            <td>VAL</td>
        </tr>
    </tbody>
</table>
<p>The country's income level:<strong><span id="iclvl">ic placeholder</span></strong></p>

</div>

<div id="covid-modal" class="container  text-light text-center info-box rounded shadow mod">
<h1>Covid numbers</h1>

<table id="covid-data" class="container-fluid">
    <tbody>
        
    </tbody>
</table>

</div>
<!--
<div id="result" style="display:block" class="container-fluid hidden text-dark">
<br>
<div class="row">
        <div class="col-2 col-xs-2 col-sm-2 col-md-1">
        <div id="general-btn" class='bg-light rounded-circle'><svg data-name="Layer 1" id="Layer_1" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:none;stroke:#078cd6;stroke-linecap:round;stroke-linejoin:round;stroke-width:2px;}.cls-2{fill:#078cd6;}</style></defs><title/><circle class="cls-1" cx="32" cy="32" r="24"/><circle class="cls-2" cx="32" cy="22" r="2"/><polyline class="cls-1" points="32 43 32 28 28 28"/></svg></div>
        <div id="media-btn" class='bg-light mt-3 rounded-circle'><svg data-name="Layer 1" id="Layer_1" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:none;stroke:#078cd6;stroke-linecap:round;stroke-linejoin:round;stroke-width:2px;}.cls-2{fill:#078cd6;}</style></defs><title/><circle class="cls-1" cx="32" cy="32" r="24"/><circle class="cls-2" cx="32" cy="22" r="2"/><polyline class="cls-1" points="32 43 32 28 28 28"/></svg></div>
        <div id="guide-btn" class='bg-light mt-3 rounded-circle'><svg data-name="Layer 1" id="Layer_1" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:none;stroke:#078cd6;stroke-linecap:round;stroke-linejoin:round;stroke-width:2px;}.cls-2{fill:#078cd6;}</style></defs><title/><circle class="cls-1" cx="32" cy="32" r="24"/><circle class="cls-2" cx="32" cy="22" r="2"/><polyline class="cls-1" points="32 43 32 28 28 28"/></svg></div>

        </div>
        <div style="display:block" class="text-center p-0  col-10 col-xs-10 col-sm-10 col-md-6 col-lg-6 col-xl-6 ">
          <div id="info"  class="active bg-light shadow">
            <img src="./loading.gif" class="rounded-circle" style="display:inline-block">
            <h1 class="display-3 text-center"></h1>
            <ul class="text-left">
                
            </ul>
            
            <div class="container text-center">
            <button class="mb-3 btn btn-danger" id="close">Close</button>
            </div>
            </div>

            <div id="media" class="inactive shadow">
            <img src="./loading.gif" style="display:inline-block">
            <h1 class="display-3 text-center"></h1>
            <ul class="text-left">
                
            </ul>
            
            <div class="container text-center">
            <button class="mb-3 btn btn-danger" id="close">Close</button>
            </div>
            </div>
        </div>
        
        
        <div class="col"></div>
    </div>


</div>



-->


<div class="loading container-fluid loading text-center text-light shadow">
    <div id="blur"></div>
    <h1 class="text-center text-dark">Loading</h1>
    <img src="loading.gif">
</div>

<div id="ui" class="container-fluid">
    
        <div id="menu" class="text-center">
            <div id="general"></div>
            <div id="audio"></div>
            <div id="links"></div>
            <div id="financial"></div>
            <div id="covid"></div>
            
            

        </div>
    
</div>


<div id="mapid">
</div>

<script src="https://code.jquery.com/ui/1.8.23/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin="anonymous"></script>
    
<script src="./libs/js/scripts.js"></script>
</body>
</html>