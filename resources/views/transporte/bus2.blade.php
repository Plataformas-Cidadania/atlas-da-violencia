@extends('.layout')
@section('title', 'Área do aluno')
@section('content')




        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="  crossorigin=""></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


        <script src="js/Control.FullScreen.js"></script>

        <style type="text/css">
             .fullscreen-icon { background-image: url(img/icon-fullscreen.png); }
             #map:-webkit-full-screen { width: 100% !important; height: 100% !important; z-index: 99999; }
             #map:-ms-fullscreen { width: 100% !important; height: 100% !important; z-index: 99999; }
             #map:full-screen { width: 100% !important; height: 100% !important; z-index: 99999; }
             #map:fullscreen { width: 100% !important; height: 100% !important; z-index: 99999; }
             .leaflet-pseudo-fullscreen { position: fixed !important; width: 100% !important; height: 100% !important; top: 0px !important; left: 0px !important; z-index: 99999; }
        </style>






        <script language="javascript">
            var map = null;
            var markers = L.layerGroup();

            function init() {

                map = new L.Map('map', {
                    fullscreenControl: true,
                    fullscreenControlOptions: { // optional
                        title:"Show me the fullscreen !",
                        titleCancel:"Exit fullscreen mode"
                    }
                });

                L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
                    maxZoom: 18
                }).addTo(map);
                map.attributionControl.setPrefix(''); // Don't show the 'Powered by Leaflet' text.

                //Define an array of Latlng objects (points along the line)
                var polylinePoints = [
                    new L.LatLng(-22.885923, -43.115279),
                    new L.LatLng(-22.885638, -43.115334),
                    new L.LatLng(-22.883899, -43.115719),
                    new L.LatLng(-22.878828, -43.114530),
                    new L.LatLng(-22.872676, -43.205319),
                    new L.LatLng(-22.899125, -43.207882),
                    new L.LatLng(-22.893682, -43.190791),
                    new L.LatLng(-22.897019, -43.179726),
                    new L.LatLng(-22.904090, -43.174590),
                    new L.LatLng(-22.906861, -43.172895),
                ];

                var polylineOptions = {
                    color: 'blue',
                    weight: 6,
                    opacity: 0.9
                };

                var polyline = new L.Polyline(polylinePoints, polylineOptions);


                map.addLayer(polyline);

                // zoom the map to the polyline
                map.fitBounds(polyline.getBounds());
            }

            function load(){
                $.ajax({
                    method:'GET',
                    url: 'http://dadosabertos.rio.rj.gov.br/apiTransporte/apresentacao/rest/index.cfm/obterPosicoesDaLinha/'+document.getElementById('linha').value,
                    data:{
                    },
                    cache: false,
                    success: function(data) {
                        console.log(data);

                        markers.clearLayers();

                        ///////////////ICONE/////////////////
                        var LeafIcon = L.Icon.extend({
                            options: {
                                iconSize:     [40, 44],
                            }
                        });
                        var markerOn = new LeafIcon({iconUrl: 'img/marker.png'});
                        var markerOff = new LeafIcon({iconUrl: 'img/marker-off.png'});
                        ///////////////ICONE/////////////////

                        //////////////MARKERS///////////////
                        for(let i in data.DATA){

                            let marker = markerOn;
                            if(data.DATA[i][5]===0){
                                marker = markerOff;
                            }

                            L.marker([data.DATA[i][3], data.DATA[i][4]], {icon: marker}).bindPopup(
                                '<b>'+data.DATA[i][1]+'</b> ('+data.DATA[i][2]+')<br>'+
                                'Atualizado em: '+data.DATA[i][0]+'<br>'+
                                'Velocidade: '+data.DATA[i][5]+'Km/h'
                            ).addTo(markers);
                        }
                        markers.addTo(map);
                        //////////////MARKERS///////////////

                        totalOnibus =  data.DATA.length;

                        document.getElementById('totalOnibus').innerHTML = totalOnibus;

                        //loadRoute();
                    },
                    error: function(xhr, status, err) {
                        console.error(status, err.toString());
                        this.setState({loading: false});
                    }
                });
            }
            function primeiro(map){
                load(map);
                setInterval(function(){
                    load(map);
                }, 60000);
            }


        </script>

    <body onLoad="javascript:init(map);">
    <div id="map" style="height: 600px"></div>






    <div class="container">
        <div class="row">
            <div class="col-12"><br><br></div>

            <div class="col-md-1"><img src="img/marker.png" alt="" width="40"></div>
            <div class="col-md-10">Ônibus em movimento</div>
            <div class="col-md-1">20</div>
            <div class="col-md-12"><hr></div>

            <div class="col-md-1"><img src="img/marker-pause.png" alt="" width="40"></div>
            <div class="col-md-10" style="vertical-align: middle;">Ônibus parado a 10 minutos</div>
            <div class="col-md-1">20</div>
            <div class="col-md-12"><hr></div>

            <div class="col-md-1"><img src="img/marker-off.png" alt="" width="40"></div>
            <div class="col-md-10" style="vertical-align: middle;">Ônibus parado a mais de 10 minutos</div>
            <div class="col-md-1">20</div>
            <div class="col-md-12"><hr></div>

            <div class="col-md-11" style="vertical-align: middle;">Total</div>
            <div class="col-md-1" id="totalOnibus">60</div>
        </div>
    </div>


    <div class="container">

        <div class="row" style="position: absolute; top: -20px; z-index: 999999999999999999999999999;">
            <div class="col-sm-12">
                <br>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Encontre a linha desejada</h5>
                        <!--<p class="card-text">Com suporte a texto embaixo, que funciona como uma introdução a um conteúdo adicional.</p>-->

                        <div class="form-row align-items-center">
                            <div class="col-auto">
                                <input type="text" class="form-control" id="linha" placeholder="linha" />
                            </div>
                            <!--<div class="col-auto">
                               <input type="text" class="form-control" id="ordem" placeholder="código (opcional)"/>
                            </div>-->
                            <div class="col-auto">
                                <button class="btn btn-primary" onclick="primeiro(map)">Pesquisar</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
    </body>


@endsection
