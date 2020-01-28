class Map extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            mymap: null,
            data: null,
            legend: [],
            indexLegend: 1,
            lastIndexLegend: 0,
            carregado: false,
            linhas: ['Brasil', 'Carioca', 'Oeste', 'Olimpica'],
            colors: ['black', 'orange', 'blue', 'green']
        };

        this.loadMap = this.loadMap.bind(this);
        this.refreshMarkers = this.refreshMarkers.bind(this);
        this.refreshMarkersEstacoes = this.refreshMarkersEstacoes.bind(this);
    }

    componentDidMount() {
        this.setState({ mymap: L.map(this.props.mapId, {
                fullscreenControl: true,
                fullscreenControlOptions: { // optional
                    title: "Show me the fullscreen !",
                    titleCancel: "Exit fullscreen mode"
                }
            }).setView([-14, -52], 4) }, function () {});
    }

    componentWillReceiveProps(props) {

        if (this.state.data != props.data) {
            this.setState({ data: props.data }, function () {

                if (this.state.data) {
                    this.loadMap();
                }
            });
        }
    }

    loadMap() {

        let map = this.state.mymap;

        let tileLayer = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
            maxZoom: 18
        }).addTo(map);

        let featuresEstacoes = this.state.data.estacoes.features;
        let linhas = this.state.linhas;

        map.attributionControl.setPrefix(''); // Don't show the 'Powered by Leaflet' text.


        //Define an array of Latlng objects (points along the line)
        let polylinePoints = {};

        for (let i in linhas) {
            polylinePoints[linhas[i]] = [];
        }

        for (let i in featuresEstacoes) {
            for (let j in linhas) {
                if (featuresEstacoes[i].properties['Flg_Trans' + linhas[j]] === "Sim") {
                    polylinePoints[linhas[j]].push(new L.LatLng(featuresEstacoes[i].geometry.coordinates[1], featuresEstacoes[i].geometry.coordinates[0]));
                    break;
                }
            }
        }

        let polylineOptions = {
            color: 'grey',
            weight: 6,
            opacity: 0.9
        };

        for (let i in linhas) {
            polylineOptions.color = this.state.colors[i];
            let polyline = new L.Polyline(polylinePoints[linhas[i]], polylineOptions);
            map.addLayer(polyline);
            // zoom the map to the polyline
            //map.fitBounds(polyline.getBounds());
        }

        this.setState({ mymap: map }, function () {
            if (this.state.data) {
                this.refreshMarkers(this.state.data);
                this.refreshMarkersEstacoes(this.state.data);
            }
        });
    }

    refreshMarkers(data) {
        let map = this.state.mymap;
        let markers = L.layerGroup();

        ///////////////ICONE/////////////////
        var LeafIcon = L.Icon.extend({
            options: {
                iconSize: [40, 44]
            }
        });

        var markerOn = new LeafIcon({ iconUrl: 'img/brt-marker.png' });
        var markerOff = new LeafIcon({ iconUrl: 'img/marker-off.png' });
        ///////////////ICONE/////////////////

        //////////////MARKERS///////////////
        let pontos = [];
        //console.log(data['brt']["veiculos"]);

        for (let i in data['brt']) {
            let marker = markerOn;

            L.marker([data["brt"][i]["latitude"], data["brt"][i]["longitude"]], { icon: marker }).bindPopup('<b>' + data["brt"][i]["trajeto"] + '</b><br>' + 'Data Hora: ' + data["brt"][i]["data_hora"] + '<br>' + 'Linha: ' + data["brt"][i]["linha"] + '<br>' + 'Sentido: ' + data["brt"][i]["sentido"] + '<br>' + 'Código: ' + data["brt"][i]["codigo"] + '<br>' + 'Latitude: ' + data["brt"][i]["latitude"] + '<br>' + 'Longitude: ' + data["brt"][i]["longitude"] + '<br>').addTo(markers);
        }

        markers.addTo(map);

        var overlayMaps = {
            "<img src='/img/icon-brt.jpg'> ": markers
        };

        L.control.layers(null, overlayMaps, { collapsed: false }).addTo(map);

        markers.addTo(map);

        //////////////MARKERS///////////////

        this.setState({ mymap: map, markers: markers });
    }

    refreshMarkersEstacoes(data) {
        let map = this.state.mymap;
        let markers = L.layerGroup();

        let linhaCarioca = L.layerGroup();
        let linhaOeste = L.layerGroup();
        let linhaOlimpica = L.layerGroup();

        /*for(let k in this.state.data){
            //linhas[k] = L.layerGroup();
            console.log(k);
        }*/

        ///////////////ICONE/////////////////
        var LeafIcon = L.Icon.extend({
            options: {
                iconSize: [40, 44]
            }
        });
        var markerCarioca = new LeafIcon({ iconUrl: 'img/brt-estacao-carioca.png' });
        var markerOeste = new LeafIcon({ iconUrl: 'img/brt-estacao-oeste.png' });
        var markerOlimpica = new LeafIcon({ iconUrl: 'img/brt-estacao-olimpica.png' });
        var markerOff = new LeafIcon({ iconUrl: 'img/brt-estacao-off.png' });
        ///////////////ICONE/////////////////

        let pontos = [];

        //////////////MARKERS///////////////

        for (let i in data['estacoes']["features"]) {

            let marker = markerOff;
            if (data['estacoes']["features"][i]["properties"]["Flg_TransBrasil"] === "Sim") {
                //let marker = markerOff;
            } else if (data['estacoes']["features"][i]["properties"]["Flg_TransCarioca"] === "Sim") {
                marker = markerCarioca;
            } else if (data['estacoes']["features"][i]["properties"]["Flg_TransOeste"] === "Sim") {
                marker = markerOeste;
            } else if (data['estacoes']["features"][i]["properties"]["Flg_TransOlimpica"] === "Sim") {
                marker = markerOlimpica;
            }

            let m = L.marker([data['estacoes']["features"][i]["geometry"]["coordinates"][1], data['estacoes']["features"][i]["geometry"]["coordinates"][0]], { icon: marker }).bindPopup('<b>' + data['estacoes']["features"][i]["properties"]["Nome"] + '</b><br>' + 'Status: ' + data['estacoes']["features"][i]["properties"]["Flg_Ativo"] + '<br>' + 'Integra Aeroporto: ' + data['estacoes']["features"][i]["properties"]["Integra_Aeroporto"] + '<br>' + 'Integra Metro: ' + data['estacoes']["features"][i]["properties"]["Integra_Metro"] + '<br>' + 'Integra Trem: ' + data['estacoes']["features"][i]["properties"]["Integra_Trem"] + '<br>');

            if (data['estacoes']["features"][i]["properties"]["Flg_TransBrasil"] === "Sim") {
                //let marker = markerOff;
            } else if (data['estacoes']["features"][i]["properties"]["Flg_TransCarioca"] === "Sim") {
                m.addTo(linhaCarioca);
            } else if (data['estacoes']["features"][i]["properties"]["Flg_TransOeste"] === "Sim") {
                m.addTo(linhaOeste);
            } else if (data['estacoes']["features"][i]["properties"]["Flg_TransOlimpica"] === "Sim") {
                m.addTo(linhaOlimpica);
            }

            pontos.push([data['estacoes']["features"][i]["geometry"]["coordinates"][1], data['estacoes']["features"][i]["geometry"]["coordinates"][0]]);
        }

        linhaCarioca.addTo(map);
        linhaOeste.addTo(map);
        linhaOlimpica.addTo(map);

        var overlayMaps = {
            "<img src='/img/trans-carioca.jpg'> ": linhaCarioca,
            "<img src='/img/trans-oeste.jpg'>": linhaOeste,
            "<img src='/img/trans-olimpica.jpg'>": linhaOlimpica
        };

        L.control.layers(null, overlayMaps, { collapsed: false }).addTo(map);
        let bounds = new L.LatLngBounds(pontos);
        map.fitBounds(bounds);
        //////////////MARKERS///////////////

        this.setState({ mymap: map, markers: markers });
    }

    render() {
        let estacoes = null;
        if (this.state.data) {

            //console.log(this.state.data);
            //console.log(this.state.data['estacoes']['features'][5]['properties']['lines']);

            estacoes = this.state.data['estacoes']['features'].map(function (item, index) {

                //let columns = Object.keys(item['properties']['lines']);
                //console.log(columns);


                let flgTransBrasil = item['properties']['Flg_TransBrasil'];
                let flgTransCarioca = item['properties']['Flg_TransCarioca'];
                let flgTransOeste = item['properties']['Flg_TransOeste'];
                let flgTransOlimpica = item['properties']['Flg_TransOlimpica'];

                return React.createElement(
                    'tr',
                    { key: 'lista-' + index },
                    React.createElement(
                        'td',
                        null,
                        React.createElement('div', { className: "linhas " + (flgTransBrasil == "Sim" ? "linha-trans-brasil" : "linha-off") }),
                        React.createElement('div', { className: "linhas " + (flgTransCarioca == "Sim" ? "linha-trans-carioca" : "linha-off") }),
                        React.createElement('div', { className: "linhas " + (flgTransOeste == "Sim" ? "linha-trans-oeste" : "linha-off") }),
                        React.createElement('div', { className: "linhas " + (flgTransOlimpica == "Sim" ? "linha-trans-olimpica" : "linha-off") })
                    ),
                    React.createElement(
                        'td',
                        null,
                        item['properties']['titulo']
                    ),
                    React.createElement(
                        'td',
                        null,
                        React.createElement('img', { src: '/img/estacoes/metro.png', width: '25px', alt: 'Metro', className: 'icon-style' }),
                        React.createElement('img', { src: '/img/estacoes/trem.png', width: '25px', alt: 'Trem', className: 'icon-style' }),
                        React.createElement('img', { src: '/img/estacoes/brt.png', width: '25px', alt: 'BRT', className: 'icon-style' }),
                        React.createElement('img', { src: '/img/estacoes/metro.png', className: item.properties.metro == 1 ? "show icon-style" : "hidden", width: '20px', alt: 'Metro', title: 'Metro' }),
                        React.createElement('img', { src: '/img/estacoes/brt.png', className: item.properties.brt == 1 ? "show icon-style" : "hidden", width: '20px', alt: 'BRT', title: 'BRT' }),
                        React.createElement('img', { src: '/img/estacoes/trem.png', className: item.properties.trem == 1 ? "show icon-style" : "hidden", width: '20px', alt: 'Trem', title: 'Trem' }),
                        React.createElement('img', { src: '/img/estacoes/vlt.png', className: item.properties.vlt == 1 ? "show icon-style" : "hidden", width: '20px', alt: 'VLT', title: 'VLT' }),
                        React.createElement('img', { src: '/img/estacoes/barca.png', className: item.properties.barca == 1 ? "show icon-style" : "hidden", width: '20px', alt: 'Barca', title: 'Barca' }),
                        React.createElement('img', { src: '/img/estacoes/aeroporto.png', className: item.properties.aeroporto == 1 ? "show icon-style" : "hidden", width: '20px', alt: 'A\xE9roporto', title: 'A\xE9roporto' }),
                        React.createElement('img', { src: '/img/estacoes/bicicletario.png', className: item.properties.bicicletario == 1 ? "show icon-style" : "hidden", width: '20px', alt: 'Bicicletario', title: 'Bicicletario' })
                    ),
                    React.createElement(
                        'td',
                        null,
                        React.createElement('i', { className: item['properties']['ativo'] == 1 ? "fa-2x fa fa-check-circle-o text-success" : "fa-2x fa fa-times-circle-o text-danger", 'aria-hidden': 'true' })
                    ),
                    React.createElement(
                        'td',
                        null,
                        React.createElement('i', { className: item['properties']['status'] == 1 ? "fa-2x fa fa-check-circle-o text-success" : "fa-2x fa fa-times-circle-o text-danger", 'aria-hidden': 'true' })
                    )
                );
            });
        }

        let brts = null;
        if (this.state.data) {

            brts = this.state.data["brt"].map(function (item) {

                /*let flgTransBrasil =  item['properties']['Flg_TransBrasil'];
                let flgTransCarioca =  item['properties']['Flg_TransCarioca'];
                let flgTransOeste =  item['properties']['Flg_TransOeste'];
                let flgTransOlimpica =  item['properties']['Flg_TransOlimpica'];*/

                return React.createElement(
                    'tr',
                    null,
                    React.createElement(
                        'td',
                        null,
                        item['linha']
                    ),
                    React.createElement(
                        'td',
                        null,
                        item['codigo']
                    ),
                    React.createElement(
                        'td',
                        null,
                        item['trajeto']
                    ),
                    React.createElement(
                        'td',
                        null,
                        item['sentido']
                    ),
                    React.createElement(
                        'td',
                        null,
                        item['velocidade']
                    ),
                    React.createElement(
                        'td',
                        null,
                        item['latitude'],
                        ' - ',
                        item['longitude']
                    )
                );
            });
        }

        return React.createElement(
            'div',
            null,
            React.createElement('div', { id: this.props.mapId, style: { height: '600px' } }),
            React.createElement(
                'div',
                { className: 'container' },
                React.createElement('br', null),
                React.createElement(
                    'div',
                    { className: 'text-center' },
                    React.createElement(
                        'h2',
                        null,
                        'Esta\xE7\xF5es'
                    ),
                    React.createElement(
                        'p',
                        null,
                        'Nessa \xE1rea voc\xEA consegue informa\xE7\xF5es das esta\xE7\xF5es e suas situa\xE7\xF5es e integra\xE7\xF5es'
                    ),
                    React.createElement('hr', null)
                ),
                React.createElement('br', null),
                React.createElement(
                    'div',
                    { className: 'row' },
                    React.createElement(
                        'div',
                        { className: 'col-md-12' },
                        React.createElement(
                            'div',
                            { className: 'table-responsive-sm' },
                            React.createElement(
                                'table',
                                { className: 'table' },
                                React.createElement(
                                    'thead',
                                    null,
                                    React.createElement(
                                        'tr',
                                        null,
                                        React.createElement(
                                            'th',
                                            null,
                                            'Linha'
                                        ),
                                        React.createElement(
                                            'th',
                                            null,
                                            'Terminal'
                                        ),
                                        React.createElement(
                                            'th',
                                            null,
                                            'Integr\xE7\xF5pes'
                                        ),
                                        React.createElement(
                                            'th',
                                            null,
                                            'Ativo'
                                        ),
                                        React.createElement(
                                            'th',
                                            null,
                                            'Status'
                                        )
                                    )
                                ),
                                React.createElement(
                                    'tbody',
                                    null,
                                    estacoes
                                )
                            )
                        )
                    )
                )
            ),
            React.createElement(
                'div',
                { className: 'container' },
                React.createElement('br', null),
                React.createElement(
                    'div',
                    { className: 'text-center' },
                    React.createElement(
                        'h2',
                        null,
                        'BRTs'
                    ),
                    React.createElement(
                        'p',
                        null,
                        'Nessa \xE1rea voc\xEA consegue acompanha em tempo real a situa\xE7\xE3o do BRTs'
                    ),
                    React.createElement('hr', null)
                ),
                React.createElement('br', null),
                React.createElement(
                    'div',
                    { className: 'row' },
                    React.createElement(
                        'div',
                        { className: 'col-md-12' },
                        React.createElement(
                            'div',
                            { className: 'table-responsive-sm' },
                            React.createElement(
                                'table',
                                { className: 'table' },
                                React.createElement(
                                    'thead',
                                    null,
                                    React.createElement(
                                        'tr',
                                        null,
                                        React.createElement(
                                            'th',
                                            null,
                                            'Linha'
                                        ),
                                        React.createElement(
                                            'th',
                                            null,
                                            'C\xF3digo'
                                        ),
                                        React.createElement(
                                            'th',
                                            null,
                                            'Trajeto'
                                        ),
                                        React.createElement(
                                            'th',
                                            null,
                                            'Sentido'
                                        ),
                                        React.createElement(
                                            'th',
                                            null,
                                            'Velocidade'
                                        ),
                                        React.createElement(
                                            'th',
                                            null,
                                            'Pr\xF3ximo'
                                        )
                                    )
                                ),
                                React.createElement(
                                    'tbody',
                                    null,
                                    brts
                                )
                            )
                        )
                    )
                )
            )
        );
    }

}