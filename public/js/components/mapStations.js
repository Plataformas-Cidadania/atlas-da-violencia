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
            colors: ['black', 'orange', 'blue', 'green'],
            transportes: null,
            matrizTransportes: null,
            colorsChart: ['#AC74AC', '#4DA6FF', '#7568EC', '#EC7F46', '#E01747', '#D9A300', '#226FB3', '#EDB621', '#698F36'],
            selectedStations: ['aeroporto', 'barca']
        };

        this.loadMap = this.loadMap.bind(this);
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

        map.attributionControl.setPrefix(''); // Don't show the 'Powered by Leaflet' text.

        this.setState({ mymap: map }, function () {
            if (this.state.data) {
                this.refreshMarkersEstacoes(this.state.data);
            }
        });
    }

    refreshMarkersEstacoes(data) {

        console.log(data);

        let map = this.state.mymap;
        let markers = L.layerGroup();

        let linhas = {};

        for (let k in this.state.data) {
            linhas[k] = L.layerGroup();
            //console.log(k);
        }

        ///////////////ICONE/////////////////
        var LeafIcon = L.Icon.extend({
            options: {
                iconSize: [40, 44]
            }
        });
        ///////////////ICONE/////////////////
        let pontos = [];

        //let matrizTransportes = [];

        let overlayMaps = {};
        let transportes = {
            titles: [],
            values: []
        };

        let matrizTransportes = {
            titles: [],
            values: []
        };

        let tiposTransportes = [];

        for (let transporte in data) {

            let existe = false;
            for (let i in tiposTransportes) {
                if (tiposTransportes[i].tipo === data[transporte].properties.tipo) {
                    tiposTransportes[i]['qtd'] += data[transporte]['features'].length;
                    existe = true;
                }
            }

            if (!existe) {
                tiposTransportes.push({
                    'tipo': data[transporte].properties.tipo,
                    'qtd': data[transporte].features.length
                });
            }
        }

        for (let i in tiposTransportes) {
            matrizTransportes['titles'].push(tiposTransportes[i].tipo);
            matrizTransportes['values'].push(tiposTransportes[i].qtd);
        }

        /*console.log('tiposTransportes', tiposTransportes);
        console.log('matrizTransportes', matrizTransportes);
         console.log(data.brt.features.length);*/

        /*matrizTransportes = {
            titles: ['Rodoviário', 'Ferroviário', 'Aério', 'Aquaviário', 'Outros'],
            values: [rodoviario, ferrofiario, aerio, aquaviario, outros]
        };*/

        for (let k in data) {
            //console.log(data[k]['properties'].icone);

            console.log(data[k]);
            var markerTerminais = L.icon({
                iconUrl: 'imagens/transportes_icones/' + data[k]['properties'].icone
            });

            overlayMaps["<img src='imagens/transportes/" + data[k]['properties'].imagem + "' width='40' alt='" + k + "' title='" + k + "'> "] = linhas[k];

            L.geoJson(data[k], {
                pointToLayer: function (feature, latlng) {
                    return L.marker(latlng, {
                        icon: markerTerminais
                    });
                }.bind(this),
                onEachFeature: function (f, l) {
                    l.bindPopup('<b>' + JSON.stringify(f.properties.titulo, null, ' ').replace(/[\{\}"]/g, '') + '</b>');
                }
            }).addTo(linhas[k]);

            if (this.state.selectedStations.includes(k)) {
                linhas[k].addTo(map);
            }

            if (k !== 'bicicletario') {
                transportes.titles.push(data[k].properties.titulo);
                transportes.values.push(data[k].features.length);
            }

            for (let i in data[k]['features']) {
                pontos.push([data[k]["features"][i]["geometry"]["coordinates"][1], data[k]["features"][i]["geometry"]["coordinates"][0]]);
            }
        }

        //console.log(transportes);

        L.control.layers(null, overlayMaps, { collapsed: false }).addTo(map);

        //let bounds = new L.LatLngBounds(pontos);


        let bounds = new L.LatLngBounds(pontos);
        map.fitBounds(bounds);
        //////////////MARKERS///////////////

        this.setState({ mymap: map, markers: markers, transportes: transportes, matrizTransportes: matrizTransportes });
    }

    render() {

        let abasArray = [];
        let abas = null;
        let abasConteudo = null;
        let icones = null;
        let populacaoTransporte = null;
        let potulacao = 6688927;

        if (this.state.data) {
            for (let k in this.state.data) {
                //console.log(this.state.data);

                //var dadosImg = this.state.data[k];

                let lista = this.state.data[k]["features"].map(function (item, index) {
                    //console.log(this.state.data);
                    return React.createElement(
                        'tr',
                        { key: 'lista' + index },
                        React.createElement(
                            'td',
                            null,
                            React.createElement(
                                'h5',
                                null,
                                React.createElement(
                                    'strong',
                                    null,
                                    index + 1,
                                    ' - ',
                                    item.properties.titulo
                                )
                            ),
                            React.createElement(
                                'p',
                                null,
                                item.properties.endereco
                            ),
                            React.createElement(
                                'p',
                                null,
                                item.properties.telefone
                            )
                        ),
                        React.createElement(
                            'td',
                            null,
                            React.createElement('img', { src: 'img/estacoes/metro.png', className: item.properties.metro == 1 ? "show icon-style" : "hidden", width: '20px', alt: 'Metro', title: 'Metro' }),
                            React.createElement('img', { src: 'img/estacoes/brt.png', className: item.properties.brt == 1 ? "show icon-style" : "hidden", width: '20px', alt: 'BRT', title: 'BRT' }),
                            React.createElement('img', { src: 'img/estacoes/trem.png', className: item.properties.trem == 1 ? "show icon-style" : "hidden", width: '20px', alt: 'Trem', title: 'Trem' }),
                            React.createElement('img', { src: 'img/estacoes/vlt.png', className: item.properties.vlt == 1 ? "show icon-style" : "hidden", width: '20px', alt: 'VLT', title: 'VLT' }),
                            React.createElement('img', { src: 'img/estacoes/barca.png', className: item.properties.barca == 1 ? "show icon-style" : "hidden", width: '20px', alt: 'Barca', title: 'Barca' }),
                            React.createElement('img', { src: 'img/estacoes/aeroporto.png', className: item.properties.aeroporto == 1 ? "show icon-style" : "hidden", width: '20px', alt: 'A\xE9roporto', title: 'A\xE9roporto' }),
                            React.createElement('img', { src: 'img/estacoes/bicicletario.png', className: item.properties.bicicletario == 1 ? "show icon-style" : "hidden", width: '20px', alt: 'Bicicletario', title: 'Bicicletario' })
                        )
                    );
                });

                abasArray.push({
                    'titulo': this.state.data[k]['properties']['titulo'],
                    'imagem': this.state.data[k]['properties']['imagem'],
                    'lista': lista
                });
            }

            abas = abasArray.map(function (item, index) {
                return React.createElement(
                    'li',
                    { key: 'li' + index, className: 'btn btn-primary btn-pri' },
                    React.createElement(
                        'a',
                        { key: 'aba' + index, className: "nav-link " + (index == 0 ? 'active' : null), id: "v-pills-" + item.titulo + "-tab", 'data-toggle': 'pill',
                            href: "#v-pills-" + index, role: 'tab', 'aria-controls': "v-pills-" + item.titulo,
                            'aria-selected': 'true' },
                        item.titulo
                    )
                );
            });

            abasConteudo = abasArray.map(function (item, index) {
                return React.createElement(
                    'div',
                    { key: 'abas' + index, className: "tab-pane " + (index == 0 ? 'active' : null), id: "v-pills-" + index, role: 'tabpanel',
                        'aria-labelledby': "v-pills-" + item.titulo + "-tab" },
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
                                    item.titulo
                                ),
                                React.createElement(
                                    'th',
                                    null,
                                    'Integra\xE7\xF5es'
                                )
                            )
                        ),
                        React.createElement(
                            'tbody',
                            null,
                            item.lista
                        )
                    )
                );
            });

            icones = abasArray.map(function (item, index) {
                return React.createElement(
                    'div',
                    { key: 'icones' + index, className: "block col-md-3  " + (index == 0 ? 'active' : null), 'data-move-y': '200px', 'data-move-x': '-200px' },
                    React.createElement(
                        'div',
                        { className: 'text-center img-circle icons-circle' },
                        React.createElement(
                            'div',
                            { className: 'img-circle icons-circle-int' },
                            React.createElement('img', { src: "imagens/transportes/" + item.imagem, width: '50' }),
                            React.createElement('br', null),
                            item.titulo,
                            React.createElement(
                                'h2',
                                null,
                                item.lista.length
                            )
                        ),
                        React.createElement('br', null),
                        React.createElement('br', null)
                    )
                );
            });
            populacaoTransporte = abasArray.map(function (item, index) {
                //console.log('*** ', item);
                return React.createElement(
                    'div',
                    { key: 'populacao' + index, className: "col-md-3  " + (index == 0 ? 'active' : null) },
                    React.createElement(
                        'div',
                        { className: 'box-list bg-qui' },
                        React.createElement('img', { src: "imagens/transportes/" + item.imagem, width: '50' }),
                        React.createElement('img', { src: "img/pessoa.png", width: '50' }),
                        React.createElement(
                            'h2',
                            null,
                            React.createElement(
                                'strong',
                                null,
                                Math.round(potulacao / item.lista.length)
                            )
                        ),
                        React.createElement(
                            'p',
                            { className: 'tamanhoMinimo' },
                            'Quantidade de pessoas por ',
                            item.titulo
                        )
                    ),
                    React.createElement('br', null),
                    React.createElement('br', null)
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
                ' ',
                React.createElement('br', null),
                ' ',
                React.createElement('br', null),
                React.createElement(
                    'div',
                    { className: 'row' },
                    React.createElement(
                        'div',
                        { className: 'col-md-6 col-sm-12' },
                        React.createElement(
                            'h3',
                            null,
                            'Terminais'
                        ),
                        React.createElement('hr', null),
                        React.createElement('br', null),
                        React.createElement(BarChart, { id: 'bar-chart1', data: this.state.transportes, colors: this.state.colorsChart })
                    ),
                    React.createElement(
                        'div',
                        { className: 'col-md-6 col-sm-12' },
                        React.createElement(
                            'h3',
                            null,
                            'Matriz'
                        ),
                        React.createElement('hr', null),
                        React.createElement('br', null),
                        React.createElement(BarChart, { id: 'bar-chart2', data: this.state.matrizTransportes, colors: this.state.colorsChart })
                    )
                )
            ),
            React.createElement(
                'div',
                { className: 'container' },
                React.createElement('br', null),
                React.createElement('br', null),
                React.createElement('br', null),
                React.createElement(
                    'div',
                    { className: 'row' },
                    icones
                )
            ),
            React.createElement(
                'div',
                { className: 'container' },
                React.createElement('br', null),
                React.createElement('br', null),
                React.createElement('br', null),
                React.createElement(
                    'div',
                    { className: 'text-center' },
                    React.createElement(
                        'h2',
                        null,
                        'Popula\xE7\xE3o'
                    ),
                    React.createElement(
                        'p',
                        null,
                        'Comparativo de quantidade de pessoas por estaq\xE7\xF5es de meio de transporte'
                    ),
                    React.createElement('hr', null)
                ),
                React.createElement('br', null),
                React.createElement(
                    'div',
                    { className: 'row' },
                    populacaoTransporte
                )
            ),
            React.createElement(
                'div',
                { className: 'container' },
                React.createElement('br', null),
                React.createElement('br', null),
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
                                'div',
                                { className: 'row' },
                                React.createElement(
                                    'div',
                                    { className: 'col-md-3' },
                                    React.createElement(
                                        'div',
                                        { className: 'nav flex-column nav-pills', id: 'v-pills-tab', role: 'tablist', 'aria-orientation': 'vertical' },
                                        React.createElement(
                                            'ul',
                                            null,
                                            abas
                                        )
                                    )
                                ),
                                React.createElement(
                                    'div',
                                    { className: 'col-md-9' },
                                    React.createElement(
                                        'div',
                                        { className: 'tab-content', id: 'v-pills-tabContent' },
                                        abasConteudo
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );
    }

}