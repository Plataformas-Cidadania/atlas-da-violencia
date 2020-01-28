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

      airData: null,

      airDataMonth: null,
      airDataHour: null,
      airDataTenHours: null,

      airDataChart: null,
      airDataChartY: null,
      airDataMonthChart: null,
      airDataMonthChartY: null,
      airDataHourChart: null,
      airDataHourChartY: null,
      airDataTenHoursChart: null,

      labelsDiaria: null,
      labelsMonth: null,
      labelsHour: null,
      labelsTenHours: [1, 10],

      diaSemanaSelecionado: 0
    };

    this.loadMap = this.loadMap.bind(this);
    this.refreshMarkers = this.refreshMarkers.bind(this);
    this.refreshMarkersEstacoes = this.refreshMarkersEstacoes.bind(this);
    this.slecionarDiaSemana = this.slecionarDiaSemana.bind(this);
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
          var d = new Date();
          this.slecionarDiaSemana(d.getDay());
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

    let polylineOptions = {
      color: 'grey',
      weight: 6,
      opacity: 0.9
    };

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

    var markerOn = new LeafIcon({ iconUrl: 'img/air-marker.png' });
    var markerOff = new LeafIcon({ iconUrl: 'img/marker-off.png' });
    ///////////////ICONE/////////////////

    //////////////MARKERS///////////////
    let pontos = [];
    //console.log(data['air']["veiculos"]);

    for (let i in data['air']) {
      let marker = markerOn;

      L.marker([data["air"][i]["latitude"], data["air"][i]["longitude"]], { icon: marker }).bindPopup('<b>' + data["air"][i]["trajeto"] + '</b><br>' + 'Data Hora: ' + data["air"][i]["data_hora"] + '<br>' + 'Linha: ' + data["air"][i]["linha"] + '<br>' + 'Sentido: ' + data["air"][i]["sentido"] + '<br>' + 'Código: ' + data["air"][i]["codigo"] + '<br>' + 'Latitude: ' + data["air"][i]["latitude"] + '<br>' + 'Longitude: ' + data["air"][i]["longitude"] + '<br>').addTo(markers);
    }

    markers.addTo(map);

    var overlayMaps = {
      "<img src='img/icon-air.jpg'> ": markers
    };

    L.control.layers(null, overlayMaps, { collapsed: false }).addTo(map);

    markers.addTo(map);

    //////////////MARKERS///////////////

    let airDataChart = [];
    let airDataChartY = [];
    let airDataMonthChart = [];
    let airDataMonthChartY = [];
    let airDataHourChart = [];
    let airDataHourChartY = [];
    let airDataTenHoursChart = [];

    let labelsDiaria = [];
    let labelsMonth = [];
    let labelsHour = [];
    let labelsTenHours = [];

    //console.log(labelsTenHours);

    for (let i in data.airData.series) {
      airDataChart.push(data.airData.series[i]);
    }

    for (let i in data.airData.yaxis) {
      airDataChartY.push(data.airData.yaxis[i]);
    }

    for (let i in data.airDataMonth.series) {
      airDataMonthChart.push(data.airDataMonth.series[i]);
    }

    for (let i in data.airDataMonth.yaxis) {
      airDataMonthChartY.push(data.airDataMonth.yaxis[i]);
    }

    for (let i in data.airDataHour) {
      if (!(i in airDataHourChart)) {
        airDataHourChart[i] = {
          label: data.airDataHour[i].label,
          series: [],
          yaxis: []
        };
      }
      for (let j in data.airDataHour[i].series) {
        airDataHourChart[i]['series'].push(data.airDataHour[i].series[j]);
      }
      for (let j in data.airDataHour[i].yaxis) {
        airDataHourChart[i]['yaxis'].push(data.airDataHour[i].yaxis[j]);
      }
    }

    //console.log('dados semana', data.airDataHour);
    //console.log('grafico semana', airDataHourChart);

    for (let i in data.airDataTenHours.series) {
      airDataTenHoursChart.push(data.airDataTenHours.series[i]);
    }

    labelsDiaria = data.airData.label;
    labelsMonth = data.airDataMonth.label;
    //labelsHour = data.airDataHour.label;
    labelsTenHours = data.airDataTenHours.label;

    ///////////////////////////////////////////////////////////////////////////////

    this.setState({
      mymap: map,
      airDataChart: airDataChart,
      airDataChartY: airDataChartY,
      airDataMonthChart: airDataMonthChart,
      airDataMonthChartY: airDataMonthChartY,
      airDataHourChart: airDataHourChart,
      airDataHourChartY: airDataHourChartY,
      airDataTenHoursChart: airDataTenHoursChart,

      labelsDiaria: labelsDiaria,
      labelsMonth: labelsMonth,
      labelsHour: labelsHour,
      labelsTenHours: labelsTenHours,

      markers: markers
    });
  }

  refreshMarkersEstacoes(data) {
    let map = this.state.mymap;
    let markers = L.layerGroup();

    let linhas = {};

    let overlayMaps = {};

    for (let linha in data.linhas) {
      linhas[linha] = L.layerGroup();
      linhas[linha].addTo(map);

      overlayMaps["<img src='imagens/linhas/" + data.linhas[linha].imagem + "' width='35'> "] = linhas[linha];
    }
    //console.log(linhas);
    ///////////////ICONE/////////////////
    var LeafIcon = L.Icon.extend({
      options: {
        iconSize: [40, 44]
      }
    });

    ///////////////ICONE/////////////////

    //////////////MARKERS///////////////

    let pontos = [];

    for (let linha in data.linhas) {
      let marker = new LeafIcon({ iconUrl: 'imagens/linhas_icones/' + data.linhas[linha].icone });

      for (let i in data.linhas[linha].estacoes.features) {
        let m = L.marker([data.linhas[linha].estacoes["features"][i]["geometry"]["coordinates"][1], data.linhas[linha].estacoes["features"][i]["geometry"]["coordinates"][0]], { icon: marker }).bindPopup('<b>' + data.linhas[linha].estacoes["features"][i]["properties"]["titulo"] + '</b><br>' + 'Status: ' + data.linhas[linha].estacoes["features"][i]["properties"]["ativo"] + '<br>');
        //console.log(m);
        m.addTo(linhas[linha]);
        pontos.push([data.linhas[linha].estacoes["features"][i]["geometry"]["coordinates"][1], data.linhas[linha].estacoes["features"][i]["geometry"]["coordinates"][0]]);
      }
    }

    L.control.layers(null, overlayMaps, { collapsed: false }).addTo(map);
    let bounds = new L.LatLngBounds(pontos);
    map.fitBounds(bounds);
    //////////////MARKERS///////////////

    this.setState({ mymap: map, markers: markers });
  }

  slecionarDiaSemana(dia) {
    this.setState({ diaSemanaSelecionado: dia });
  }

  render() {

    let airs = null;

    //DIA ATUAL DA SEMANA/////////////////////////////
    var semana = ["DOM", "SEG", "TER", "QUA", "QUI", "SEX", "SAB"];
    var d = new Date();
    let diaSemana = semana[d.getDay()];

    //criar array e htmls vazios para os gráficos de dias da semana
    let emptyDiasSemana = [];
    for (let i = 0; i < 7; i++) {
      emptyDiasSemana.push({ data: [], name: "", type: "bar" });
    }
    let weekCharts = null;
    weekCharts = emptyDiasSemana.map(function (item, index) {
      return React.createElement(
        'div',
        { key: 'mix-chart-week-' + index, style: { display: index == this.state.diaSemanaSelecionado ? '' : 'none' } },
        React.createElement(MixedChart, { id: 'mix-chart-week-' + index, yaxis: [], series: [], labels: [1, 10] })
      );
    }.bind(this));

    if (this.state.data) {

      airs = this.state.data["air"].map(function (item, index) {

        return React.createElement(
          'tr',
          { key: 'tabela' + index },
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
            item['velocidade'],
            'km/h'
          )
        );
      });

      if (this.state.airDataHourChart) {
        weekCharts = this.state.airDataHourChart.map(function (item, index) {
          //console.log('airDataHourChart', index, item);

          return React.createElement(
            'div',
            { key: 'mix-chart-week-' + index, style: { display: index == this.state.diaSemanaSelecionado ? '' : 'none' } },
            React.createElement(MixedChart, { id: 'mix-chart-week-' + index, yaxis: item['yaxis'], series: item['series'], labels: item['label'] })
          );
        }.bind(this));
      }
    }

    return React.createElement(
      'div',
      null,
      React.createElement('div', { id: this.props.mapId, style: { height: '600px' } }),
      React.createElement(
        'div',
        { className: 'container' },
        React.createElement(
          'div',
          { className: 'row' },
          React.createElement(
            'div',
            { className: 'col-md-12' },
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement(
              'h4',
              null,
              'Quantidade diaria e velocidade'
            ),
            React.createElement(MixedChart, { id: 'mix-chart1', yaxis: this.state.airDataChartY, series: this.state.airDataChart, labels: this.state.labelsDiaria })
          )
        ),
        React.createElement(
          'div',
          { className: 'row' },
          React.createElement(
            'div',
            { className: 'col-md-7' },
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement(
              'h4',
              null,
              'Quantidade Mensal e velocidade'
            ),
            React.createElement(MixedChart, { id: 'mix-chart2', yaxis: this.state.airDataMonthChartY, series: this.state.airDataMonthChart, labels: this.state.labelsMonth })
          ),
          React.createElement(
            'div',
            { className: 'col-md-5' },
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement(
              'h4',
              null,
              'Quantidade nas \xFAltimas 6 horas '
            ),
            React.createElement(GroupedBarChart, { id: 'groupe-bar-chart1', series: this.state.airDataTenHoursChart, labels: this.state.labelsTenHours })
          )
        ),
        React.createElement(
          'div',
          { className: 'row' },
          React.createElement(
            'div',
            { className: 'col-md-12' },
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement(
              'h4',
              null,
              'Quantidade por hora e media de velocidade '
            ),
            React.createElement('br', null),
            React.createElement(
              'ul',
              { className: 'menu-center' },
              React.createElement(
                'li',
                { onClick: () => this.slecionarDiaSemana(0), style: { fontWeight: diaSemana == 'DOM' ? 'bold' : 'inherit' } },
                'DOM'
              ),
              React.createElement(
                'li',
                { onClick: () => this.slecionarDiaSemana(1), style: { fontWeight: diaSemana == 'SEG' ? 'bold' : 'inherit' } },
                'SEG'
              ),
              React.createElement(
                'li',
                { onClick: () => this.slecionarDiaSemana(2), style: { fontWeight: diaSemana == 'TER' ? 'bold' : 'inherit' } },
                'TER'
              ),
              React.createElement(
                'li',
                { onClick: () => this.slecionarDiaSemana(3), style: { fontWeight: diaSemana == 'QUA' ? 'bold' : 'inherit' } },
                'QUA'
              ),
              React.createElement(
                'li',
                { onClick: () => this.slecionarDiaSemana(4), style: { fontWeight: diaSemana == 'QUI' ? 'bold' : 'inherit' } },
                'QUI'
              ),
              React.createElement(
                'li',
                { onClick: () => this.slecionarDiaSemana(5), style: { fontWeight: diaSemana == 'SEX' ? 'bold' : 'inherit' } },
                'SEX'
              ),
              React.createElement(
                'li',
                { onClick: () => this.slecionarDiaSemana(6), style: { fontWeight: diaSemana == 'SAB' ? 'bold' : 'inherit' } },
                'SAB'
              )
            ),
            React.createElement('br', null),
            weekCharts
          )
        ),
        React.createElement('br', null),
        React.createElement(
          'div',
          { className: 'text-center' },
          React.createElement(
            'h2',
            null,
            'AIRs'
          ),
          React.createElement(
            'p',
            null,
            'Nessa \xE1rea voc\xEA consegue acompanha em tempo real a situa\xE7\xE3o do AIRs'
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
                    )
                  )
                ),
                React.createElement(
                  'tbody',
                  null,
                  airs
                )
              )
            )
          )
        )
      )
    );
  }

}