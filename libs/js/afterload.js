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

let $info=('#info')
let infoDiv=document.querySelector('#info')

let radioList;
let audio=document.createElement('audio')
audio.controls=true
let currencyRate;
let currentPoly;
    const countrySelectorChange=(e)=>{
       //$info.toggle() 
       typeof currentPoly=="undefined"? console.log('nothing to do'):mymap.removeLayer(currentPoly)
       $('#result').show( "bounce", { times: 2 }, 200 )
       infoDiv.innerHTML='<img src="./loading.gif" style="display:inline-block">'
        console.log(e.target.value)
        
        let countryCode=e.target.value
        $.ajax({
            url : 'libs/php/summary2.php',
            type: 'POST',
            dataType: 'json',
            data:{
                countryCode
            },success:(res)=>{
                let data=res.data
                infoDiv.innerHTML=''

        console.log(data)
        
        let h1=document.createElement('h1')
        h1.class='display-1'
        h1.innerHTML=data.name
        infoDiv.appendChild(h1)
        
        pdiv=document.createElement('div')
        let list=document.createElement('ul')
        let li=document.createElement('li')
        li=document.createElement('li')
                
        li.innerHTML=`Country Code:<br><strong>${data.countryCode}</strong>`
        list.appendChild(li)

        
        
        li=document.createElement('li')
        li.innerHTML=`Country's capital is:<br><strong> ${data.capital.name}</strong>`
        list.appendChild(li)
        li=document.createElement('li')
        li.innerHTML=`Country's currency:<br><strong> ${data.currency}</strong>`
        list.appendChild(li)
        li=document.createElement('li')
        li.innerHTML=`Country's income level:<br><strong> ${data.incomeLevel}</strong>`
        list.appendChild(li)
        li=document.createElement('li')
        li.innerHTML=`temperature now:<br><strong>${data.temperature} C</strong>`
        list.appendChild(li)
        li=document.createElement('li')
        li.innerHTML=`Country population:<br><strong> ${data.population}</strong>`
        list.appendChild(li)
        li=document.createElement('li')
        li.innerHTML=`Total COVID-19 cases:<br><strong> ${data.covidCases}</strong>`
        list.appendChild(li)

        li=document.createElement('li')
        li.innerHTML=`<a href=\"${data.wiki}\">Wikipedia</a>`
        list.appendChild(li)
        
        

        li=document.createElement('li')
        li.innerHTML=`flag:<br> <img width="50" height="30" src=${data.flag}>`
        list.appendChild(li)
        infoDiv.appendChild(list)
        let select=document.createElement('select')
        select.id='chSelector'
        radioList=data.radios
        if(radioList.length!==0){
        radioList.forEach((radio,key)=>{
            let option=document.createElement('option')
            option.value=key
            option.innerHTML=`${radio.name}`
            select.appendChild(option)
        })
        h1=document.createElement('h1')
        h1.innerHTML=`${data.name}'s radio channels`
        infoDiv.appendChild(h1)
       
        infoDiv.appendChild(select)
        infoDiv.innerHTML+='<br>'
        
        let source=document.createElement('source')
        source.src=radioList[select.value].url
        audio.appendChild(source)

        

        infoDiv.appendChild(audio)
    }
        currentPoly=L.geoJSON(data.geometry)
        currentPoly.addTo(mymap)
        mymap.flyToBounds(currentPoly)
        
        h1=document.createElement('h1')
        h1.innerHTML='Travel guides'
        infoDiv.appendChild(h1)

        let travelLinks=document.createElement('ul')
        data.travelLinks.forEach((link)=>{
            let li=document.createElement('li')
    
            let a=document.createElement('a')
            a.innerHTML=`${link.title}<br>`
            a.href=link.url
            li.appendChild(a)
            travelLinks.appendChild(li)
        })
        infoDiv.appendChild(travelLinks)
        

        currencyRate=data.exchangeRate
        let currencyDiv='<div id="currency"><input id=\"usd\" type=\"number\" step=\"0.01\" value=1><label for=\"usd\"></label></div>'
        infoDiv.innerHTML+=currencyDiv

        let usd=document.querySelector('#usd')
        let label=document.querySelector('#currency>label')
        label.innerHTML=`USD is ${Math.floor(usd.value*currencyRate*100)/100} ${data.currency}`
        console.log(label)
        



        
        infoDiv.innerHTML+='<button class=\"btn btn-danger mt-3 mb-2\"id=\"close\">Close</button>'

        document.querySelector('#close').addEventListener('click',(e)=>{
            $('#result').hide( "bounce", { times: 2 }, 200 );})

            let audioSelect=document.querySelector('#chSelector')
            audioSelect.addEventListener('change',(event)=>{
                let source=document.querySelector('source')
                console.log(source)
                let audio=document.querySelector('audio')
                source.src=radioList[event.target.value].url
                audio.load()
                audio.play()})
                usd=$('#usd')
                usd.on('change',(e)=>{
                    //   let label=document.querySelector('#currency>label')
                        label=document.querySelector('#currency>label')
                       console.log(label)
                       label.innerHTML=`USD is ${Math.floor(e.target.value*currencyRate*100)/100} ${data.currency}`
                   })
            },
            error:(err)=>{
                console.log(err)
            }

            
        })}

        
        



let countrySelector=$('#country')

countrySelector.on('change',countrySelectorChange)/*(event)=>{
    
    clickHandler(event,current.getAttribute('lat'),current.getAttribute('lng'),current.getAttribute('data-index'))
    console.log(currentPoly)
    typeof currentPoly=="undefined"? console.log('nothing to do'):mymap.removeLayer(currentPoly)

    console.log(current)
})

//countrySelector.on('change',countrySelectorChange)
//console.log(countrySelector.onchange)



let lat
let lng
let locations=[]
window.navigator.geolocation.getCurrentPosition((value)=>{
    lat=value.coords.latitude
    lng=value.coords.longitude
    

   // console.log(latitude,longitude)

    mymap.setView([lat,lng], 5)
    

    let marker1=L.marker([lat,lng],{icon:new L.Icon({iconUrl:'thunder.png',iconSize:[38,50]})})

    marker1.addTo(mymap).addEventListener('click',(e)=>{console.log(e)})

    $.ajax({ url : url=`libs/php/init.php?lat=${lat}&lng=${lng}`,
type: 'POST',
dataType: 'json',
success: (res)=>{
    let countries=res.data
    countries.forEach((country) => {
        let select=document.querySelector('#country')
        let option=document.createElement('option')
        
        
        option.value=country.code
        option.innerHTML=country.name
        option.value==='GB'?option.selected=true:option.selected=false
        select.appendChild(option)

    });
},
error:(err)=>{console.log(err)}

})


},(err)=>{
    console.log(err.message)
    lat=0
    lng=0
    mymap.setView([0,1], 2);

   

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



let url


$.ajax({ url : url=lat>0?`libs/php/init.php`:`libs/php/init.php?lat=${lat}&lng=${lng}`,
type: 'POST',
dataType: 'json',
success: (res)=>{
    let countries=res.data
    countries.forEach((country) => {
        let select=document.querySelector('#country')
        let option=document.createElement('option')
        
        
        option.value=country.code
        option.innerHTML=country.name
        option.value==='GB'?option.selected=true:option.selected=false
        select.appendChild(option)

    });
},
error:(err)=>{console.log(err)}

})

   /* const clickHandler=(e,lat,lng,dataIndex)=>{
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
                li.innerHTML=`Latest Covid-19 cases:<strong> ${typeof data.covidCases!="undefined"||typeof data.covidCases!="null"?data.covidCases?data.covidCases[data.covidCases.length-1].Cases:'N/A':'N/A'}</strong>`
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
   
        
        




    
    
    


    
    

   // L.geoJSON(feature).getBounds().getCenter()

   */