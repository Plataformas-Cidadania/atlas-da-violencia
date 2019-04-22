class Map extends React.Component{
    constructor(props){
        super(props);
        //console.log(props);
        this.state = {
            firstTimeLoad: true,
            regioes: [],
            ufs: [],
            data: [],
            dataCalor: [],
            types: null,
            typesAccident: null,
            genders: null,
            //year:props.year,
            //month:props.month,
            start: props.start,
            end: props.end,
            filters: props.filters,
            pais: 203, //utilizado para o mapa de calor
            tipoTerritorioSelecionado: props.tipoTerritorioSelecionado,
            codigoTerritorioSelecionado: props.codigoTerritorioSelecionado,
            tipoTerritorioAgrupamento: props.tipoTerritorioAgrupamento,
            tipo: ['Não Informado', 'Automóvel', 'Motocicleta', 'Pedestre', 'Ônibus', 'Caminhao', 'Bicicleta', 'Outros'],
            tipoIcone: props.typeIcons,
            sexo: ['Não Informado', 'Maculino', 'Feminino'],
            faixaEtaria: ['Não Informado', '0-12'],
            tipoAcidente: ['Não Informado', 'Atropelamento'],
            turno: ['Não Informado', 'Manhã', 'Tarde', 'Noite'],

            mapElements: {
                map:null,
            },
            tileLayerMap:1, //1 - Básico, 2 - Contraste, 3 - Satélite
            tilesLayers: {
                basic: null,
                contrast: null,
                satellite: null,
            },
            zoom: {
                1:4,//territorio 1 usa o zoom 4
                2:5,
                3:7,
                4:8,
            },
            classMarker: {
                1:'marker',
                2:'marker',
                3:'marker2',
                4:'marker2',
            },
            loadData:{
                1: function() {
                    //console.log('aaa');
                    this.loadDataTotalPorTerritorio();
                }.bind(this),
                2: function() {
                    //console.log('bbb');
                    this.loadDataTotalPorTerritorio();
                }.bind(this),
                3: function() {
                    //console.log('ccc');
                    this.loadDataPontosPorTerritorio();
                }.bind(this),
                4: function() {
                    //console.log('ddd');
                    this.loadDataPontosPorTerritorio();
                }.bind(this),
            }
        };

        this.loadMap = this.loadMap.bind(this);
        this.loadData = this.loadData.bind(this);
        this.loadDataTotalPorTerritorio = this.loadDataTotalPorTerritorio.bind(this);
        this.loadDataPontosPorTerritorio = this.loadDataPontosPorTerritorio.bind(this);
        this.populateMap = this.populateMap.bind(this);
        this.populateMapCluster = this.populateMapCluster.bind(this);
        this.heatMap = this.heatMap.bind(this);
        this.removeHeatMap = this.removeHeatMap.bind(this);
        this.addHeatMap = this.addHeatMap.bind(this);
        this.changeTileLayer = this.changeTileLayer.bind(this);
        this.removeMarkersGroup = this.removeMarkersGroup.bind(this);
        this.addMarkersGroup = this.addMarkersGroup.bind(this);

    }


    componentDidMount(){
        //console.log(this.props.id);
        //this.loadData();
        //this.mountPer();
        this.loadFirstMap();
        //this.loadDataTotalPorTerritorio();
        //this.loadDataPontosPorPais();
    }

    componentWillReceiveProps(props){
        if(props.filter==1 || (this.state.firstTimeLoad===true && props.start!=null && props.end != null)){
            this.setState({
                firstTimeLoad: false,
                types: props.types,
                typesAccident: props.typesAccident,
                genders: props.genders,
                tipoTerritorioSelecionado: props.tipoTerritorioAgrupamento,
                codigoTerritorioSelecionado: props.codigoTerritorioSelecionado,
                tipoTerritorioAgrupamento: props.tipoTerritorioAgrupamento,
                start: props.start,
                end: props.end,
                filters: props.filters
            }, function(){
                //this.mountPer();
                //console.log(this.state.start, this.state.end);
                this.loadMap();
                this.loadDataTotalPorTerritorio();
            });
        }
    }


    loadFirstMap(){

        let mapElements = this.state.mapElements;

        let basic = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        });

        let contrast = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/toner/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        });

        var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 17,
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        });

        let tilesLayers = this.state.tilesLayers;
        tilesLayers.basic = basic;
        tilesLayers.contrast = contrast;
        tilesLayers.satellite = satellite;
        this.setState({tilesLayers: tilesLayers});

        var latlng = L.latLng(-13.70, -55.65);

        let tile = null;
        if(this.state.tileLayerMap==1){
            tile = basic;
        }
        if(this.state.tileLayerMap==2){
            tile = contrast;
        }
        if(this.state.tileLayerMap==3){
            tile = satellite;
        }

        mapElements.map = L.map('map', {
            center: latlng,
            zoom: 4,
            layers: [tile],
            fullscreenControl: true,
            fullscreenControlOptions: { // optional
                title:"Show me the fullscreen !",
                titleCancel:"Exit fullscreen mode"
            }
        });

        let thisReact = this;

        //////////////////DIV CONTAINER DOS CONTROLERS///////////////////////////////////
        //pega o div externo ao mapa
        let controlsMap = document.getElementById('controls-map');

        mapElements.controlContainer = L.Control.extend({
            options:{
                position: 'topright',
            },
            onAdd: function(){
                return L.DomUtil.get('controls-map');
            }
        }).bind(thisReact);
        mapElements.map.addControl(new mapElements.controlContainer());
        ///////////////////////////////////////////////////////////////////////////////

        ////////////////CONTROLERS DOS MAPAS////////////////////////////////////////////
        /*var mapas = {
            "Mapa": tiles,
            "<img src='img/leaflet/pedestre.png' /><span>Contraste</span>": contraste,
            "Satélite": satelite,
        };

        mapElements.controlLayersMaps = L.control.layers(mapas, null, {collapsed: false});
        mapElements.controlLayersMaps.addTo(mapElements.map);

        //pega o div do controle
        let divControlLayersMaps = mapElements.controlLayersMaps.getContainer();
        //coloca o div do controle no div externo
        controlsMap.appendChild(divControlLayersMaps);*/


        mapElements.controlBasicMap = L.Control.extend({
            options:{
                position: 'topright',
            },
            onAdd: function(){
                let container = L.DomUtil.create('div');
                container.onclick = function(){
                    thisReact.setState({tileLayerMap: 1});
                    thisReact.changeTileLayer(thisReact.state.tilesLayers.basic);
                    container.className = 'control-data-types check-control-data-types leaflet-control';
                };

                container.id = 'controlBasic';
                container.className = 'control-data-types leaflet-control';
                if(thisReact.state.tileLayerMap==1){
                    container.className = 'control-data-types check-control-data-types leaflet-control';
                }

                container.style.cursor = 'pointer';
                container.innerHTML = '<img src="img/leaflet/controls/basic.png"  title="Mapa Básico">';

                return container;
            }
        }).bind(thisReact);
        let controlBasicMapObj = new mapElements.controlBasicMap();
        mapElements.map.addControl(controlBasicMapObj);
        //pega o div do controle
        let divControlBasicMap = controlBasicMapObj.getContainer();
        //coloca o div do controle no div externo
        controlsMap.appendChild(divControlBasicMap);

        mapElements.controlContrastMap = L.Control.extend({
            options:{
                position: 'topright',
                check: false,
            },
            onAdd: function(){
                let container = L.DomUtil.create('div');
                container.onclick = function(){
                    thisReact.setState({tileLayerMap: 2});
                    thisReact.changeTileLayer(thisReact.state.tilesLayers.contrast);
                    container.className = 'control-data-types check-control-data-types leaflet-control';
                };

                container.id = 'controlContrast';
                container.className = 'control-data-types leaflet-control';
                if(thisReact.state.tileLayerMap==2){
                    container.className = 'control-data-types check-control-data-types leaflet-control';
                }

                container.style.cursor = 'pointer';
                container.innerHTML = '<img src="img/leaflet/controls/contrast.png"  title="Contraste">';

                return container;
            }
        });
        let controlContrastMapObj = new mapElements.controlContrastMap();
        mapElements.map.addControl(controlContrastMapObj);
        //pega o div do controle
        let divControlContrastMap = controlContrastMapObj.getContainer();
        //coloca o div do controle no div externo
        controlsMap.appendChild(divControlContrastMap);

        mapElements.controlSatelliteMap = L.Control.extend({
            options:{
                position: 'topright',
                check: false,
            },
            onAdd: function(){
                let container = L.DomUtil.create('div');
                container.onclick = function(){
                    thisReact.setState({tileLayerMap: 3});
                    thisReact.changeTileLayer(thisReact.state.tilesLayers.satellite);
                    container.className = 'control-data-types check-control-data-types leaflet-control';
                };

                container.id = 'controlSatellite';
                container.className = 'control-data-types leaflet-control';
                if(thisReact.state.tileLayerMap==3){
                    container.className = 'control-data-types check-control-data-types leaflet-control';
                }

                container.style.cursor = 'pointer';
                /*container.style.borderBottom = 'solid 1px #ccc';*/
                container.innerHTML = '<img src="img/leaflet/controls/satellite.png" title="Satélite">';

                return container;
            }
        });
        let controlSatelliteMapObj = new mapElements.controlSatelliteMap();
        mapElements.map.addControl(controlSatelliteMapObj);
        //pega o div do controle
        let divControlSatelliteMap = controlSatelliteMapObj.getContainer();
        //coloca o div do controle no div externo
        controlsMap.appendChild(divControlSatelliteMap);

        ///////////////FIM CONTROLERS DOS MAPAS/////////////////////////////////////////

        //////////////////////////SEPARADOR/////////////////////////////////////////////
        mapElements.controlSeparator = L.Control.extend({
            options:{
                position: 'topright',
            },
            onAdd: function(){
                let container = L.DomUtil.create('div');
                container.style.marginRight = '0';
                container.style.borderBottom = 'solid 1px #e8e8e8';
                container.style.width = '100%';
                container.innerHTML = ' ';
                return container;
            }
        }).bind(thisReact);
        let controlSeparatorObj = new mapElements.controlSeparator();
        mapElements.map.addControl(controlSeparatorObj);
        //pega o div do controle
        let divControlSeparator = controlSeparatorObj.getContainer();
        //coloca o div do controle no div externo
        controlsMap.appendChild(divControlSeparator);
        ////////////////////////////FIM//////////////////////////////////////////////////

        ////////////////CONTROLERS DOS LAYERS////////////////////////////////////////////
        //Clusters/Markers
        mapElements.controlClusterMap = L.Control.extend({
            options:{
                position: 'topright',
                check: true,
            },
            onAdd: function(){
                let options = this.options;
                //console.log(options.check);
                let container = L.DomUtil.create('div');
                container.onclick = function(){
                    //console.log(options.check);
                    options.check = !options.check;
                    container.className = 'control-data-types leaflet-control';
                    //container.innerHTML = '<img width="24px" height="32px" src="img/leaflet/marker-off.png">';
                    thisReact.removeMarkersGroup();
                    if(options.check){
                        thisReact.addMarkersGroup();
                        container.className = 'control-data-types check-control-data-types leaflet-control';
                        //container.innerHTML = '<img width="24px" height="32px" src="img/leaflet/marker-on.png">';
                    }
                }.bind(options, thisReact);

                container.className = 'control-data-types leaflet-control';
                if(options.check){
                    container.className = 'control-data-types check-control-data-types leaflet-control';
                }

                container.style.cursor = 'pointer';
                container.innerHTML = '<img src="img/leaflet/controls/marker.png" title="Marcadores">';

                return container;
            }
        }).bind(thisReact);
        let controlClusterMapObj = new mapElements.controlClusterMap();
        mapElements.map.addControl(controlClusterMapObj);
        //pega o div do controle
        let divControlClusterMap = controlClusterMapObj.getContainer();
        //coloca o div do controle no div externo
        controlsMap.appendChild(divControlClusterMap);


        //HeatMap
        mapElements.controlHeatMap = L.Control.extend({
            options:{
                position: 'topright',
                check: false,
                heatmapLoaded: false,
            },
            onAdd: function(){

                let options = this.options;
                //console.log(options.check);
                let container = L.DomUtil.create('div', 'control-data-types');
                container.onclick = function(){
                    //console.log(options.check);
                    options.check = !options.check;
                    container.className = 'control-data-types leaflet-control';

                    //verifica se o mapa já foi carregado antes.
                    if(options.heatmapLoaded && !options.check){
                        thisReact.removeHeatMap();
                    }

                    if(options.heatmapLoaded && options.check){
                        thisReact.addHeatMap();
                        container.className = 'control-data-types check-control-data-types leaflet-control';
                    }

                    if(!options.heatmapLoaded && options.check){
                        thisReact.loadDataPontosPorPais();
                        options.heatmapLoaded = true;
                        container.className = 'control-data-types check-control-data-types leaflet-control';
                    }

                }.bind(options, thisReact);

                container.innerHTML = '<img src="img/leaflet/controls/heatmap.png" title="Mapa de Calor">';

                return container;
            }
        }).bind(thisReact);
        let controlHeatMapObj = new mapElements.controlHeatMap();
        mapElements.map.addControl(controlHeatMapObj);
        //pega o div do controle
        let divControlHeatMap = controlHeatMapObj.getContainer();
        //coloca o div do controle no div externo
        controlsMap.appendChild(divControlHeatMap);


        ////////////////FIM CONTROLERS DOS LAYERS////////////////////////////////////////////


        ///////////////CONTROLE HABILITA/DESABILITA ZOOM/////////////////////////////////////
        //Clusters/Markers
        mapElements.controlZoomMap = L.Control.extend({
            options:{
                position: 'topright',
                check: false,
            },
            onAdd: function(){
                let options = this.options;
                //console.log('zoom', options.check);
                let container = L.DomUtil.create('div');
                container.onclick = function(){
                    options.check = !options.check;
                    //console.log(options.check);
                    container.className = 'control-data-types leaflet-control';
                    //container.innerHTML = '<img width="24px" height="32px" src="img/leaflet/marker-off.png">';
                    thisReact.disableZoomMap();
                    if(options.check){
                        thisReact.enableZoomMap();
                        container.className = 'control-data-types check-control-data-types leaflet-control';
                        //container.innerHTML = '<img width="24px" height="32px" src="img/leaflet/marker-on.png">';
                    }
                }.bind(options, thisReact);

                container.className = 'control-data-types leaflet-control';
                if(options.check){
                    container.className = 'control-data-types check-control-data-types leaflet-control';
                }

                container.style.cursor = 'pointer';
                container.innerHTML = '<img src="img/leaflet/controls/zoom.png" title="Marcadores">';

                return container;
            }
        }).bind(thisReact);
        let controlZoomMapObj = new mapElements.controlZoomMap();
        mapElements.map.addControl(controlZoomMapObj);
        //pega o div do controle
        let divControlZoomMap = controlZoomMapObj.getContainer();
        //coloca o div do controle no div externo
        controlsMap.appendChild(divControlZoomMap);
        /////////////////////////////////////////////////////////////////////////////////////

        //DESABILITA O ZOOM PELO SCHROLL DO MOUSE
        mapElements.map.scrollWheelZoom.disable();

        /*mapElements.map.on('click', function() {
            console.log('clicou no mapa');
            if (mapElements.map.scrollWheelZoom.enabled()) {
                mapElements.controlZoomMap.className = "control-data-types leaflet-control";
                mapElements.map.scrollWheelZoom.disable();
            }
            else {
                mapElements.controlZoomMap.className = "control-data-types check-control-data-types leaflet-control";
                mapElements.map.scrollWheelZoom.enable();
            }
        });*/

        this.setState({mapElements: mapElements}, function(){
            //this.loadMap();
        });
    }

    loadMap(){

        let mapElements = this.state.mapElements;

        mapElements.map.setZoom(4);

        mapElements.map.eachLayer(function(layer){
            //if not the tile layer
            if (typeof layer._url === "undefined"){
                mapElements.map.removeLayer(layer);
            }
        }.bind(this));

        //mapElements.map.removeControl(mapElements.controlSatelliteMap);


        mapElements.markersGroup = L.layerGroup();
        mapElements.map.addLayer(mapElements.markersGroup);






        this.setState({mapElements: mapElements});

    }

    loadDataTotalPorTerritorio(){
        //console.log('types', this.state.types);
        //console.log('períodos', this.state.start, this.state.end);
        if(!this.state.start || !this.state.end){
            return;
        }

        $.ajax({
            method:'POST',
            url: "total-transito-territorio",
            data:{
                serie_id: this.props.id,
                start: this.state.start,
                end: this.state.end,
                filters: this.state.filters,
                types: this.state.types,
                typesAccident: this.state.typesAccident,
                genders: this.state.genders,
                tipoTerritorioSelecionado: this.state.tipoTerritorioSelecionado, // tipo de territorio selecionado
                codigoTerritorioSelecionado: this.state.codigoTerritorioSelecionado, //codigo do territorio, que pode ser codigo do país, regiao, uf, etc...
                tipoTerritorioAgrupamento: this.state.tipoTerritorioAgrupamento // tipo de territorio em que os dados são agrupados
            },
            cache: false,
            success: function(data) {
                //console.log(data);
                this.setState({data: data.valores}, function(){
                    this.populateMap();
                });
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    loadDataPontosPorTerritorio(){
        $.ajax({
            method:'POST',
            url: "pontos-transito-territorio",
            data:{
                serie_id: this.props.id,
                start: this.state.start,
                end: this.state.end,
                filters: this.state.filters,
                types: this.state.types,
                typesAccident: this.state.typesAccident,
                genders: this.state.genders,
                tipoTerritorioSelecionado: this.state.tipoTerritorioSelecionado, // tipo de territorio selecionado
                codigoTerritorioSelecionado: this.state.codigoTerritorioSelecionado, //codigo do territorio, que pode ser codigo do país, regiao, uf, etc...
            },
            cache: false,
            success: function(data) {
                //console.log(data);
                this.setState({data: data.valores}, function(){
                    this.populateMapCluster();
                });
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    loadDataPontosPorPais(){
        $.ajax({
            method:'POST',
            url: "pontos-transito-pais",
            data:{
                serie_id: this.props.id,
                start: this.state.start,
                end: this.state.end,
                pais: this.state.pais,
                types: this.state.types,
            },
            cache: false,
            success: function(data) {
                //console.log('pais', data);
                this.setState({dataCalor: data.valores}, function(){
                    this.heatMap();
                });
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    gerarIntervalos(data){

        if(data===undefined){
            return null;
        }


        let valores = data.map(function(item){
            return item.total;
        });

        //console.log(valores);
        let min = Math.min.apply(null, valores);
        let minUtil = parseInt(min + min * 10/100);
        let max = Math.max.apply(null, valores);
        let maxUtil = parseInt(max - max * 10/100);

        let qtdIntervalos = 5;
        let intervalo = parseInt(maxUtil / qtdIntervalos);

        //console.log('minUtil', minUtil);
        //console.log('intervalo', intervalo);

        let intervalos = [];
        intervalos.push(minUtil);
        let anterior = minUtil;
        for(let i=0;i<qtdIntervalos-1;i++){
            anterior += intervalo;
            intervalos.push(anterior);
        }

        //console.log(intervalos);

        return intervalos;
    }

    defineCor(valor, intervalos){
        let cor = null;
        for(let k in intervalos){
            if(valor < intervalos[k]){
                cor = parseInt(k)+1;
                break;
            }
        }
        //se o valor não é menor que ninguem então define a cor mais quente.
        if(cor===null){
            cor = intervalos.length;
        }

        return cor;
    }


    populateMap(){

        let _this = this;

        let mapElements = this.state.mapElements;

        //let markers = L.markerClusterGroup({ spiderfyOnMaxZoom: false, showCoverageOnHover: false, zoomToBoundsOnClick: true });

        let data = this.state.data;

        let intervalos = this.gerarIntervalos(data);

        for(let i in data){

            let cor = this.defineCor(data[i].total, intervalos);
            let classMarker = _this.state.classMarker[data[i].tipo_territorio];
            //console.log(classMarker);

            let icon = L.divIcon({
                className: classMarker+' markerCor'+cor,
                html: "<p style='color: #333;'>"+data[i].total+"</p>"
            });

            let marker = L.marker(L.latLng(data[i].lat, data[i].lng), {icon: icon})
                .bindPopup('<strong>'+data[i].nome+'</strong>')
                .openPopup();

            marker.on('mouseover', function(e){
                this.openPopup();
            });
            marker.on('mouseout', function(e){
                this.closePopup();
            });
            marker.on('click', function(e){
                let latlng = this.getLatLng();
                mapElements.map.removeLayer(this);
                let zoom = _this.state.zoom[parseInt(data[i].tipo_territorio)];
                _this.setState({
                    tipoTerritorioSelecionado: data[i].tipo_territorio,//1 - país, 2 - regiao, 3 - uf, 4 - municipio
                    codigoTerritorioSelecionado: [data[i].codigo], //203 - Brasil 13 - SE, etc...
                    tipoTerritorioAgrupamento: parseInt(data[i].tipo_territorio)+1,//1 - país, 2 - regiao, 3 - uf, 4 - municipio
                }, function(){
                    _this.state.loadData[data[i].tipo_territorio]();
                    mapElements.map.setView([e.target._latlng.lat, e.target._latlng.lng], zoom);
                });

            });
            //mapElements.map.addLayer(marker);
            mapElements.markersGroup.addLayer(marker);
        }

        this.setState({mapElements: mapElements});
    }

    populateMapCluster(){

        let _this = this;
        let mapElements = this.state.mapElements;

        let markers = L.markerClusterGroup({ spiderfyOnMaxZoom: true, showCoverageOnHover: true, zoomToBoundsOnClick: true });

        let data = this.state.data;

        for(let i in data){

            let icon = L.icon({
                iconUrl: 'img/leaflet/controls/marker.png',
                iconSize:     [32, 32], // size of the icon
                iconAnchor:   [16, 16], // point of the icon which will correspond to marker's location
                popupAnchor:  [-3, -30] // point from which the popup should open relative to the iconAnchor
            });

            console.log(data[i]);
            console.log(this.props.allFilters);

            let allFilters = this.props.allFilters;
            let filterInfo = "";
            for(let j in allFilters){
                filterInfo += '<strong>'+allFilters[j]['titulo']+':</strong> '+data[i][allFilters[j]['slug']]+'<br>'
            }


            let marker = L.marker(L.latLng(data[i].lat, data[i].lng), {icon: icon})
            //let marker = L.marker(L.latLng(data[i].lat, data[i].lng))
                .bindPopup('' +
                    '<strong>'+data[i].lat+', '+data[i].lng+'</strong><hr style="margin:5px 0; padding:0;">' +
                    '<strong>Data:</strong> '+data[i].data+'<br>' +
                    filterInfo
                )
                .openPopup();

            marker.on('mouseover', function(e){
                //this.openPopup();
            });
            marker.on('mouseout', function(e){
                //this.closePopup();
            });
            marker.on('click', function(e){
                this.openPopup();
            });
            markers.addLayer(marker);
        }
        //mapElements.map.addLayer(markers);
        mapElements.markersGroup.addLayer(markers);
        this.setState({mapElements: mapElements});
    }

    heatMap(){
        let cfg = {
            // radius should be small ONLY if scaleRadius is true (or small radius is intended)
            // if scaleRadius is false it will be the constant radius used in pixels
            "radius": 0.002,
            "maxOpacity": .7,
            // scales the radius based on map zoom
            "scaleRadius": true,
            // if set to false the heatmap uses the global maximum for colorization
            // if activated: uses the data maximum within the current map boundaries
            //   (there will always be a red spot with useLocalExtremas true)
            "useLocalExtrema": true,
            // which field name in your data represents the latitude - default "lat"
            latField: 'lat',
            // which field name in your data represents the longitude - default "lng"
            lngField: 'lng',
            // which field name in your data represents the data value - default "value"
            valueField: 'count'
        };

        let heatmapLayer = new HeatmapOverlay(cfg);
        let mapElements = this.state.mapElements;
        mapElements.map.addLayer(heatmapLayer);

        var dataCalor = {
            max: 8,
            data: this.state.dataCalor
        };

        heatmapLayer.setData(dataCalor);

        mapElements.heatmapLayer = heatmapLayer;

        //L.control.layers(null, {'Mapa de Calor': heatmapLayer}, {collapsed: false}).addTo(mapElements.map);

        this.setState({mapElements: mapElements});


    }

    removeMarkersGroup(){
        let mapElements = this.state.mapElements;
        mapElements.map.removeLayer(this.state.mapElements.markersGroup);
        this.setState({mapElements: mapElements});
    }

    addMarkersGroup(){
        let mapElements = this.state.mapElements;
        mapElements.map.addLayer(this.state.mapElements.markersGroup);
        this.setState({mapElements: mapElements});
    }

    removeHeatMap(){
        let mapElements = this.state.mapElements;
        mapElements.map.removeLayer(this.state.mapElements.heatmapLayer);
        this.setState({mapElements: mapElements});
    };

    addHeatMap(){
        let mapElements = this.state.mapElements;
        mapElements.map.addLayer(this.state.mapElements.heatmapLayer);
        this.setState({mapElements: mapElements});
    };

    enableZoomMap(){
        //console.log('aaaaaaaaaaaaaaa');
        let mapElements = this.state.mapElements;
        mapElements.map.scrollWheelZoom.enable();
        this.setState({mapElements: mapElements});
    }

    disableZoomMap(){
        let mapElements = this.state.mapElements;
        mapElements.map.scrollWheelZoom.disable();
        this.setState({mapElements: mapElements});
    }

    changeTileLayer(tile){
        let mapElements = this.state.mapElements;
        document.getElementById('controlBasic').className = "control-data-types leaflet-control";
        document.getElementById('controlContrast').className = "control-data-types leaflet-control";
        document.getElementById('controlSatellite').className = "control-data-types leaflet-control";
        mapElements.map.removeLayer(this.state.tilesLayers.basic);
        mapElements.map.removeLayer(this.state.tilesLayers.contrast);
        mapElements.map.removeLayer(this.state.tilesLayers.satellite);
        mapElements.map.addLayer(tile);
        this.setState({mapElements: mapElements});
    }

    loadData(){
        $.ajax({
            method:'POST',
            url: "valores-transito",
            data:{},
            cache: false,
            success: function(data) {
                //console.log(data);
                this.setState({regioes: data.regioes, ufs: data.ufs, acidentes: data.valores}, function(){
                    this.loadMap();
                });
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    loadMap2(){

        let turno = ['MANHÃ', 'TARDE', 'NOITE'];
        let tipo = ['Automóvel', 'Motocicleta', 'Pedestre', 'Ônibus', 'Caminhão', 'Bicicleta', 'Outros'];
        let tipo_icone = ['automovel.png', 'motocicleta.png', 'pedestre.png', 'onibus.png', 'caminhao.png', 'bicicleta.png', 'outros.png'];
        let sexo = ['MASCULINO', 'FEMININO'];
        let tipo_acidente = ['ATROPELAMENTO'];

        let acidentes = this.state.acidentes;

        for(let i in acidentes){
            acidentes[i].turno = turno[Math.floor(Math.random() * turno.length)];
            acidentes[i].tipo = tipo[Math.floor(Math.random() * tipo.length)];
            acidentes[i].tipo_icone = tipo_icone[Math.floor(Math.random() * tipo_icone.length)];
            acidentes[i].sexo = sexo[Math.floor(Math.random() * sexo.length)];
            acidentes[i].tipo_acidente = tipo_acidente[Math.floor(Math.random() * tipo_acidente.length)];
            acidentes[i].data = 'Agosto/2017';
            acidentes[i].faixa_etaria = 'NAO DISPONIVEL';
        }

        this.setState({acidentes: acidentes}, function(){
            /*var testData = {
                max:8,
                data:[
                    {lat:24.6408,lng:46.7728,tipo:'Outros', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:50.75,lng:-1.55,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:52.6333,lng:1.75,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:48.15,lng:9.4667,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:52.35,lng:4.9167,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:60.8,lng:11.1,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:43.561,lng:-116.214,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:47.5036,lng:-94.685,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:42.1818,lng:-71.1962,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:42.0477,lng:-74.1227,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:40.0326,lng:-75.719,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:40.7128,lng:-73.2962,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:27.9003,lng:-82.3024,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:38.2085,lng:-85.6918,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:46.8159,lng:-100.706,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:30.5449,lng:-90.8083,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:44.735,lng:-89.61,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:41.4201,lng:-75.6485,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:39.4209,lng:-74.4977,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:39.7437,lng:-104.979,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:39.5593,lng:-105.006,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:45.2673,lng:-93.0196,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:41.1215,lng:-89.4635,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:43.4314,lng:-83.9784,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:43.7279,lng:-86.284,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:40.7168,lng:-73.9861,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:47.7294,lng:-116.757,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:47.7294,lng:-116.757,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:35.5498,lng:-118.917,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:34.1568,lng:-118.523,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:39.501,lng:-87.3919,tipo:'Outros', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:33.5586,lng:-112.095,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:38.757,lng:-77.1487,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:33.223,lng:-117.107,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:30.2316,lng:-85.502,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:39.1703,lng:-75.5456,tipo:'Bicicleta', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:30.0041,lng:-95.2984,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:29.7755,lng:-95.4152,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:41.8014,lng:-87.6005,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:37.8754,lng:-121.687,tipo:'Bicicleta', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:38.4493,lng:-122.709,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:40.5494,lng:-89.6252,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:42.6105,lng:-71.2306,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:40.0973,lng:-85.671,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:40.3987,lng:-86.8642,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:40.4224,lng:-86.8031,tipo:'Bicicleta', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:47.2166,lng:-122.451,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:32.2369,lng:-110.956,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:41.3969,lng:-87.3274,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:41.7364,lng:-89.7043,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:42.3425,lng:-71.0677,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:33.8042,lng:-83.8893,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:36.6859,lng:-121.629,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:41.0957,lng:-80.5052,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:46.8841,lng:-123.995,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:40.2851,lng:-75.9523,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:42.4235,lng:-85.3992,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:39.7437,lng:-104.979,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:25.6586,lng:-80.3568,tipo:'Bicicleta', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:33.0975,lng:-80.1753,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:25.7615,lng:-80.2939,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:26.3739,lng:-80.1468,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:37.6454,lng:-84.8171,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:34.2321,lng:-77.8835,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:34.6774,lng:-82.928,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:39.9744,lng:-86.0779,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:35.6784,lng:-97.4944,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:33.5547,lng:-84.1872,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:27.2498,lng:-80.3797,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:41.4789,lng:-81.6473,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:41.813,lng:-87.7134,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:41.8917,lng:-87.9359,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:35.0911,lng:-89.651,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:32.6102,lng:-117.03,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:41.758,lng:-72.7444,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:39.8062,lng:-86.1407,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:41.872,lng:-88.1662,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:34.1404,lng:-81.3369,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:46.15,lng:-60.1667,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:36.0679,lng:-86.7194,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:43.45,lng:-80.5,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:44.3833,lng:-79.7,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:45.4167,lng:-75.7,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:43.75,lng:-79.2,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:45.2667,lng:-66.0667,tipo:'Outros', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:42.9833,lng:-81.25,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:44.25,lng:-79.4667,tipo:'Outros', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:45.2667,lng:-66.0667,tipo:'Veículo', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:34.3667,lng:-118.478,tipo:'Outros', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:42.734,lng:-87.8211,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:39.9738,lng:-86.1765,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:33.7438,lng:-117.866,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:37.5741,lng:-122.321,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:42.2843,lng:-85.2293,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:34.6574,lng:-92.5295,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:41.4881,lng:-87.4424,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:25.72,lng:-80.2707,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:34.5873,lng:-118.245,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'},
                    {lat:35.8278,lng:-78.6421,tipo:'Pedestre', data:'Agosto / 2017', turno:'NOITE', faixa_etaria:'NAO DISPONIVEL', sexo:'MASCULINO', tipo_acidente:'ATROPELAMENTO'}]
            };*/

            let testData = {
                max: 8,
                data: this.state.acidentes
            };

            let tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            });


            /*var tiles = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 18,
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
            });

             var Thunderforest_Landscape = L.tileLayer('https://{s}.tile.thunderforest.com/landscape/{z}/{x}/{y}.png?apikey={apikey}', {
             attribution: '&copy; <a href="http://www.thunderforest.com/">Thunderforest</a>, &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
             apikey: '<your apikey>',
             maxZoom: 22
             });

             var Stamen_Toner = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/toner/{z}/{x}/{y}.{ext}', {
             attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
             subdomains: 'abcd',
             minZoom: 0,
             maxZoom: 20,
             ext: 'png'
             });

             var Esri_OceanBasemap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Ocean_Basemap/MapServer/tile/{z}/{y}/{x}', {
             attribution: 'Tiles &copy; Esri &mdash; Sources: GEBCO, NOAA, CHS, OSU, UNH, CSUMB, National Geographic, DeLorme, NAVTEQ, and Esri',
             maxZoom: 13
             });

             var CartoDB_DarkMatter = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_all/{z}/{x}/{y}.png', {
             attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="http://cartodb.com/attributions">CartoDB</a>',
             subdomains: 'abcd',
             maxZoom: 19
             });
            */

            var latlng = L.latLng(-13.70, -69.65);

            var map = L.map('map', {
                center: latlng,
                zoom: 4,
                layers: [tiles],
                fullscreenControl: true,
                fullscreenControlOptions: { // optional
                    title:"Show me the fullscreen !",
                    titleCancel:"Exit fullscreen mode"
                }
            });


            let markers = L.markerClusterGroup({ spiderfyOnMaxZoom: false, showCoverageOnHover: false, zoomToBoundsOnClick: true });
            let grupoRegioes = [];
            let grupoUfs = [];


            function popularRegiao(_this){
                for (let j = 0; j < _this.state.regioes.length; j++) {

                    //criar grupo de cluster para cada regiao
                    let regiao = L.markerClusterGroup({ spiderfyOnMaxZoom: false, showCoverageOnHover: false, zoomToBoundsOnClick: true,  maxClusterRadius: 200});

                    //coloca os pontos de uma determinada reigao dentro de seu respectivo grupo
                    for (let i = 0; i < testData.data.length; i++) {
                        if(testData.data[i].sigla_regiao == _this.state.regioes[j].sigla){
                            //console.log(testData.data[i].tipo);
                            let tipoIcon = L.icon({
                                iconUrl: 'img/leaflet/'+testData.data[i].tipo_icone,

                                iconSize:     [48, 48], // size of the icon
                                iconAnchor:   [24, 24], // point of the icon which will correspond to marker's location
                                popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
                            });



                            //var m = L.marker(getRandomLatLng(map));
                            var m = L.marker(L.latLng(testData.data[i].lat, testData.data[i].lng), {icon: tipoIcon})
                                .bindPopup('' +
                                    '<strong>'+testData.data[i].tipo+'</strong><hr style="margin:5px 0; padding:0;">' +
                                    '<strong>Data:</strong> '+testData.data[i].data+'<br>' +
                                    '<strong>Turno:</strong> '+testData.data[i].turno+' <br>' +
                                    '<strong>Faixa Etária:</strong> '+testData.data[i].faixa_etaria+' <br>' +
                                    '<strong>Sexo:</strong> '+testData.data[i].sexo+' <br>' +
                                    '<strong>Tipo Acidente:</strong> '+testData.data[i].tipo_acidente+' ')
                                .openPopup();
                            regiao.addLayer(m);
                        }

                    }

                    //map.addLayer(regiao);
                    grupoRegioes.push(regiao);
                }

                addLayersRegioes();
            }

            function popularUf(_this){
                for (let j = 0; j < _this.state.ufs.length; j++) {

                    //criar grupo de cluster para cada uf
                    let uf = L.markerClusterGroup({ spiderfyOnMaxZoom: false, showCoverageOnHover: false, zoomToBoundsOnClick: true,  maxClusterRadius: 120});

                    //coloca os pontos de uma determinada reigao dentro de seu respectivo grupo
                    for (let i = 0; i < testData.data.length; i++) {
                        if(testData.data[i].uf == _this.state.ufs[j].sigla){
                            //console.log(testData.data[i].tipo);
                            let tipoIcon = L.icon({
                                iconUrl: 'img/leaflet/'+testData.data[i].tipo_icone,

                                iconSize:     [48, 48], // size of the icon
                                iconAnchor:   [24, 24], // point of the icon which will correspond to marker's location
                                popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
                            });



                            //var m = L.marker(getRandomLatLng(map));
                            var m = L.marker(L.latLng(testData.data[i].lat, testData.data[i].lng), {icon: tipoIcon})
                                .bindPopup('' +
                                    '<strong>'+testData.data[i].tipo+'</strong><hr style="margin:5px 0; padding:0;">' +
                                    '<strong>Data:</strong> '+testData.data[i].data+'<br>' +
                                    '<strong>Turno:</strong> '+testData.data[i].turno+' <br>' +
                                    '<strong>Faixa Etária:</strong> '+testData.data[i].faixa_etaria+' <br>' +
                                    '<strong>Sexo:</strong> '+testData.data[i].sexo+' <br>' +
                                    '<strong>Tipo Acidente:</strong> '+testData.data[i].tipo_acidente+' ')
                                .openPopup();
                            uf.addLayer(m);
                        }

                    }

                    //map.addLayer(uf);
                    grupoUfs.push(uf);
                }
            }

            function marcarPontos(){
                //for (let i = 0; i < testData.data.length-(testData.data.length/2); i++) {
                for (let i = 0; i < testData.data.length; i++) {

                    //console.log(testData.data[i].tipo);
                    let tipoIcon = L.icon({
                        iconUrl: 'img/leaflet/'+testData.data[i].tipo_icone,

                        iconSize:     [48, 48], // size of the icon
                        iconAnchor:   [24, 24], // point of the icon which will correspond to marker's location
                        popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
                    });



                    //var m = L.marker(getRandomLatLng(map));
                    var m = L.marker(L.latLng(testData.data[i].lat, testData.data[i].lng), {icon: tipoIcon})
                        .bindPopup('' +
                            '<strong>'+testData.data[i].tipo+'</strong><hr style="margin:5px 0; padding:0;">' +
                            '<strong>Data:</strong> '+testData.data[i].data+'<br>' +
                            '<strong>Turno:</strong> '+testData.data[i].turno+' <br>' +
                            '<strong>Faixa Etária:</strong> '+testData.data[i].faixa_etaria+' <br>' +
                            '<strong>Sexo:</strong> '+testData.data[i].sexo+' <br>' +
                            '<strong>Tipo Acidente:</strong> '+testData.data[i].tipo_acidente+' ')
                        .openPopup();
                    markers.addLayer(m);
                }

                //map.addLayer(markers);
            }

            function addLayersRegioes(){
                for(let i in grupoRegioes){
                    map.addLayer(grupoRegioes[i])
                }
            }

            function removeLayersRegioes(){
                for(let i in grupoRegioes){
                    map.removeLayer(grupoRegioes[i])
                }
            }

            function addLayersUfs(){
                for(let i in grupoUfs){
                    map.addLayer(grupoUfs[i])
                }
            }

            function removeLayersUfs(){
                for(let i in grupoUfs){
                    map.removeLayer(grupoUfs[i])
                }
            }


            function populate(_this) {


                popularRegiao(_this);
                popularUf(_this);
                marcarPontos();

                return false;
            }

            var polygon;
            markers.on('clustermouseover', function (a) {
                if (polygon) {
                    map.removeLayer(polygon);
                }
                polygon = L.polygon(a.layer.getConvexHull());
                //console.log(polygon);
                map.addLayer(polygon);
            });

            markers.on('clustermouseout', function (a) {
                if (polygon) {
                    map.removeLayer(polygon);
                    polygon = null;
                }
            });

            map.on('zoomend', function () {
                /*if (polygon) {
                    map.removeLayer(polygon);
                    polygon = null;
                }*/

                map.removeLayer(markers);
                removeLayersRegioes();
                removeLayersUfs();
                let zoom = map.getZoom();
                //console.log(zoom);
                if(zoom <= 4){
                    addLayersRegioes()
                }
                if(zoom > 4 && zoom <= 10){
                    addLayersUfs()
                }
                if(zoom > 10){
                    map.addLayer(markers)
                }
            });

            // detect fullscreen toggling
            map.on('enterFullscreen', function(){
                if(window.console) window.console.log('enterFullscreen');
            });
            map.on('exitFullscreen', function(){
                if(window.console) window.console.log('exitFullscreen');
            });
            ///////////////////FIM BOX - RAG

            populate(this);
            //map.addLayer(markers);
            //map.addLayer(markers2);
        });

    }

    render(){


        return(
            <div>
                <div id="map" className="map" />
                <div id="controls-map" className="control-container" />
            </div>
        );
    }
}