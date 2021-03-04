let mymap=L.map('mapid');

const countrySelectorChange=(e)=>{
    console.log(e.target)
}

let countrySelector=$('#country')

countrySelector.on('change',(event)=>{
    let current=event.target.options[event.target.selectedIndex]
    mymap.setView([current.getAttribute('lat'),current.getAttribute('lng')],5)
    clickHandler(event,current.getAttribute('lat'),current.getAttribute('lng'))

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
   // console.log(e.target)
    //console.log(e.latlng.lat)
    clickHandler(e,e.latlng.lat,e.latlng.lng)
    //console.log('buzi')

    //e.target.openPopup()
}






const getData=async()=>{
    let data=await fetch('https://restcountries.eu/rest/v2/all')
    let jsondata=await data.json()
   
    jsondata.forEach(element => {
        
        const [lat,lang]=element.latlng
        
        locations.push([lat,lang,element.flag,element.name])
        let option=document.createElement('option')
        option.value=[element.alpha3Code]
        option.setAttribute('lat',lat)
        option.setAttribute('lng',lang)
        option.setAttribute('flag',element.flag)
        option.innerHTML=element.name
        document.querySelector('#country').appendChild(option)
       // console.log(locations)
        
    });


    locations.forEach( (location)=>{
        const [lat,lng,flag]=location
       // console.log(lat,lng)
        if(typeof lat!=="undefined"&& typeof lng!=="undefined"){
        L.marker([lat,lng],{icon:new L.Icon({iconUrl:flag,iconSize:[50,30],})})
       // .bindPopup('<img src="./loading.gif" width="100%" height="100%">')
        
        .addEventListener('click',clickHandlerWrapper)
        
        
        .addTo(mymap)
    }
        
       
      })
  
      /*var latlngs = [[[-82.96578304719736,8.225027980985985],[-83.50843726269431,8.446926581247283],[-83.71147396516908,8.656836249216866],[-83.59631303580665,8.830443223501419],[-83.63264156770784,9.051385809765321],[-83.90988562695372,9.29080272057358],[-84.30340165885636,9.487354030795714],[-84.64764421256866,9.615537421095707],[-84.71335079622777,9.908051866083852],[-84.97566036654133,10.086723130733006],[-84.91137488477024,9.795991522658923],[-85.11092342806532,9.55703969974131],[-85.33948828809227,9.83454214114866],[-85.66078650586698,9.933347479690724],[-85.79744483106285,10.134885565629034],[-85.79170874707843,10.439337266476613],[-85.65931372754666,10.75433095951172],[-85.94172543002176,10.895278428587801],[-85.7125404528073,11.088444932494824],[-85.56185197624418,11.217119248901597],[-84.90300330273895,10.952303371621896],[-84.67306901725627,11.082657172078143],[-84.35593075228104,10.999225572142905],[-84.19017859570485,10.793450018756674],[-83.89505449088595,10.726839097532446],[-83.65561174186158,10.938764146361422],[-83.40231970898296,10.395438137244652],[-83.01567664257517,9.992982082555555],[-82.54619625520348,9.566134751824677],[-82.93289099804358,9.476812038608173],[-82.92715491405916,9.074330145702916],[-82.71918311230053,8.925708726431495],[-82.86865719270477,8.807266343618522],[-82.82977067740516,8.62629547773237],[-82.91317643912421,8.42351715741907],[-82.96578304719736,8.225027980985985]]];

      var polygon = L.polygon(latlngs, {color: 'red'}).addTo(mymap);
      mymap.setView([-82.96578304719736,8.225027980985985],8)*/
  
    }
    getData()

    const clickHandler=(e,lat,lng)=>{
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
                lat,lng
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



    
    
    


    
    

    