class Map extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: this.props.id,
            min: 0,
            max: 0,
        };
        this.loadData = this.loadData.bind(this);
        this.loadMap = this.loadMap.bind(this);
        this.highlightFeature = this.highlightFeature.bind(this);
        this.resetHighlight = this.resetHighlight.bind(this);
        this.zoomToFeature = this.zoomToFeature.bind(this);
        this.onEachFeature = this.onEachFeature.bind(this);

    }

    componentDidMount(){
        this.setState({mymap: L.map('mapid').setView([-10, -52], 4)}, function(){
            let tileLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiYnJwYXNzb3MiLCJhIjoiY2l4N3l0bXF0MDFiczJ6cnNwODN3cHJidiJ9.qnfh8Jfn_be6gpo774j_nQ', {
                maxZoom: 18,
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                id: 'mapbox.streets'
            }).addTo(this.state.mymap);

            this.makeInfo();

        });
    }

    makeInfo(){
        this.setState({info: L.control()}, function(){
            this.state.info.onAdd = function () {
                this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
                this.update();
                return this._div;
            };

            // method that we will use to update the control based on feature properties passed
            this.state.info.update = function (props) {
                //console.log('info', props);
                this._div.innerHTML =
                    '<h4>Ocorrências</h4>' +  (props ? '<b>' + props.uf + '</b><br />' + props.total
                        : 'Passe o mouse na região');
            };
            this.state.info.addTo(this.state.mymap);
        });
    }

    componentWillReceiveProps(props){
        if(this.state.min != props.min || this.state.max != props.max){
            this.setState({min: props.min, max: props.max}, function(){
                this.loadData();
            });
        }
    }

    loadData(){
        let _this = this;
        $.ajax("regiao/"+_this.state.id+"/"+_this.props.tipoValores+"/"+_this.state.min+"/"+_this.state.max, {
            data: {},
            success: function(data){
                //console.log(data);
                _this.loadMap(data);
            },
            error: function(data){
                console.log('erro');
            }
        })
    }

    loadMap(data){
        console.log('map', data);
        let _this = this;
        //remove existing map layers
        this.state.mymap.eachLayer(function(layer){
            //if not the tile layer
            if (typeof layer._url === "undefined"){
                _this.state.mymap.removeLayer(layer);
            }
        });

        let valores = [];
        let marcadores = [];
        for(let i in data.features){
            valores[i] = data.features[i].properties.total;


            /*marcadores[i] = {};
            marcadores[i].x = data.features[i].properties.x;
            marcadores[i].y = data.features[i].properties.y;
            marcadores[i].uf = data.features[i].properties.uf;
            marcadores[i].total = data.features[i].properties.total;
            var circle = L.marker([marcadores[i].y, marcadores[i].x], {
                icon: new L.DivIcon({
                    className: 'label-valor',
                    html: '<div>'+marcadores[i].total+'</div>'
                })
            }).addTo(_this.state.mymap);*/


        }
        //console.log(valores);

        intervalos = gerarIntervalos(valores);
        //console.log(intervalos);
        this.props.setIntervalos(intervalos);

        this.setState({geojson: L.geoJson(data, {
            style: style,
            onEachFeature: this.onEachFeature //listeners
        }).addTo(this.state.mymap)});

        /*let geojson = L.geoJson(data, {
            style: style,
            onEachFeature: this.onEachFeature //listeners
        }).addTo(this.state.mymap);*/

        /*for(var i=0; i<data.circles.length; i++){
         var circle = L.circle([data.circles[i].st_y, data.circles[i].st_x], {
         color: 'red',
         fillColor: '#f03',
         fillOpacity: 0.5,
         radius: data.circles[i].valor*10
         }).addTo(mymap);
         }*/

        legend[indexLegend] = L.control({position: 'bottomright'});


        legend[indexLegend].onAdd = function (mymap) {
            let div = L.DomUtil.create('div', 'info legend'),
                //grades = [0, 100, 300, 600, 1000, 1500, 3000, 5000, 7000, 9000],
                grades = intervalos,
                labels = [];
            // loop through our density intervals and generate a label with a colored square for each interval
            for (let i = 0; i < grades.length; i++) {
                div.innerHTML +=
                    '<i style="background:' + getColor(grades[i] + 1) + '"></i> ' +
                    grades[i] + (grades[i + 1] ? '&nbsp;&ndash;&nbsp;' + grades[i + 1] + '<br>' : '+');
            }
            return div;
        };

        if(lastIndexLegend!=0){
            this.state.mymap.removeControl(legend[lastIndexLegend]);
        }
        legend[indexLegend].addTo(this.state.mymap);
        lastIndexLegend = indexLegend;
        indexLegend++;

        /*for(var i in data){
         var circle = L.circle([data[i].st_y, data[i].st_x], {
         color: 'red',
         fillColor: '#f03',
         fillOpacity: 0.5,
         radius: 50000
         }).addTo(mymap);
         }*/

        let polygon2 = L.polygon([
            [51.509, -0.08],
            [51.503, -0.06],
            [51.51, -0.047]
        ]).addTo(this.state.mymap);

    }

    highlightFeature(e) {
        //console.log(e);
        var layer = e.target;
        layer.setStyle({
            weight: 5,
            color: '#333',
            dashArray: '',
            fillOpacity: 0.7
        });

        if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
            layer.bringToFront();
        }

        this.state.info.update(layer.feature.properties);
    }
    resetHighlight(e) {
        this.state.geojson.resetStyle(e.target);
        this.state.info.update();
    }
    zoomToFeature(e) {
        this.state.mymap.fitBounds(e.target.getBounds());
    }
    onEachFeature(feature, layer) {
        //console.log(layer);
        //console.log(this);
        layer.on({
            mouseover: this.highlightFeature,
            mouseout: this.resetHighlight,
            click: this.zoomToFeature
        });
    }


    render(){
        return (<div id="mapid"></div>);
    }
}
