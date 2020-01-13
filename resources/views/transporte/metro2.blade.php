@extends('.layout')
@section('title', 'Bus')
@section('content')




        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="  crossorigin=""></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


        <script src="js/Control.FullScreen.js"></script>

        <style>
            html, body {
                height: 100%;
                margin: 0;
            }
            #map {
                width: 100%;
                height: 400px;
            }
        </style>

        <div id='map'></div>

        <script>
            var map = null;
            var cities = L.layerGroup();
            //var markers = L.layerGroup();

                L.marker([39.61, -105.02]).bindPopup('This is Littleton, CO.').addTo(cities),
                L.marker([39.74, -104.99]).bindPopup('This is Denver, CO.').addTo(cities),
                L.marker([39.73, -104.8]).bindPopup('This is Aurora, CO.').addTo(cities),
                L.marker([39.77, -105.23]).bindPopup('This is Golden, CO.').addTo(cities);

            map.addLayer(cities);


            var mbAttr = 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                mbUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';

            var grayscale   = L.tileLayer(mbUrl, {id: 'mapbox.light', attribution: mbAttr}),
                streets  = L.tileLayer(mbUrl, {id: 'mapbox.streets',   attribution: mbAttr});

            var map = L.map('map', {
                center: [39.73, -104.99],
                zoom: 10,
                layers: [grayscale, cities]
            });

            var baseLayers = {
                "Grayscale": grayscale,
                "Streets": streets
            };

            var overlays = {
                "Cities": cities
            };


            load();

            ////////////////////////////
            function load(){
                $.ajax({
                    method:'GET',
                    //url: 'http://dadosabertos.rio.rj.gov.br/apiTransporte/apresentacao/rest/index.cfm/obterPosicoesDaLinha/'+document.getElementById('linha').value,
                    url: 'https://opendata.arcgis.com/datasets/7a0b22723c5a458faaae79f046163504_19.geojson',
                    data:{
                    },
                    cache: false,
                    success: function(data) {
                        console.log(data.features[0].geometry.coordinates[0]);
                        console.log(data.features[0].properties.Nome);
                        console.log(data);

                        markers.clearLayers();

                        //////////////MARKERS///////////////
                        for(let i in data.DATA){

                            L.marker([data.features[0].geometry.coordinates[0], data.features[0].geometry.coordinates[1]]).bindPopup(
                                    data.features[0].properties.Nome
                                ).addTo(cities);

                            /*L.marker([data.DATA[i][3], data.DATA[i][4]], {icon: marker}).bindPopup(
                                '<b>'+data.DATA[i][1]+'</b> ('+data.DATA[i][2]+')<br>'+
                                'Atualizado em: '+data.DATA[i][0]+'<br>'+
                                'Velocidade: '+data.DATA[i][5]+'Km/h'
                            ).addTo(markers);*/
                        }

                        cities.addTo(map);
                        //////////////MARKERS///////////////

                    },
                    error: function(xhr, status, err) {
                        console.error(status, err.toString());
                        this.setState({loading: false});
                    }
                });
            }
            //////////////////////////////
        </script>



@endsection
