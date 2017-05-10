class Map extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: this.props.id,
            min: 0,
            max: 0,
            periodo: 0,
            legend: [],
            indexLegend: 1,
            lastIndexLegend: 0,
            carregado: false
        };
        this.loadData = this.loadData.bind(this);
        this.loadMap = this.loadMap.bind(this);
        this.highlightFeature = this.highlightFeature.bind(this);
        this.resetHighlight = this.resetHighlight.bind(this);
        this.zoomToFeature = this.zoomToFeature.bind(this);
        this.onEachFeature = this.onEachFeature.bind(this);

    }

    componentDidMount(){
        this.setState({mymap: L.map(this.props.mapId).setView([-14, -52], 4)}, function(){
            let tileLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiYnJwYXNzb3MiLCJhIjoiY2l4N3l0bXF0MDFiczJ6cnNwODN3cHJidiJ9.qnfh8Jfn_be6gpo774j_nQ', {
                maxZoom: 18,
                attribution: '<div class="print-off" style="float:right;">&nbsp;Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="http://mapbox.com">Mapbox</a></div>',
                /*id: 'mapbox.streets'*/
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
            let _this = this;
            this.state.info.update = function (props) {
                //console.log('info', props);

                let sigla = '';
                if(props){
                    if(props.sigla!==props.nome){
                        sigla = props.sigla + ' - ';
                    }
                }

                this._div.innerHTML =
                    '<h4>Ocorrências</h4>' +  (props ? '<b>' + sigla + props.nome + '</b><br />' + formatNumber(props.total, _this.props.decimais, ',', '.')
                        : 'Passe o mouse na região');
            };
            this.state.info.addTo(this.state.mymap);
        });
    }

    componentWillReceiveProps(props){
        if(this.state.periodo != props.periodo){
            this.setState({periodo: props.periodo}, function(){
                this.loadData();
            });
        }
    }

    loadData(){
        //console.log('Map - loadData', this.props.typeRegion, this.props.typeRegionSerie);
        let _this = this;
        $.ajax("regiao/"+_this.state.id+"/"+_this.state.periodo+"/"+_this.props.regions+"/"+_this.props.abrangencia, {
            data: {},
            success: function(data){
               console.log('map - loadData',data);
                _this.loadMap(data);
            },
            error: function(data){
               //console.log('map', 'erro');
            }
        })
    }


    loadMap(data){


        //console.log('map', data);
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

        let intervalos = gerarIntervalos(valores);
        //console.log('map - intervalos', intervalos);
        this.props.setIntervalos(intervalos);

        this.setState(
            {
                geojson: L.geoJson(data, {
                    style: function(feature) {
                        return {
                            fillColor: getColor(feature.properties.total, intervalos),
                            weight: 2,
                            opacity: 1,
                            color: 'white',
                            dashArray: '3',
                            fillOpacity: 0.9
                        };
                    },
                    onEachFeature: this.onEachFeature //listeners
                }).addTo(this.state.mymap),
                area: data.bounding_box_total
        });

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

        let legend = this.state.legend;
        let indexLegend = this.state.indexLegend;
        let lastIndexLegend = this.state.lastIndexLegend;

        legend[indexLegend] = L.control({position: 'bottomright'});


        legend[indexLegend].onAdd = function (mymap) {
           //console.log('map - intervalos', intervalos);
            let div = L.DomUtil.create('div', 'info legend'),
                //grades = [0, 100, 300, 600, 1000, 1500, 3000, 5000, 7000, 9000],
                grades = intervalos,
                labels = [];
            // loop through our density intervals and generate a label with a colored square for each interval
            for (let i = 0; i < grades.length; i++) {
                div.innerHTML +=
                    '<i style="background:' + getColor(grades[i] + 1, intervalos) + '"></i> ' +
                    formatNumber(grades[i], this.props.decimais, ',', '.') +
                    (grades[i + 1] ? '&nbsp;&ndash;&nbsp;' + formatNumber(grades[i + 1], this.props.decimais, ',', '.') + '<br>' : '+');
            }
            return div;
        }.bind(this, intervalos);

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

        /*var polygon = L.polygon([
            [51.509, -0.08],
            [51.503, -0.06],
            [52.51, -0.047]
        ]).addTo(this.state.mymap);*/

        let area = data.bounding_box_total;

        console.log('area', area);

        let new_area = [];
        let count_area = 0;

        area.find(function(item){
            item.find(function(point){
                //console.log(point);
                new_area[count_area] = [];
                new_area[count_area][0] = point[1];
                new_area[count_area][1] = point[0];
                //let marker = L.marker(new_area[count_area]).addTo(this.state.mymap);
                count_area++;
            }.bind(this));
        }.bind(this));

        //let polygonArea = L.polygon(new_area).addTo(this.state.mymap);
        //let centerPolygon = polygonArea.getCenter();
        //let center = [centerPolygon.lat, centerPolygon.lng];
        //console.log('center', center);
        //let marker = L.marker(center).addTo(this.state.mymap);

        console.log('new_area', new_area);
        this.state.mymap.fitBounds(new_area);


        /*area.find(function(item){
            item.find(function(it){
                it.find(function(point){
                    console.log(point);
                    new_area[count_area] = [];
                    new_area[count_area][0] = point[1];
                    new_area[count_area][1] = point[0];
                    let marker = L.marker(new_area[count_area]).addTo(this.state.mymap);
                }.bind(this));
            }.bind(this));
        }.bind(this));*/





        //let point = [];

        //point[0] = area[0][0][0][1];
        //point[1] = area[0][0][0][0];



        //console.log('point', point);
        //console.log('area', area);

        //let marker = L.marker(point).addTo(this.state.mymap);
        //let marker2 = L.marker(area[0][0][0]).addTo(this.state.mymap);
        //let marker3 = L.marker([-14, -52]).addTo(this.state.mymap);



        this.setState({indexLegend: indexLegend, lastIndexLegend: lastIndexLegend, legend: legend});

    }

    highlightFeature(e) {
        //console.log(e);
        var layer = e.target;
        layer.setStyle({
            weight: 2,
            color: '#333',
            dashArray: '',
            fillOpacity: 1
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
        return (
            <div>
                <div style={{textAlign: 'center', clear: 'both'}}>
                    <button className="btn btn-primary btn-lg bg-pri" style={{border:'0'}}>{this.state.periodo}</button>
                    <div style={{marginTop:'-19px'}}>
                        <i className="fa fa-sort-down fa-2x" style={{color:'#3498DB'}} />
                    </div>
                </div>
                <br/>
                <div id={this.props.mapId} className="map"></div>
                {/*<br/>
                <div style={{float: 'right', marginLeft:'5px'}}>
                    <Download btnDownload={"download"+this.props.mapId} divDownload={this.props.mapId} arquivo="mapa.png"/>
                </div>
                <div style={{float: 'right', marginLeft:'5px'}}>
                    <Print divPrint="listValoresSeries" imgPrint="imgPrintList"/>
                </div>
                <div style={{clear: 'both'}}/>*/}
            </div>

        );
    }
}
