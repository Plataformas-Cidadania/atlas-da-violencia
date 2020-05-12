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
            radares: null,
            radaresGeral: null,
            matrizRadares: null,
            colorsChart: ['#AC74AC', '#4DA6FF', '#7568EC', '#EC7F46', '#E01747', '#D9A300', '#226FB3', '#EDB621', '#698F36'],
            selectedRadares: ['fixo']
        };

        this.loadMap = this.loadMap.bind(this);
        this.refreshMarkersRadares = this.refreshMarkersRadares.bind(this);
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
                this.refreshMarkersRadares(this.state.data);
            }
        });
    }

    refreshMarkersRadares(data) {

        //console.log(data);

        let map = this.state.mymap;
        //let markers = L.layerGroup();

        let markers = L.markerClusterGroup();

        let linhas = {};

        for (let k in this.state.data[0]) {
            //linhas[k] = L.layerGroup();
            linhas[k] = L.markerClusterGroup();

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

        //let matrizRadares = [];

        let overlayMaps = {};
        let radares = {
            titles: [],
            values: []
        };

        let matrizRadares = {
            titles: [],
            values: []
        };

        let tiposRadares = [];

        for (let radar in data) {

            let existe = false;
            /*for(let i in tiposRadares){
                if(tiposRadares[i].tipo === data[radar].properties.tipo){
                    tiposRadares[i]['qtd'] += data[radar]['features'].length;
                    existe = true;
                }
            }
              if(!existe){
                tiposRadares.push({
                    'tipo': data[radar].properties.tipo,
                    'qtd': data[radar].features.length
                });
            }*/
        }

        for (let i in tiposRadares) {
            matrizRadares['titles'].push(tiposRadares[i].tipo);
            matrizRadares['values'].push(tiposRadares[i].qtd);
        }

        for (let k in data[0]) {

            var markerTerminais = L.icon({
                iconUrl: 'imagens/radares_icones/' + data[0][k]['properties'].icone,
                iconSize: [38, 38]
            });

            overlayMaps["<img src='imagens/radares/" + data[0][k]['properties'].imagem + "' width='40' alt='" + k + "' title='" + k + "'> "] = linhas[k];

            L.geoJson(data[0][k], {
                pointToLayer: function (feature, latlng) {
                    return L.marker(latlng, {
                        icon: markerTerminais
                    });
                }.bind(this),
                onEachFeature: function (f, l) {
                    l.bindPopup('<div style="text-align:center; width: 100%; border-bottom: solid 1px #CCCCCC; padding-bottom: 5px; margin-bottom: 5px;"><b>' + JSON.stringify(f.properties.titulo, null, ' ').replace(/[\{\}"]/g, '') + '</b></div>' + 'Velocidade: ' + f.properties.velocidade + '<br/>' + 'Sentido Duplo: ' + f.properties.sentido_duplo + '<br/>' + 'Sentido Todos: ' + f.properties.sentido_todos + '<br/>' + 'Direção real: ' + f.properties.direcao_real + '<br/>' + 'Sigla Rodovia: ' + f.properties.sigla_rodovia + '<br/>' + 'Km Rodovia: ' + f.properties.km_rodovia + '<br/>' + 'Status: ' + f.properties.status + '<br/>' + 'Longitude: ' + f.geometry.coordinates[0] + '<br/>' + 'Latitude: ' + f.geometry.coordinates[1] + '<br/><br/>');
                }
            }).addTo(linhas[k]);

            if (this.state.selectedRadares.includes(k)) {
                linhas[k].addTo(map);
            }

            //console.log(data[0][k].properties);

            radares.titles.push(data[0][k].properties.titulo);
            radares.values.push(data[0][k].features.length);

            for (let i in data[0][k]['features']) {
                pontos.push([data[0][k]["features"][i]["geometry"]["coordinates"][1], data[0][k]["features"][i]["geometry"]["coordinates"][0]]);
            }
        }

        let radaresGeral = {
            titles: [],
            values: []
        };

        for (let k in data[1]) {

            //console.log(data[1][k].velocidade);

            radaresGeral.titles.push(data[1][k].velocidade);
            radaresGeral.values.push(data[1][k].qtd);
        }

        console.log(radaresGeral);

        L.control.layers(null, overlayMaps, { collapsed: false }).addTo(map);

        //let bounds = new L.LatLngBounds(pontos);


        let bounds = new L.LatLngBounds(pontos);
        map.fitBounds(bounds);
        //////////////MARKERS///////////////

        this.setState({ mymap: map, markers: markers, radares: radares, radaresGeral: radaresGeral, matrizRadares: matrizRadares });
    }

    render() {

        let icones = null;
        let tabela = null;
        let rows = null;

        if (this.state.data) {

            rows = this.state.data[1].map(function (item, index) {
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
                                index + 1
                            )
                        )
                    ),
                    React.createElement(
                        'td',
                        null,
                        React.createElement(
                            'p',
                            null,
                            item.velocidade,
                            ' km'
                        )
                    ),
                    React.createElement(
                        'td',
                        null,
                        React.createElement(
                            'p',
                            null,
                            item.qtd
                        )
                    )
                );
            });

            tabela = React.createElement(
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
                            'N\xBA'
                        ),
                        React.createElement(
                            'th',
                            null,
                            'Limite regulamentado'
                        ),
                        React.createElement(
                            'th',
                            null,
                            'Quantidade'
                        )
                    )
                ),
                React.createElement(
                    'tbody',
                    null,
                    rows
                )
            );

            /*icones = this.state.data[0].map(function(item, index){
                return (
                      <div  key={'icones'+index} className={"block col-md-3  "+ (index == 0 ? 'active' : null )}  data-move-y="200px" data-move-x="-200px">
                        <div className="text-center img-circle icons-circle">
                            <div className="img-circle icons-circle-int">
                                <img src={"imagens/radares/"+item.imagem} width='50'/><br/>
                                {item.titulo}
                                <h2>{item.lista.length}</h2>
                            </div>
                            <br/><br/>
                        </div>
                      </div>
                );
            });*/
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
                        { className: 'col-md-12 text-center' },
                        React.createElement(
                            'h3',
                            null,
                            'Tipo de radares'
                        ),
                        React.createElement('hr', null),
                        React.createElement('br', null)
                    ),
                    React.createElement(
                        'div',
                        { className: 'col-md-6 col-sm-12' },
                        React.createElement(BarChart, { id: 'bar-chart1', data: this.state.radares, colors: this.state.colorsChart })
                    ),
                    React.createElement(
                        'div',
                        { className: 'col-md-6 col-sm-12 text-center' },
                        React.createElement(
                            'div',
                            { style: { margin: '50px auto' } },
                            React.createElement(PieChart, { id: 'pie-chart1', data: this.state.radares })
                        )
                    )
                ),
                React.createElement('br', null),
                ' ',
                React.createElement('br', null),
                ' ',
                React.createElement('br', null),
                React.createElement('br', null),
                ' ',
                React.createElement('br', null),
                React.createElement(
                    'div',
                    { className: 'row' },
                    React.createElement(
                        'div',
                        { className: 'col-md-12 text-center' },
                        React.createElement(
                            'h3',
                            null,
                            'Dados por velocidade'
                        ),
                        React.createElement('hr', null),
                        React.createElement('br', null)
                    ),
                    React.createElement(
                        'div',
                        { className: 'col-md-6 col-sm-12' },
                        React.createElement(BarChart, { id: 'bar-chart2', data: this.state.radaresGeral, colors: this.state.colorsChart })
                    ),
                    React.createElement(
                        'div',
                        { className: 'col-md-6 col-sm-12 text-center' },
                        React.createElement(
                            'div',
                            { style: { margin: '50px auto' } },
                            React.createElement(PieChart, { id: 'pie-chart2', data: this.state.radaresGeral })
                        )
                    )
                )
            ),
            React.createElement(
                'div',
                { className: 'container' },
                React.createElement('br', null),
                React.createElement('br', null),
                React.createElement('br', null),
                React.createElement('br', null),
                React.createElement('br', null),
                React.createElement(
                    'div',
                    { className: 'text-center' },
                    React.createElement(
                        'h2',
                        null,
                        'Tabela'
                    ),
                    React.createElement(
                        'p',
                        null,
                        'Nessa \xE1rea voc\xEA encontra dados consolidados dos radares'
                    ),
                    React.createElement('hr', null)
                ),
                React.createElement('br', null),
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
                                    { className: 'col-md-12' },
                                    React.createElement(
                                        'div',
                                        { className: 'tab-content', id: 'v-pills-tabContent' },
                                        tabela
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