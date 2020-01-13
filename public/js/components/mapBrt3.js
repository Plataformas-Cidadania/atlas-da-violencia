class Map extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            mymap: null,
            data: null,
            legend: [],
            indexLegend: 1,
            lastIndexLegend: 0,
            carregado: false
        };

        this.loadMap = this.loadMap.bind(this);
        this.refreshMarkers = this.refreshMarkers.bind(this);
    }

    componentDidMount() {
        this.setState({ mymap: L.map(this.props.mapId, {
                fullscreenControl: true,
                fullscreenControlOptions: { // optional
                    title: "Show me the fullscreen !",
                    titleCancel: "Exit fullscreen mode"
                }
            }).setView([-14, -52], 4) }, function () {

            this.loadMap();
        });
    }

    componentWillReceiveProps(props) {

        if (this.state.data != props.data) {
            this.setState({ data: props.data }, function () {

                if (this.state.data) {
                    //this.refreshMarkers(this.state.data);
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

        //console.log(this.state.data.estacoes.features);

        map.attributionControl.setPrefix(''); // Don't show the 'Powered by Leaflet' text.

        //console.log("A: "+this.state.data.estacoes.features[1]['geometry']['coordinates'][1]);

        //Define an array of Latlng objects (points along the line)

        for (let i in this.state.data.estacoes.features) {
            console.log("B: " + this.state.data.estacoes.features[i].geometry.coordinates[0]);
            console.log("C: " + this.state.data.estacoes.features[i].geometry.coordinates[1]);

            for (let i in this.state.data.estacoes.features) {
                var polylinePoints = [new L.LatLng(this.state.data.estacoes.features[i].geometry.coordinates[0], this.state.data.estacoes.features[i].geometry.coordinates[1]), new L.LatLng(-22.885923, -43.115279), new L.LatLng(-22.885638, -43.115334), new L.LatLng(-22.883899, -43.115719), new L.LatLng(-22.878828, -43.114530), new L.LatLng(-22.872676, -43.205319), new L.LatLng(-22.899125, -43.207882), new L.LatLng(-22.893682, -43.190791), new L.LatLng(-22.897019, -43.179726), new L.LatLng(-22.904090, -43.174590), new L.LatLng(-22.906861, -43.172895)];
            }
        }

        var polylineOptions = {
            color: 'blue',
            weight: 6,
            opacity: 0.9
        };

        var polyline = new L.Polyline(polylinePoints, polylineOptions);

        map.addLayer(polyline);

        // zoom the map to the polyline
        map.fitBounds(polyline.getBounds());

        this.setState({ mymap: map }, function () {
            if (this.state.data) {
                this.refreshMarkers(this.state.data);
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
        var markerOn = new LeafIcon({ iconUrl: 'img/marker.png' });
        var markerOff = new LeafIcon({ iconUrl: 'img/marker-off.png' });
        ///////////////ICONE/////////////////

        //////////////MARKERS///////////////
        let pontos = [];

        for (let i in data['brt']["veiculos"]) {
            let marker = markerOn;
            /*if(data['brt']["veiculos"][i][5]===0){
                marker = markerOff;
            }*/

            //console.log(data['brt']["veiculos"][1]);

            L.marker([data["brt"]["veiculos"][i]["latitude"], data["brt"]["veiculos"][i]["longitude"]], { icon: marker }).bindPopup('<b>' + data["brt"]["veiculos"][i]["trajeto"] + '</b><br>' + 'Data Hora: ' + data["brt"]["veiculos"][i]["dataHora"] + '<br>' + 'Linha: ' + data["brt"]["veiculos"][i]["linha"] + '<br>' + 'Sentido: ' + data["brt"]["veiculos"][i]["sentido"] + '<br>' + 'Código: ' + data["brt"]["veiculos"][i]["codigo"] + '<br>' + 'Latitude: ' + data["brt"]["veiculos"][i]["latitude"] + '<br>' + 'Longitude: ' + data["brt"]["veiculos"][i]["longitude"] + '<br>').addTo(markers);

            pontos.push([data["brt"]["veiculos"][i]["latitude"], data["brt"]["veiculos"][i]["longitude"]]);
        }
        markers.addTo(map);

        let bounds = new L.LatLngBounds(pontos);
        map.fitBounds(bounds);
        //////////////MARKERS///////////////

        this.setState({ mymap: map, markers: markers });
    }

    render() {
        return React.createElement(
            "div",
            null,
            React.createElement("div", { id: this.props.mapId, style: { height: '600px' } })
        );
    }
}