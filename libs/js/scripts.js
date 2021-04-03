let mymap=L.map('mapid');
L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        minZoom:2,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoidHV0cmFpZ2VyZ28iLCJhIjoiY2tsaWJnank0MXU0MzJ3cDcxNzhtMTRjdSJ9.NDJrMBXj7_3uu-D1TKCRdw'
    }).addTo(mymap);

mymap.setView([0,0],1)

let url
let lat
let lng
let loading=false
let currentPoly
let countrySelector=document.querySelector('#country')
$('.loading').hide()

window.navigator.geolocation.getCurrentPosition((value)=>{
    lat=value.coords.latitude
    lng=value.coords.longitude
    

   // console.log(latitude,longitude)

    mymap.setView([lat,lng], 5)
    


    let markerOwn=L.marker([lat,lng],{icon:new L.Icon({iconUrl:'thunder.png',iconSize:[38,50]})})

    markerOwn.addTo(mymap).addEventListener('click',(e)=>{console.log(e)})

    $.ajax({ url : `libs/php/init.php?lat=${lat}&lng=${lng}`,
type: 'POST',
dataType: 'json',
success: (res)=>{
    let countries=res.data.countries
    countries.forEach((country) => {
        let select=document.querySelector('#country')
        let option=document.createElement('option')
        
        
        option.value=country.code
        option.innerHTML=country.name
        option.selected=country.current
        select.appendChild(option)

    })
},
error:(err)=>{console.log(err.message)}

}).done(()=>{
    getAllData(countrySelector.value)
})


},(err)=>{
    $.ajax({ url : `libs/php/init.php`,
        type: 'POST',
        dataType: 'json',
    success: (res)=>{
        let countries=res.data.countries
        countries.forEach((country) => {
        let select=document.querySelector('#country')
        let option=document.createElement('option')
        
        
        option.value=country.code
        option.innerHTML=country.name
        
        select.appendChild(option)

    })

},
    error:(err)=>{console.log(err)}

})
    console.log(err.message)
    lat=0
    lng=0
    mymap.setView([0,0], 2);

})

let btns=document.querySelectorAll('#menu>div')
console.log(btns)
btns.forEach((btn)=>{
btn.addEventListener('click',(e)=>{
    let modals=$('.mod')
    let modalName='#'+e.target.id+'-modal'
    if($(modalName).is(':visible')){
        modals.hide()
    }else{
        modals.hide()
        $(modalName).show()  
    }
    
    
   
    
    
})
})

let pubData;
function getAllData(countryCode){
    $.ajax({
        url: './libs/php/summary2.php',
        method:'POST',
        dataType:'json',
        data:{
            countryCode
        },
        success:(res)=>{
            if(res.statusCode===200){
                pubData=res.data
            }
            console.log('Done')
            
        },
        error:(err)=>{
            console.log(err)
        }
    }).done((res)=>{
        console.log(res.data)
        updateMap(res.data)
        insertData(res.data)
        $('.loading').hide()
    })
}






let modals=$('.mod').hide()





let currencyRate

const insertData=(data)=>{
    let audioSelect=document.querySelector('#audioSelector')
    audioSelect.innerHTML=`<option value=\"none\">Choose one</option>`
    data.radios.forEach((e)=>{
        let option=document.createElement('option')
        
        option.innerHTML=e.name
        option.value=e.url
        audioSelect.appendChild(option)
    })


    let tbody=document.querySelector('#general-info>tbody')
    tbody.innerHTML=`<tr><td>${data.capital.name}</td><td>${data.population}</td><td>${data.currency}</td><td><img src=${data.flag} width=\"50\" height=\"30\"></td><td><a href=${data.wiki} target=\"_blank\">Wiki</a></td></tr>`
    
    let linklist=document.querySelector('#travel>ul')
    linklist.innerHTML=""
    data.travelLinks.forEach((link)=>{
        
        let li=document.createElement('li')
        let a=document.createElement('a')
        a.href=link.url
        let titleArr=link.title.split(' ')
        let title=titleArr.length>=2?titleArr[0]+" "+titleArr[1]+'...':titleArr[0]
        a.innerHTML=title
        a.target="_blank"
        li.appendChild(a)
        linklist.appendChild(li)
    })

    linklist=document.querySelector('#wiki>ul')
    data.wikiResults.forEach((link)=>{
        
        let li=document.createElement('li')
        let a=document.createElement('a')
        a.href=link.wiki
        let titleArr=link.title.split(' ')
        let title=titleArr.length>=2?titleArr[0]+" "+titleArr[1]+'...':titleArr[0]
        a.innerHTML=title
        a.target="_blank"
        li.appendChild(a)
        linklist.appendChild(li)
    })
    currencyRate=data.exchangeRate
    let tds=document.querySelectorAll('#financial-data>tbody>tr>td')
    

    $('#amount').on('input',(e)=>{
        tds[3].innerHTML=e.target.value*currencyRate
    })
    tds[4].innerHTML=data.currency
    document.querySelector('#iclvl').innerHTML=data.incomeLevel
    let table=document.querySelector('#covid-data>tbody')
    table.innerHTML=""
    for (const [key, value] of Object.entries(data.covidCases)) {
            let tr=document.createElement('tr')
            let tdkey=document.createElement('td')
            let tdvalue=document.createElement('td')

            tdkey.innerHTML=key
            tdvalue.innerHTML=value
            tr.appendChild(tdkey)
            tr.appendChild(tdvalue)
            table.appendChild(tr)
      }
    
}


let wikiMarkers
let eqMarkers
const updateMap=(data)=>{

    if(currentPoly){
        mymap.removeLayer(currentPoly)
        wikiMarkers.forEach((marker)=>{
            mymap.removeLayer(marker)
        })
        eqMarkers.forEach((marker)=>{
            mymap.removeLayer(marker)
        })
    }

    currentPoly=L.geoJSON(data.geometry).addTo(mymap)
    wikiMarkers=data.wikiResults.map((result)=>{
        let popupContent=`<a href=${result.wiki} target=\"_blank\">${result.title}</a>`
       return L.marker([result.lat,result.lng],{icon:new L.Icon({iconUrl:result.img,iconSize:[50,50]})}).addTo(mymap).bindPopup(popupContent);
    })

    eqMarkers=data.earthquakes.map((eq)=>{
        let popupContent=`<h4>${eq.datetime}</h4>`
            popupContent+=`<h6 class=\"text-center\">${eq.magnitude} Magnitude</h6>`
        return L.marker([eq.lat,eq.lng],{icon:new L.Icon({iconUrl:'eq.png',iconSize:[50,50]})}).addTo(mymap).bindPopup(popupContent);
    })
    mymap.flyToBounds(currentPoly)
}




$('#audioSelector').on('change',(e)=>{
    
    let audio=document.querySelector('audio')
    if(e.target.value!='none'){
    audio.src=e.target.value
    audio.load()
    audio.play()
    }else{
        audio.pause()
        console.log('nothing to play')
    }

    
})


countrySelector.addEventListener('change',(e)=>{
    getAllData(e.target.value)
    $('.loading').show()
})