let mymap=L.map('mapid');

const countrySelectorChange=(e)=>{
    console.log(e.target)
}

let countrySelector=$('#country')

countrySelector.on('change',(event)=>{
    let current=event.target.options[event.target.selectedIndex]
    mymap.setView([current.getAttribute('lat'),current.getAttribute('lng')],5)
    clickHandler(event,current.getAttribute('lat'),current.getAttribute('lng'),current.getAttribute('data-index'))
    console.log(currentPoly)
    typeof currentPoly=="undefined"? console.log('nothing to do'):mymap.removeLayer(currentPoly)

    console.log(current)
})

//countrySelector.on('change',countrySelectorChange)
//console.log(countrySelector.onchange)




let locations=[]
window.navigator.geolocation.getCurrentPosition((value)=>{
    const {latitude,longitude}=value.coords

   // console.log(latitude,longitude)

    mymap.setView([latitude,longitude], 5)
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        minZoom:2,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoidHV0cmFpZ2VyZ28iLCJhIjoiY2tsaWJnank0MXU0MzJ3cDcxNzhtMTRjdSJ9.NDJrMBXj7_3uu-D1TKCRdw'
    }).addTo(mymap);

    let marker1=L.marker([latitude,longitude],{icon:new L.Icon({iconUrl:'thunder.png',iconSize:[38,50]})})

    marker1.addTo(mymap)
},(err)=>{
    console.log(err.message)

    mymap = L.map('mapid').setView([0,1], 2);

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        minZoom:2,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoidHV0cmFpZ2VyZ28iLCJhIjoiY2tsaWJnank0MXU0MzJ3cDcxNzhtMTRjdSJ9.NDJrMBXj7_3uu-D1TKCRdw'
    }).addTo(mymap);

    //let marker=L.marker([latitude,longitude],{icon:new L.Icon({iconUrl:'thunder.png',iconSize:[38,50]})})

    //
})

const clickHandlerWrapper=(e)=>{
    console.log(e.latlng.lat,e.latlng.lng)
    //console.log(e.latlng.lat)
    clickHandler(e,e.latlng.lat,e.latlng.lng)
    //console.log('buzi')

    //e.target.openPopup()
}


let currentPoly



$.ajax({ url : 'libs/php/init.php',
type: 'POST',
dataType: 'json',
success: (res)=>{
    let features=res.features
    features.forEach((feature,key) => {
        let select=document.querySelector('#country')
        let option=document.createElement('option')
        let {lat,lng}=L.geoJSON(feature).getBounds().getCenter()
        
        lat=Math.floor(lat*10000)/10000
        lng=Math.floor(lng*10000)/10000

        option.setAttribute('lat',lat)
        option.setAttribute('lng',lng)
        option.setAttribute('data-index',key)
        option.innerHTML=feature.properties.name
        select.appendChild(option)

       // L.geoJSON(feature).addEventListener('click',clickHandlerWrapper).addTo(mymap)

        
       console.log(lat,lng)
    });
},
error:(err)=>{console.log(err)}

})

    const clickHandler=(e,lat,lng,dataIndex)=>{
        //console.log('asd')
        
        let infoDiv=document.querySelector('#info')
        let $resultDiv=$('#result')
        let list=document.querySelector('#info>ul')
        let h1=document.querySelector('#info>h1')
        let $loading=$('#info>img')
            $loading.show()
            list.innerHTML=''
            h1.innerHTML=''
            //infoDiv.appendChild(loading)
            $resultDiv.show( "bounce", { times: 2 }, 200 );
        console.log('loading')
        $.ajax({
            url : 'libs/php/summary.php',
            type: 'POST',
            dataType: 'json',
            data:{
                lat,lng,dataIndex
            },success: (res)=>{
                //console.log(res)
                if(res.status.name==='ok'){
                let {data}=res
                console.log(data)
                console.log('loading done')
                $loading.hide()
                let li;
                li=document.createElement('li')
                h1.innerHTML=data.countryName
                li.innerHTML=`Country Code: <strong>${data.countryCode}</strong>`
                list.appendChild(li)

                
                
                li=document.createElement('li')
                li.innerHTML=`Country's local time:<strong> ${data.currentTime}</strong>`
                list.appendChild(li)
                li=document.createElement('li')
                li.innerHTML=`Country's currency:<strong> ${data.currencyCode}</strong>`
                list.appendChild(li)
                li=document.createElement('li')
                li.innerHTML=`Country's income level:<strong> ${data.incomeLevel}</strong>`
                list.appendChild(li)
                li=document.createElement('li')
                li.innerHTML=`temperature now: <strong>${data.temperature} C</strong>`
                list.appendChild(li)
                li=document.createElement('li')
                li.innerHTML=`Country population:<strong> ${data.population}</strong>`
                list.appendChild(li)
                li=document.createElement('li')
                li.innerHTML=`Latest Covid-19 cases:<strong> ${data.covidCases?data.covidCases.Cases:'N/A'}</strong>`
                list.appendChild(li)

                li=document.createElement('li')
                li.innerHTML=`flag: <img width="50" height="30" src=${data.flag}>`
                currentPoly=L.geoJSON(data.feature)
                currentPoly.addEventListener('click',clickHandlerWrapper).addTo(mymap)
                mymap.fitBounds(currentPoly.getBounds())
                list.appendChild(li)
                console.log(data)
                
                }else{console.log('whut?')}
            },
            error:(err)=>{
                console.error(err)
                h1.class="display-6 text-red"
                h1.innerHTML="Error happened!"
            }})
    }

   // $('#result').show( "shake", { times: 2 }, 150);
    document.querySelector('#close').addEventListener('click',(e)=>{
        $('#result').hide( "bounce", { times: 2 }, 200 );
        
        
    })



    
    
    


    
    

   // L.geoJSON(feature).getBounds().getCenter()