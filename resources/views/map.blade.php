@extends('layout')
@section('title', 'Mapa')
@section('content')
    <style>
        #mapid { height: 600px; }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
    <div id="mapid"></div>
    <button onclick="getData()">Carregar</button>
    <script>
        var map;


        var mymap = L.map('mapid').setView([-16, -53], 4);
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiYnJwYXNzb3MiLCJhIjoiY2l4N3l0bXF0MDFiczJ6cnNwODN3cHJidiJ9.qnfh8Jfn_be6gpo774j_nQ', {
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox.streets'
        }).addTo(mymap);

        function mapData(){
            //remove existing map layers
            map.eachLayer(function(layer){
                //if not the tile layer
                if (typeof layer._url === "undefined"){
                    map.removeLayer(layer);
                }
            });

            //create geojson container object
            var geojson = {
                "type": "FeatureCollection",
                "features": []
            };

            //areas
            mapArea('');

            //split data into features
            var dataArray = data.split(", ;");
            dataArray.pop();

            //console.log(dataArray);

            //build geojson features
            dataArray.forEach(function(d){
                d = d.split(", "); //split the data up into individual attribute values and the geometry

                //feature object container
                var feature = {
                    "type": "Feature",
                    "properties": {}, //properties object container
                    "geometry": JSON.parse(d[fields.length]) //parse geometry
                };

                for (var i=0; i<fields.length; i++){
                    feature.properties[fields[i]] = d[i];
                };

                //add feature names to autocomplete list
                if ($.inArray(feature.properties.featname, autocomplete) == -1){
                    autocomplete.push(feature.properties.featname);
                };

                geojson.features.push(feature);
            });

            //console.log(geojson);

            //activate autocomplete on featname input
            $("input[name=featname]").autocomplete({
                source: autocomplete
            });

            var mapDataLayer = L.geoJson(geojson, {
                pointToLayer: function (feature, latlng) {
                    var markerStyle = {
                        fillColor: "#CC9900",
                        color: "#FFF",
                        fillOpacity: 0.5,
                        opacity: 0.8,
                        weight: 1,
                        radius: 8
                    };

                    return L.circleMarker(latlng, markerStyle);
                },
                onEachFeature: function (feature, layer) {
                    var html = "";
                    for (prop in feature.properties){
                        html += prop+": "+feature.properties[prop]+"<br>";
                    };
                    layer.bindPopup(html);
                }
            }).addTo(map);
        }

        function getData(){
            $.ajax("get-data", {
                data: {
                    table: "ed_territorios_uf"
                },
                success: function(data){
                    console.log(data);
                    mapData(data);
                }
            })
        }


    </script>
@endsection