class Map extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            mymap: null,
            data: null,
            legend: [],
            indexLegend: 1,
            lastIndexLegend: 0,
            carregado: false,
            linhas: ['Brasil', 'Carioca', 'Oeste', 'Olimpica'],
            colors: ['black', 'orange', 'blue', 'green'],
        };

        this.loadMap = this.loadMap.bind(this);
        this.refreshMarkers = this.refreshMarkers.bind(this);
        this.refreshMarkersEstacoes = this.refreshMarkersEstacoes.bind(this);

    }

    componentDidMount(){
        this.setState({mymap: L.map(this.props.mapId, {
                fullscreenControl: true,
                fullscreenControlOptions: { // optional
                    title:"Show me the fullscreen !",
                    titleCancel:"Exit fullscreen mode"
                }
            }).setView([-14, -52], 4)}, function(){
        });
    }

    componentWillReceiveProps(props){

        if(this.state.data != props.data){
            this.setState({data: props.data}, function(){

                if(this.state.data){
                    this.loadMap();
                }
            });
        }

    }

    loadMap(){


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

            for(let i in linhas){
                polylinePoints[linhas[i]] = [];
            }

            for(let i in featuresEstacoes) {
                for(let j in linhas){
                    if(featuresEstacoes[i].properties['Flg_Trans'+linhas[j]]==="Sim"){
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

        for(let i in linhas){
            polylineOptions.color = this.state.colors[i];
            let polyline = new L.Polyline(polylinePoints[linhas[i]], polylineOptions);
            map.addLayer(polyline);
            // zoom the map to the polyline
            //map.fitBounds(polyline.getBounds());
        }

        this.setState({mymap: map}, function(){
            if(this.state.data){
                this.refreshMarkers(this.state.data);
                this.refreshMarkersEstacoes(this.state.data);

            }

        });

    }



    refreshMarkers(data){
        let map = this.state.mymap;
        //let markers = L.layerGroup();
        var markers = L.markerClusterGroup();

        ///////////////ICONE/////////////////
        var LeafIcon = L.Icon.extend({
            options: {
                iconSize:     [40, 44],
            }
        });

        //let velocidadeVia = 'img/radar-'+data["radar"][i]["VelocidadeVia"]+'.png';

        //console.log('Velocidade: '+velocidadeVia);

        //var markerOn = new LeafIcon({iconUrl: velocidadeVia});
        var markerOn = new LeafIcon({iconUrl: 'img/radar-70.png'});
        var markerOff = new LeafIcon({iconUrl: 'img/marker-off.png'});
        ///////////////ICONE/////////////////

        //////////////MARKERS///////////////
        let pontos = [];
        console.log(data['radar'][0]["Latitude"]);

        for(let i in data['radar']){
            //let marker = markerOn;
            let marker = new LeafIcon({iconUrl: 'img/radar-'+data["radar"][i]["VelocidadeVia"]+'.png'});

            L.marker([data["radar"][i]["Latitude"], data["radar"][i]["Longitude"]], {icon: marker}).bindPopup(
                '<b>'+data["radar"][i]["trajeto"]+'</b><br>'+
                'Ativo: '+data["radar"][i]["Ativo"]+'<br>'+
                'Velocidade Via: '+data["radar"][i]["VelocidadeVia"]+'<br>'+
                'Sentido: '+data["radar"][i]["Sentido"]+'<br>'+
                'TipoRadarID: '+data["radar"][i]["TipoRadarID"]+'<br>'
            ).addTo(markers);


        }
        markers.addTo(map);

        //////////////MARKERS///////////////

        this.setState({mymap: map, markers: markers});

    }

    refreshMarkersEstacoes(data){
        let map = this.state.mymap;
        let markers = L.layerGroup();

        let linhaCarioca = L.layerGroup();
        let linhaOeste = L.layerGroup();
        let linhaOlimpica = L.layerGroup();

        console.log(data);

        ///////////////ICONE/////////////////
        var LeafIcon = L.Icon.extend({
            options: {
                iconSize:     [40, 44],
            }
        });
        var markerCarioca = new LeafIcon({iconUrl: 'img/radar-estacao-carioca.png'});
        var markerOeste = new LeafIcon({iconUrl: 'img/radar-estacao-oeste.png'});
        var markerOlimpica = new LeafIcon({iconUrl: 'img/radar-estacao-olimpica.png'});
        var markerOff = new LeafIcon({iconUrl: 'img/radar-estacao-off.png'});
        ///////////////ICONE/////////////////

        let pontos = [];

        //////////////MARKERS///////////////

        for(let i in data['estacoes']["features"]){

            let marker = markerOff;
            if(data['estacoes']["features"][i]["properties"]["Flg_TransBrasil"]==="Sim"){
                //let marker = markerOff;
            }else if(data['estacoes']["features"][i]["properties"]["Flg_TransCarioca"]==="Sim"){
                marker = markerCarioca;
            }else if(data['estacoes']["features"][i]["properties"]["Flg_TransOeste"]==="Sim"){
                marker = markerOeste;
            }else if(data['estacoes']["features"][i]["properties"]["Flg_TransOlimpica"]==="Sim"){
                marker = markerOlimpica;
            }

            let m = L.marker([data['estacoes']["features"][i]["geometry"]["coordinates"][1], data['estacoes']["features"][i]["geometry"]["coordinates"][0]], {icon: marker}).bindPopup(
                '<b>'+data['estacoes']["features"][i]["properties"]["Nome"]+'</b><br>'+
                'Status: '+data['estacoes']["features"][i]["properties"]["Flg_Ativo"]+'<br>'+
                'Integra Aeroporto: '+data['estacoes']["features"][i]["properties"]["Integra_Aeroporto"]+'<br>'+
                'Integra Metro: '+data['estacoes']["features"][i]["properties"]["Integra_Metro"]+'<br>'+
                'Integra Trem: '+data['estacoes']["features"][i]["properties"]["Integra_Trem"]+'<br>'
            );

            if(data['estacoes']["features"][i]["properties"]["Flg_TransBrasil"]==="Sim"){
                //let marker = markerOff;
            }else if(data['estacoes']["features"][i]["properties"]["Flg_TransCarioca"]==="Sim"){
                m.addTo(linhaCarioca);
            }else if(data['estacoes']["features"][i]["properties"]["Flg_TransOeste"]==="Sim"){
                m.addTo(linhaOeste);
            }else if(data['estacoes']["features"][i]["properties"]["Flg_TransOlimpica"]==="Sim"){
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
            "<img src='/img/trans-olimpica.jpg'>": linhaOlimpica,
        };



        L.control.layers(null, overlayMaps).addTo(map);

        let bounds = new L.LatLngBounds(pontos);
        map.fitBounds(bounds);
        //////////////MARKERS///////////////

        this.setState({mymap: map, markers: markers});



    }

    render(){
        let estacoes = null;
        if(this.state.data){

            //console.log(this.state.data["estacoes"]["features"]);

            estacoes = this.state.data['estacoes']['features'].map(function(item){

                let flgTransBrasil =  item['properties']['Flg_TransBrasil'];
                let flgTransCarioca =  item['properties']['Flg_TransCarioca'];
                let flgTransOeste =  item['properties']['Flg_TransOeste'];
                let flgTransOlimpica =  item['properties']['Flg_TransOlimpica'];

                return (

                    <tr>
                        <td>
                            <div className={"linhas " + (flgTransBrasil == "Sim" ? "linha-trans-brasil" : "linha-off")}/>
                            <div className={"linhas " + (flgTransCarioca == "Sim" ? "linha-trans-carioca" : "linha-off")}/>
                            <div className={"linhas " + (flgTransOeste == "Sim" ? "linha-trans-oeste" : "linha-off")}/>
                            <div className={"linhas " + (flgTransOlimpica == "Sim" ? "linha-trans-olimpica" : "linha-off")}/>
                        </td>
                        <td>{item['properties']['Nome']}</td>
                        <td>{item['properties']['Integra_Metro']}</td>
                        <td>{item['properties']['Integra_Trem']}</td>
                        <td>{item['properties']['Integra_Aeroporto']}</td>
                        <td>{item['properties']['Flg_Ativo']}</td>
                        <td>{item['properties']['Status']}</td>
                    </tr>
                );
            });
        }

        let radars = null;
        if(this.state.data){



            //console.log(this.state.data["radar"]["veiculos"]);

            radars = this.state.data["radar"].map(function(item){


                /*let flgTransBrasil =  item['properties']['Flg_TransBrasil'];
                let flgTransCarioca =  item['properties']['Flg_TransCarioca'];
                let flgTransOeste =  item['properties']['Flg_TransOeste'];
                let flgTransOlimpica =  item['properties']['Flg_TransOlimpica'];*/

                return (

                    <tr>
                        <td>
                           {/* <div className={"linhas " + (flgTransBrasil == "Sim" ? "linha-trans-brasil" : "linha-off")}/>
                            <div className={"linhas " + (flgTransCarioca == "Sim" ? "linha-trans-carioca" : "linha-off")}/>
                            <div className={"linhas " + (flgTransOeste == "Sim" ? "linha-trans-oeste" : "linha-off")}/>
                            <div className={"linhas " + (flgTransOlimpica == "Sim" ? "linha-trans-olimpica" : "linha-off")}/>*/}
                            {item['linha']}
                        </td>
                        <td>{item['codigo']}</td>
                        <td>{item['trajeto']}</td>
                        <td>{item['sentido']}</td>
                        <td>{item['velocidade']}</td>
                        <td>{item['latitude']} - {item['longitude']}</td>
                    </tr>
                );
            });
        }


        return (
            <div>
                <div id={this.props.mapId} style={{height: '600px'}}/>

                <div className="container">
                    <br/>
                    <div className="text-center">
                        <h2>Estações</h2>
                        <p>Nessa área você consegue informações das estações e suas situações e integrações</p>
                        <hr/>
                    </div>
                    <br/>
                    <div className="row">
                        <div className="col-md-12">
                            <div className="table-responsive-sm">
                                <table className="table">
                                    <thead>
                                    <tr>
                                        <th>Linha</th>
                                        <th>Terminal</th>
                                        <th>Integra Metro</th>
                                        <th>Integra Trem</th>
                                        <th>Integra Aeroporto</th>
                                        <th>Ativo</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        {estacoes}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="container">
                    <br/>
                    <div className="text-center">
                        <h2>RADARs</h2>
                        <p>Nessa área você consegue acompanha em tempo real a situação do RADARs</p>
                        <hr/>
                    </div>
                    <br/>
                    <div className="row">
                        <div className="col-md-12">
                            <div className="table-responsive-sm">
                                <table className="table">
                                    <thead>
                                    <tr>
                                        <th>Linha</th>
                                        <th>Código</th>
                                        <th>Trajeto</th>
                                        <th>Sentido</th>
                                        <th>Velocidade</th>
                                        <th>Próximo</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {radars}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        );

    }





}
