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
            colors: ['black', 'orange', 'blue', 'green'],
            transportes: null,
            matrizTransportes: null,
            colorsChart: ['#AC74AC', '#4DA6FF', '#7568EC', '#EC7F46', '#E01747', '#D9A300', '#226FB3', '#EDB621', '#698F36'],
            selectedStations: ['aeroporto', 'barca'],
        };

        this.loadMap = this.loadMap.bind(this);
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

        map.attributionControl.setPrefix(''); // Don't show the 'Powered by Leaflet' text.

        this.setState({mymap: map}, function(){
            if(this.state.data){
                this.refreshMarkersEstacoes(this.state.data);
            }

        });

    }


    refreshMarkersEstacoes(data){

        console.log(data);

        let map = this.state.mymap;
        let markers = L.layerGroup();


        let linhas = {};

        for(let k in this.state.data){
            linhas[k] = L.layerGroup();
            //console.log(k);
        }


        ///////////////ICONE/////////////////
        var LeafIcon = L.Icon.extend({
            options: {
                iconSize:     [40, 44],
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

        for(let transporte in data){

            let existe = false;
            for(let i in tiposTransportes){
                if(tiposTransportes[i].tipo === data[transporte].properties.tipo){
                    tiposTransportes[i]['qtd'] += data[transporte]['features'].length;
                    existe = true;
                }
            }

            if(!existe){
                tiposTransportes.push({
                    'tipo': data[transporte].properties.tipo,
                    'qtd': data[transporte].features.length
                });
            }
        }

        for(let i in tiposTransportes){
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

        for(let k in data){
            //console.log(data[k]['properties'].icone);

            console.log(data[k]);
            var markerTerminais = L.icon({
                iconUrl: 'imagens/transportes_icones/'+data[k]['properties'].icone
            });

            overlayMaps["<img src='imagens/transportes/"+data[k]['properties'].imagem+"' width='40' alt='"+k+"' title='"+k+"'> "] = linhas[k];

            L.geoJson(data[k], {
                pointToLayer: function(feature, latlng) {
                    return L.marker(latlng, {
                        icon: markerTerminais
                    });
                }.bind(this),
                onEachFeature: function (f, l) {
                    l.bindPopup('<b>'+JSON.stringify(f.properties.titulo,null,' ').replace(/[\{\}"]/g,'')+'</b>');
                }
            }).addTo(linhas[k])

            if(this.state.selectedStations.includes(k)){
                linhas[k].addTo(map);
            }

            if(k !== 'bicicletario'){
                transportes.titles.push(data[k].properties.titulo);
                transportes.values.push(data[k].features.length);
            }

            for(let i in data[k]['features']){
                pontos.push([data[k]["features"][i]["geometry"]["coordinates"][1], data[k]["features"][i]["geometry"]["coordinates"][0]]);
            }

        }

        //console.log(transportes);

        L.control.layers(null, overlayMaps,{collapsed:false}).addTo(map);

        //let bounds = new L.LatLngBounds(pontos);


        let bounds = new L.LatLngBounds(pontos);
        map.fitBounds(bounds);
        //////////////MARKERS///////////////

        this.setState({mymap: map, markers: markers, transportes: transportes, matrizTransportes: matrizTransportes});

    }


    render(){

        let abasArray = [];
        let abas = null;
        let abasConteudo = null;
        let icones = null;
        let populacaoTransporte = null;
        let potulacao =  6688927;

        if(this.state.data){
            for(let k in this.state.data){
                //console.log(this.state.data);

                //var dadosImg = this.state.data[k];

                let lista = this.state.data[k]["features"].map(function(item, index){
                    //console.log(this.state.data);
                    return (
                         <tr key={'lista'+index}>
                             <td>
                                 <h5><strong>{index+1} - {item.properties.titulo}</strong></h5>
                                 <p>{item.properties.endereco}</p>
                                 <p>{item.properties.telefone}</p>
                             </td>
                             <td>
                                 <img src="img/estacoes/metro.png" className={item.properties.metro== 1 ? "show icon-style" : "hidden"} width="20px" alt="Metro" title="Metro"/>
                                 <img src="img/estacoes/brt.png" className={item.properties.brt== 1 ? "show icon-style" : "hidden"} width="20px" alt="BRT" title="BRT"/>
                                 <img src="img/estacoes/trem.png" className={item.properties.trem== 1 ? "show icon-style" : "hidden"} width="20px" alt="Trem" title="Trem"/>
                                 <img src="img/estacoes/vlt.png" className={item.properties.vlt== 1 ? "show icon-style" : "hidden"} width="20px" alt="VLT" title="VLT"/>
                                 <img src="img/estacoes/barca.png" className={item.properties.barca== 1 ? "show icon-style" : "hidden"} width="20px" alt="Barca" title="Barca"/>
                                 <img src="img/estacoes/aeroporto.png" className={item.properties.aeroporto== 1 ? "show icon-style" : "hidden"} width="20px" alt="Aéroporto" title="Aéroporto"/>
                                 <img src="img/estacoes/bicicletario.png" className={item.properties.bicicletario== 1 ? "show icon-style" : "hidden"} width="20px" alt="Bicicletario" title="Bicicletario"/>
                             </td>
                         </tr>
                    );
                });

                abasArray.push({
                    'titulo': this.state.data[k]['properties']['titulo'],
                    'imagem': this.state.data[k]['properties']['imagem'],
                    'lista': lista
                });
            }

            abas = abasArray.map(function(item, index){
                return (
                    <li  key={'li'+index} className="btn btn-primary btn-pri"><a key={'aba'+index} className={"nav-link "+ (index == 0 ? 'active' : null )} id={"v-pills-"+item.titulo+"-tab"} data-toggle="pill"
                       href={"#v-pills-"+index} role="tab" aria-controls={"v-pills-"+item.titulo}
                       aria-selected="true">{item.titulo}</a></li>
                );
            });

            abasConteudo = abasArray.map(function(item, index){
                return (
                    <div key={'abas'+index} className={"tab-pane "+ (index == 0 ? 'active' : null )} id={"v-pills-"+index} role="tabpanel"
                         aria-labelledby={"v-pills-"+item.titulo+"-tab"}>
                            <table className="table">
                                <thead>
                                    <tr>
                                        <th>
                                            {item.titulo}
                                        </th>
                                        <th>Integrações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {item.lista}
                                </tbody>
                            </table>
                    </div>
                );
            });

            icones = abasArray.map(function(item, index){
                return (

                    <div  key={'icones'+index} className={"block col-md-3  "+ (index == 0 ? 'active' : null )}  data-move-y="200px" data-move-x="-200px">
                        <div className="text-center img-circle icons-circle">
                            <div className="img-circle icons-circle-int">
                                <img src={"imagens/transportes/"+item.imagem} width='50'/><br/>
                                {item.titulo}
                                <h2>{item.lista.length}</h2>
                            </div>
                            <br/><br/>
                        </div>

                    </div>
                );
            });
            populacaoTransporte = abasArray.map(function(item, index){
                //console.log('*** ', item);
                return (

                    <div  key={'populacao'+index} className={"col-md-3  "+ (index == 0 ? 'active' : null )}>

                            <div className="box-list bg-qui">
                                <img src={"imagens/transportes/"+item.imagem} width='50'/>
                                <img src={"img/pessoa.png"} width='50'/>
                                <h2><strong>{ Math.round(potulacao/item.lista.length)}</strong></h2>
                                <p className="tamanhoMinimo">Quantidade de pessoas por {item.titulo}</p>
                            </div>
                            <br/><br/>


                    </div>
                );
            });

        }




        return (
            <div>


                <div id={this.props.mapId} style={{height: '600px'}}/>

                <div className="container">

                    <br/> <br/> <br/>
                    <div className="row">
                        <div className="col-md-6 col-sm-12">
                            <h3>Terminais</h3><hr/><br/>
                            <BarChart id='bar-chart1' data={this.state.transportes} colors={this.state.colorsChart}/>
                    </div>
                        <div className="col-md-6 col-sm-12">
                            <h3>Matriz</h3><hr/><br/>
                            <BarChart id='bar-chart2' data={this.state.matrizTransportes} colors={this.state.colorsChart}/>
                        </div>
                    </div>
                </div>

                {/*<div className="container">

                    <br/> <br/> <br/>
                    <div className="row">
                        <div className="col-md-3">
                            <RadialChart />
                        </div>
                        <div className="col-md-3">
                            <RadialChart />
                        </div>
                        <div className="col-md-3">
                            <RadialChart />
                        </div>
                        <div className="col-md-3">
                            <RadialChart />
                        </div>
                    </div>
                </div>*/}



                <div className="container">
                    <br/><br/><br/>
                    <div className="row">
                        {icones}
                    </div>
                </div>

                <div className="container">
                    <br/>
                    <br/>
                    <br/>
                    <div className="text-center">
                        <h2>População</h2>
                        <p>Comparativo de quantidade de pessoas por estaqções de meio de transporte</p>
                        <hr/>
                    </div>
                    <br/>
                    <div className="row">
                        {populacaoTransporte}
                    </div>
                </div>

                <div className="container">
                    <br/>
                    <br/>
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

                                <div className="row">
                                    <div className="col-md-3">

                                        <div className="nav flex-column nav-pills" id="v-pills-tab" role="tablist"  aria-orientation="vertical">
                                            <ul>{abas}</ul>
                                        </div>


                                    </div>
                                    <div className="col-md-9">
                                        <div className="tab-content" id="v-pills-tabContent">
                                            {abasConteudo}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>



            </div>
        );




    }





}
