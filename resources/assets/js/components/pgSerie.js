function Topico(props){
    return(
        <div>
            <br/>
            <div className="row">
                <div className="col-md-12">
                    <div className={"icons-groups "+props.icon} style={{float: 'left'}}>&nbsp;</div>
                    <h4 className="icon-text">&nbsp;&nbsp;{props.text}</h4>
                </div>
            </div>
            <hr style={{borderColor: '#3498DB'}}/>
            <br/>
        </div>
    );
}

class PgSerie extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: this.props.id,
            serie: this.props.serie,
            unidade: this.props.unidade,
            loading: false,
            intervalos: [],
            valoresRegioesPorPeriodo: {min: 0, max: 0, values: {}},
            smallLarge: [0,1],
            min: this.props.from,
            max: this.props.to,
            periodos: [],
            showMap: true,
            showCharts: true,
            showRegions: true,
            showTable: true,
            showCalcs: true,
            showInfo: true,
            chartLine: true,
            chartRadar: false,
            chartBar:false,
            chartPie:false
        };
        this.loading = this.loading.bind(this);
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
        this.showHide = this.showHide.bind(this);
        this.loadData = this.loadData.bind(this);
        this.setIntervalos = this.setIntervalos.bind(this);
        this.calcSmallLarge = this.calcSmallLarge.bind(this);

    }

    loading(status){
        this.setState({loading: status});
    }

    changePeriodo(min, max){
        this.setState({min: min, max: max}, function(){
           //console.log(min, max);
            this.loadData();
        });
    }

    setPeriodos(periodos){
        this.setState({periodos: periodos});
    }

    loadData(){
       //console.log(this.props.regions);
        $.ajax({
            method:'GET',
            //url: "valores-regiao/"+this.state.id+"/"+this.props.tipoValores+"/"+this.state.min+"/"+this.state.max,
            url: "valores-regiao/"+this.state.id+"/"+this.state.min+"/"+this.state.max+"/"+this.props.regions+"/"+this.props.abrangencia,
            //url: "valores-regiao/"+this.state.id+"/"+this.state.max,
            cache: false,
            success: function(data) {
               //console.log('pgSerie', data);
                //os valores menor e maior para serem utilizados no chartBar
                let smallLarge = this.calcSmallLarge(data.min.valores, data.max.valores);
                /*let totais = {
                    min: this.state.min,
                    max: this.state.max,
                    values: data
                };*/
                this.setState({valoresRegioesPorPeriodo: data, smallLarge: smallLarge});


                ///////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////
                /*let valores = [];
                for(let i in data.max.valores){
                    valores[i] = data.max.valores[i].valor;
                }
                //console.log('pgSerie', valores);
                let valoresOrdenados = valores.sort(function(a, b){
                    return a - b;
                });
                //console.log('pgSerie', valoresOrdenados);

                intervalos = gerarIntervalos(valoresOrdenados);
                this.setIntervalos(intervalos);
                //console.log('pgSerie', intervalos);*/
                ///////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////



                //loadMap(data);
            }.bind(this),
            error: function(xhr, status, err) {
              console.log('erro');
            }.bind(this)
        });
    }

    calcSmallLarge(minValores, maxValores){
        let valores = [];
        minValores.find(function(item){
            valores.push(parseFloat(item.valor));
        });
        maxValores.find(function(item){
            valores.push(parseFloat(item.valor));
        });
        let valoresSort = valores.sort(function(a, b){
            return a - b;
        });
        let smallLarge = [];
        smallLarge[0] = valoresSort[0]-10;
        if(valoresSort[0] > 0 && valoresSort[0] < 10){
            smallLarge[0] = valoresSort[0];
        }

        smallLarge[1] = valoresSort[valoresSort.length-1];
        return smallLarge;
    }

    changeChart(chart) {
        this.setState({chartLine:false, chartRadar:false, chartBar:false, chartPie:false}, function(){
            let chartChosen = {};
            chartChosen[chart] = true;
            this.setState(chartChosen);
        });
    }

    showHide(target){
        let value = this.state['show'+target];
        let inverseValue = !value;
        this.setState({['show'+target]:inverseValue});
    }

    setIntervalos(intervalos){
        this.setState({intervalos: intervalos});
    }

    render(){

        //utilizado para função de formatação
        let decimais = this.state.unidade==1 ? 0 : 2;

        return(
            <div>
                <div className="text-center" style={{display: this.state.loading ? 'block' : 'none'}}>
                    <br/><br/>
                    <i className="fa fa-5x fa-spinner fa-spin"> </i>
                </div>
                <div style={{visibility: this.state.loading ? 'disable' : 'enable'}}>
                    <div className="row">
                        <div className="col-md-6 h3" style={{margin:0}}>
                            <img style={{marginLeft: '5px'}} src="imagens/links/8516-01.png" width="52" alt="" title=""/>
                            &nbsp;{this.state.serie}
                        </div>
                        <div className="col-md-6 text-right hidden-print">
                            <div className="icons-groups icon-group-print" onClick={() => window.print()}
                                 style={{display: 'block', marginLeft: '5px'}} title="">&nbsp;</div>

                            <div className={"icons-groups" + (this.state.showInfo ? " icon-group-info" : " icon-group-info-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.showHide('Info')} title="">&nbsp;</div>

                            <div className={"icons-groups" + (this.state.showCalcs ? " icon-group-calc" : " icon-group-calc-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.showHide('Calcs')} title="">&nbsp;</div>

                            <div className={"icons-groups" + (this.state.showTable ? " icon-group-table" : " icon-group-table-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.showHide('Table')} title="">&nbsp;</div>

                            <div className={"icons-groups" + (this.state.showRegions ? " icon-group-rate" : " icon-group-rate-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.showHide('Regions')} title="">&nbsp;</div>

                            <div className={"icons-groups" + (this.state.showCharts ? " icon-group-chart" : " icon-group-chart-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.showHide('Charts')} title="">&nbsp;</div>

                            <div className={"icons-groups" + (this.state.showMap ? " icon-group-map" : " icon-group-map-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.showHide('Map')} title="">&nbsp;</div>
                        </div>
                    </div>
                    <br/>
                    <div className="line_title bg-pri"></div>

                    <div className="hidden-print">
                        <br/>

                        <RangePeriodo
                            id={this.state.id}
                            changePeriodo={this.changePeriodo}
                            setPeriodos={this.setPeriodos}
                            loading={this.loading}
                            from={this.props.from}
                            to={this.props.to}
                        />
                        <br/>


                    </div>


                    <div style={{display: this.state.showMap ? 'block' : 'none'}}>

                        <Topico icon="icon-group-map" text="Mapa"/>

                        <div className="row">
                            <div className="col-md-6">
                                <Map
                                    mapId="map1"
                                    id={this.state.id}
                                    tipoValores={this.props.tipoValores}
                                    decimais={decimais}
                                    /*min={this.state.min}
                                    max={this.state.max}*/
                                    periodo={this.state.min}
                                    setIntervalos={this.setIntervalos}
                                    regions={this.props.regions}
                                    abrangencia={this.props.abrangencia}
                                    /*typeRegion={this.props.typeRegion}
                                     typeRegionSerie={this.props.typeRegionSerie}*/
                                />
                            </div>
                            <div className="col-md-6">
                                <Map
                                    mapId="map2"
                                    id={this.state.id}
                                    tipoValores={this.props.tipoValores}
                                    decimais={decimais}
                                    /*min={this.state.min}
                                     max={this.state.max}*/
                                    periodo={this.state.max}
                                    setIntervalos={this.setIntervalos}
                                    regions={this.props.regions}
                                    abrangencia={this.props.abrangencia}
                                    /*typeRegion={this.props.typeRegion}
                                     typeRegionSerie={this.props.typeRegionSerie}*/
                                />
                            </div>
                        </div>

                        <br/><br/><br/>

                    </div>

                    <div style={{display: this.state.showCharts ? 'block' : 'none'}}>

                        <Topico icon="icon-group-chart" text="Gráficos"/>

                        <div>
                            <div style={{textAlign: 'right'}}>
                                <div className={"icons-charts" + (this.state.chartLine ? " icon-chart-line" : " icon-chart-line-disable")}
                                     style={{marginLeft: '5px'}} onClick={() => this.changeChart('chartLine')} title="">&nbsp;</div>

                                <div className={"icons-charts" + (this.state.chartBar ? " icon-chart-bar" : " icon-chart-bar-disable")}
                                     style={{marginLeft: '5px'}} onClick={() => this.changeChart('chartBar')} title="">&nbsp;</div>

                                <div className={"icons-charts" + (this.state.chartRadar ? " icon-chart-radar" : " icon-chart-radar-disable")}
                                     style={{marginLeft: '5px'}} onClick={() => this.changeChart('chartRadar')} title="">&nbsp;</div>

                                <div className={"icons-charts" + (this.state.chartPie ? " icon-chart-pie" : " icon-chart-pie-disable")}
                                     style={{marginLeft: '5px', display:'none'}} onClick={() => this.changeChart('chartPie')} title="">&nbsp;</div>
                            </div>
                            <div style={{clear:'both'}}><br/></div>
                            <div style={{display: this.state.chartLine ? 'block' : 'none'}}>
                                <ChartLine
                                    id={this.state.id}
                                    serie={this.state.serie}
                                    min={this.state.min}
                                    max={this.state.max}
                                    periodos={this.state.periodos}
                                    regions={this.props.regions}
                                    abrangencia={this.props.abrangencia}
                                    /*typeRegion={this.props.typeRegion}
                                    typeRegionSerie={this.props.typeRegionSerie}
                                    intervalos={this.state.intervalos}*/
                                />
                            </div>
                            <div style={{display: this.state.chartBar ? 'block' : 'none'}}>
                                <div className="row">
                                    <div className="col-md-12">
                                        <ChartBar
                                            id={this.state.id}
                                            serie={this.state.serie}
                                            /*intervalos={this.state.intervalos}*/
                                            min={this.state.min}
                                            max={this.state.max}
                                            regions={this.props.regions}
                                            abrangencia={this.props.abrangencia}
                                            /*data={this.state.valoresRegioesPorPeriodo}*/
                                            /*smallLarge={this.state.smallLarge}*/
                                            idBar="1"
                                        />
                                    </div>
                                    {/*<div className="col-md-6">
                                        <ChartBar
                                            serie={this.state.serie}
                                            intervalos={this.state.intervalos}
                                            data={this.state.valoresRegioesPorPeriodo.max}
                                            smallLarge={this.state.smallLarge}
                                            idBar="2"
                                        />
                                    </div>*/}
                                </div>
                            </div>
                            <div style={{display: this.state.chartRadar ? 'block' : 'none'}}>
                                <ChartRadar
                                    serie={this.state.serie}
                                    data={this.state.valoresRegioesPorPeriodo.max}
                                />
                            </div>
                            <div style={{display: this.state.chartPie ? 'block' : 'none'}}>
                                <ChartPie
                                    intervalos={this.state.intervalos}
                                    data={this.state.valoresRegioesPorPeriodo.max}
                                />
                            </div>
                        </div>
                        <br/><br/>
                    </div>

                    <div style={{display: this.state.showRegions && this.props.abrangencia==3 ? 'block' : 'none'}}>

                        <Topico icon="icon-group-rate" text="Taxas"/>

                        <Regions
                            id={this.state.id}
                            decimais={decimais}
                            regions={this.props.regions}
                            abrangencia={this.props.abrangencia}
                            min={this.state.min}
                            max={this.state.max}
                            data={this.state.valoresRegioesPorPeriodo.max}
                        />
                        <br/><br/>
                    </div>



                    <div style={{display: this.state.showTable ? 'block' : 'none'}}>

                        <Topico icon="icon-group-table" text="Tabela"/>

                        <div style={{textAlign: 'center', clear: 'both'}}>
                            <button className="btn btn-primary btn-lg bg-pri" style={{border:'0'}}>{this.state.max}</button>
                            <div style={{marginTop:'-19px'}}>
                                <i className="fa fa-sort-down fa-2x" style={{color:'#3498DB'}} />
                            </div>
                        </div>
                        <br/>

                        <ListValoresSeries
                            decimais={decimais}
                            min={this.state.min}
                            max={this.state.max}
                            data={this.state.valoresRegioesPorPeriodo.max}
                        />
                        <br/><br/>
                    </div>

                    <div className="hidden-print" style={{display: this.state.showCalcs ? 'block' : 'none'}}>

                        <Topico icon="icon-group-calc" text="Cálculos"/>

                        <Calcs
                            id={this.state.id}
                            decimais={decimais}
                            serie={this.state.serie}
                            data={this.state.valoresRegioesPorPeriodo.max}
                        />
                        <br/><br/><br/>
                    </div>

                    <div className="hidden-print" style={{display: this.state.showInfo ? 'block' : 'none'}}>
                        <div className="row">
                            <div className="col-md-12">
                                <div className="icons-groups icon-group-info" style={{float: 'left'}}>&nbsp;</div>
                                <h4 className="icon-text">&nbsp;&nbsp;Metadados</h4>
                            </div>
                        </div>
                        <hr style={{borderColor: '#3498DB'}}/>
                        <div className="bs-callout" style={{borderLeftColor: '#3498DB'}}>
                            <div dangerouslySetInnerHTML={{__html: this.props.metadados}} />
                            <br/>
                            <div className="text-right">
                                <a href={"downloads/"+this.props.id+"/"+this.props.serie} className="text-info h5">
                                    <strong>+ informações</strong>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        );
    }
}

ReactDOM.render(
    <PgSerie
        id={serie_id}
        serie={serie}
        metadados={metadados}
        tipoValores={tipoValores}
        unidade={unidade}
        from={from}
        to={to}
        regions={regions}
        abrangencia={abrangencia}
        /*typeRegion={typeRegion}
        typeRegionSerie={typeRegionSerie}*/
    />,
    document.getElementById('pgSerie')
);



