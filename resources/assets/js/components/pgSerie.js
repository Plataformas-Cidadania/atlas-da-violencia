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
            fonte: this.props.fonte,
            loading: false,
            loadingItems: true,
            intervalos: [],
            intervalosFrom: [],
            intervalosTo: [],
            valoresRegioesPorPeriodo: {min: 0, max: 0, values: {}},
            valoresPeriodo: {},
            smallLarge: [0,1],
            min: this.props.from,
            max: this.props.to,
            /*min: this.props.from,
            max: this.props.to,*/
            periodos: [],
            abrangencia: props.abrangencia,
            nomeAbrangencia: props.nomeAbrangencia,
            abrangenciasOk: props.abrangenciasOk,
            regions: props.regions,
            showMap: true,
            loadingMap: false,
            showCharts: true,
            showRegions: true,
            showTable: true,
            showCalcs: true,
            showInfo: true,
            chartLine: true,
            chartRadar: false,
            chartBar:false,
            chartPie:false,
        };
        this.loading = this.loading.bind(this);
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
        this.showHide = this.showHide.bind(this);
        this.loadData = this.loadData.bind(this);
        this.loadDataPeriodo = this.loadDataPeriodo.bind(this);
        this.loadDataMaps = this.loadDataMaps.bind(this);
        this.setIntervalos = this.setIntervalos.bind(this);
        this.calcSmallLarge = this.calcSmallLarge.bind(this);
        this.setAbrangencia = this.setAbrangencia.bind(this);
        this.setNomeAbrangencia = this.setNomeAbrangencia.bind(this);
        this.setRegions = this.setRegions.bind(this);

    }

    loading(status){
        this.setState({loading: status});
    }

    changePeriodo(min, max){
        this.setState({min: min, max: max}, function(){
            //console.log(min, max);
            this.loadData();
            this.loadDataPeriodo();
            this.loadDataMaps();
        });
    }

    setPeriodos(periodos){
        this.setState({periodos: periodos});
    }

    setAbrangencia(abrangencia){
        $.ajax({
            method:'GET',
            url: "get-regions/"+abrangencia,
            cache: false,
            success: function(data) {
                console.log('GET-REGIONS IN PGSERIE', data);
                this.setState({regions: data, abrangencia: abrangencia});
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    setNomeAbrangencia(nomeAbrangencia){
        this.setState({nomeAbrangencia: nomeAbrangencia});
    }

    setRegions(regions){
        console.log(regions);
        this.setState({regions: regions}, function(){
            this.loadData();
            this.loadDataPeriodo();
            this.loadDataMaps();
        });
    }


    loadData(){

        //console.log('MIN', this.state.min);
        //console.log('MAX', this.state.max);

        if(this.state.min && this.state.max){

            this.setState({loadingItems: true});

            //console.log(this.state.regions);
            $.ajax({
                method:'GET',
                //url: "valores-regiao/"+this.state.id+"/"+this.props.tipoValores+"/"+this.state.min+"/"+this.state.max,
                url: "valores-regiao/"+this.state.id+"/"+this.state.min+"/"+this.state.max+"/"+this.state.regions+"/"+this.state.abrangencia,
                //url: "valores-regiao/"+this.state.id+"/"+this.state.max,
                cache: false,
                success: function(data) {
                    //console.log('pgSerie', data);
                    //os valores menor e maior para serem utilizados no chartBar
                    let smallLarge = this.calcSmallLarge(data.min.valores, data.max.valores);

                    this.setState({valoresRegioesPorPeriodo: data, smallLarge: smallLarge, loadingItems: false});

                }.bind(this),
                error: function(xhr, status, err) {
                  console.log('erro');
                }.bind(this)
            });
        }
    }

    loadDataPeriodo(){
        if(this.state.min && this.state.max) {
            let _this = this;
            //$.ajax("periodo/"+this.state.id+"/"+this.state.min+"/"+this.state.max, {
            $.ajax("periodo/" + this.state.id + "/" + this.state.min + "/" + this.state.max + "/" + this.state.regions + "/" + this.state.abrangencia, {
                data: {},
                success: function (data) {
                    this.setState({valoresPeriodo: data});
                }.bind(this),
                error: function (data) {
                    console.log('erro');
                }.bind(this)
            })
        }
    }

    loadDataMaps(){
        this.setState({loadingMap: true});
        if(this.state.min && this.state.min){
            let _this = this;
            $.ajax("regiao/"+_this.state.id+"/"+_this.state.min+"/"+_this.state.regions+"/"+_this.state.abrangencia, {
                data: {},
                success: function(dataMapFrom){

                    let valoresMapFrom = this.getValoresMap(dataMapFrom);

                    $.ajax("regiao/"+_this.state.id+"/"+_this.state.max+"/"+_this.state.regions+"/"+_this.state.abrangencia, {
                        data: {},
                        success: function(dataMapTo){


                            let valoresMapTo = this.getValoresMap(dataMapTo);

                            let intervalos = this.setIntervalos(gerarIntervalos(valoresMapFrom), gerarIntervalos(valoresMapTo));
                            //console.log(dataMapFrom);
                            //console.log(dataMapTo);
                            //console.log(intervalos);
                            this.setState({dataMapFrom: dataMapFrom, dataMapTo: dataMapTo, intervalos: intervalos, loadingMap:false});
                        }.bind(this),
                        error: function(data){
                            //console.log('map', 'erro');
                        }
                    })
                }.bind(this),
                error: function(data){
                    //console.log('map', 'erro');
                }
            });
        }


    }

    getValoresMap(data){
        let valores = [];
        for(let i in data.features) {
            valores[i] = data.features[i].properties.total;
        }

        return valores;
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

    setIntervalos(intervalosFrom, intervalosTo){

        if(intervalosFrom[intervalosFrom.length-1] > intervalosTo[intervalosTo.length-1]){
            return intervalosFrom;
        }

        return intervalosTo;

    }

    change(event){
        console.log(event.target.value);
    }


    generateArrayYears(from, to){
        let arrayYears = [];
        for(let i = from ; i <= to ; i++){
            arrayYears.push(i);
        }

        return arrayYears;
    }


    render(){

        //utilizado para função de formatação
        let decimais = this.props.tipoUnidade==1 ? 0 : 2;

        let regions = null;



        let from = formatPeriodicidade(this.state.min, this.props.periodicidade);
        let to = formatPeriodicidade(this.state.max, this.props.periodicidade);

        let arrayYears = this.generateArrayYears(from, to)

        let optionsDownloadPeriodosFrom = arrayYears.map(function(item, index){
            let selected = false;
            if(index==0){
                selected = 'selected';
            }
            return <option selected={selected} value={item+'-01-'+'-01'}>{item}</option>
        });

        let optionsDownloadPeriodosTo = arrayYears.map(function(item, index){
            let selected = false;
            if(index==arrayYears.length-1){
                selected = 'selected';
            }
            return <option selected={selected} value={item+'-01-'+'-01'}>{item}</option>
        }.bind(this));

        if(this.state.showRegions && this.state.abrangencia==3){
            regions = (
                <div style={{display: this.state.showRegions && this.state.abrangencia==3 ? 'block' : 'none'}}>

                    <Topico icon="icon-group-rate" text="Taxas"/>
                    <Regions
                        id={this.state.id}
                        periodicidade={this.props.periodicidade}
                        decimais={decimais}
                        regions={this.state.regions}
                        abrangencia={this.state.abrangencia}
                        min={this.state.min}
                        max={this.state.max}
                        data={this.state.valoresRegioesPorPeriodo.max}
                    />
                    <br/><br/>
                </div>
            );
        }

        return(
            <div>
                <div className="text-center" style={{display: this.state.loading ? 'block' : 'none'}}>
                    <br/><br/>
                    <i className="fa fa-5x fa-spinner fa-spin"> </i>
                </div>
                <div style={{visibility: this.state.loading ? 'disable' : 'enable'}}>
                    <div className="row">
                        <div className="h3">
                           {/* <img style={{marginLeft: '5px'}} src="imagens/links/8516-01.png" width="52" alt="" title=""/>*/}
                            &nbsp;{this.state.serie}
                        </div>

                        <div className="line_title bg-pri"/>
                        <br/>

                        <div className="col-md-6">
                            <AbrangenciaSerie
                                abrangencia={this.state.abrangencia}
                                nomeAbrangencia={this.state.nomeAbrangencia}
                                setAbrangencia={this.setAbrangencia}
                                abrangenciasOk={this.state.abrangenciasOk}
                                setRegions={this.setRegions}
                                setNomeAbrangencia={this.setNomeAbrangencia}
                            />
                        </div>



                        <div className="col-md-6 text-right hidden-print">

                            <div className="dropdown">
                                <div id="dLabel" className="icons-groups icon-group-download"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                     style={{display: 'block', marginLeft: '5px'}} title="">&nbsp;</div>

                                <ul className="dropdown-menu" aria-labelledby="dLabel" style={{left: 'inherit', right: '0', float: 'right', margin: '40px 0 0'}}>
                                    <li><a>Dados em .csv</a></li>
                                    <li role="separator" className="divider"/>
                                    <li>
                                        <form name="frmDownloadPeriodo" action="download-dados" target="_blank" method="POST">
                                            <input type="hidden" name="_token" value={$('meta[name="csrf-token"]').attr('content')}/>
                                            <input type="hidden" name="downloadType" value='csv'/>
                                            <input type="hidden" name="id" value={this.props.id}/>
                                            <input type="hidden" name="serie" value={this.props.serie}/>
                                            <input type="hidden" name="from" value={this.state.min}/>
                                            <input type="hidden" name="to" value={this.state.max}/>
                                            <input type="hidden" name="regions" value={this.state.regions}/>
                                            <input type="hidden" name="abrangencia" value={this.state.abrangencia}/>
                                            <button className="btn-download">Download ({formatPeriodicidade(this.state.min, this.props.periodicidade)
                                            } - {formatPeriodicidade(this.state.max, this.props.periodicidade)})</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form name="frmDownloadTotal" action="download-dados" target="_blank" method="POST">
                                            <input type="hidden" name="_token" value={$('meta[name="csrf-token"]').attr('content')}/>
                                            <input type="hidden" name="downloadType" value='csv'/>
                                            <input type="hidden" name="id" value={this.props.id}/>
                                            <input type="hidden" name="serie" value={this.props.serie}/>
                                            <input type="hidden" name="regions" value={this.state.regions}/>
                                            <input type="hidden" name="abrangencia" value={this.state.abrangencia}/>
                                            <button className="btn-download">Download Total</button>
                                        </form>
                                    </li>
                                    <li>
                                        <button className="btn-download" data-toggle="modal" data-target="#downloadModal">Download Personalizado</button>
                                    </li>
                                    <br/>
                                    <li><a>Dados em .ods</a></li>
                                    <li role="separator" className="divider"/>
                                    <li>
                                        <form name="frmDownloadPeriodo" action="download-dados" target="_blank" method="POST">
                                            <input type="hidden" name="_token" value={$('meta[name="csrf-token"]').attr('content')}/>
                                            <input type="hidden" name="downloadType" value='ods'/>
                                            <input type="hidden" name="id" value={this.props.id}/>
                                            <input type="hidden" name="serie" value={this.props.serie}/>
                                            <input type="hidden" name="from" value={this.state.min}/>
                                            <input type="hidden" name="to" value={this.state.max}/>
                                            <input type="hidden" name="regions" value={this.state.regions}/>
                                            <input type="hidden" name="abrangencia" value={this.state.abrangencia}/>
                                            <button className="btn-download">Download ({formatPeriodicidade(this.state.min, this.props.periodicidade)
                                            } - {formatPeriodicidade(this.state.max, this.props.periodicidade)})</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form name="frmDownloadTotal" action="download-dados" target="_blank" method="POST">
                                            <input type="hidden" name="_token" value={$('meta[name="csrf-token"]').attr('content')}/>
                                            <input type="hidden" name="downloadType" value='ods'/>
                                            <input type="hidden" name="id" value={this.props.id}/>
                                            <input type="hidden" name="serie" value={this.props.serie}/>
                                            <input type="hidden" name="regions" value={this.state.regions}/>
                                            <input type="hidden" name="abrangencia" value={this.state.abrangencia}/>
                                            <button className="btn-download">Download Total</button>
                                        </form>
                                    </li>

                                </ul>
                                <div id="downloadModal" className="modal fade text-left" role="dialog" style={{zIndex: "9999999999"}}>
                                    <div className="modal-dialog">
                                        <div className="modal-content">
                                            <form name="frmDownloadTotal" action="download-dados" target="_blank" method="POST">
                                                <div className="modal-header">
                                                    <button type="button" className="close"
                                                            data-dismiss="modal">&times;</button>
                                                    <h4 className="modal-title">Personalizar Download</h4>
                                                </div>
                                                <div className="modal-body">
                                                    <input type="hidden" name="_token" value={$('meta[name="csrf-token"]').attr('content')}/>
                                                    <input type="hidden" name="downloadType" value='csv'/>
                                                    <input type="hidden" name="id" value={this.props.id}/>
                                                    <input type="hidden" name="serie" value={this.props.serie}/>
                                                    <div>
                                                        <label htmlFor="decimal">Separador Decimal</label>
                                                        <select name="decimal" className="form-control">
                                                            <option value=",">,</option>
                                                            <option value=".">.</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label htmlFor="from">De</label>
                                                        <select name="from" className="form-control">
                                                            {optionsDownloadPeriodosFrom}
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label htmlFor="to">Até</label>
                                                        <select name="to" className="form-control">
                                                            {optionsDownloadPeriodosTo}
                                                        </select>
                                                    </div>
                                                    <input type="hidden" name="regions" value={this.state.regions}/>
                                                    <input type="hidden" name="abrangencia" value={this.state.abrangencia}/>
                                                </div>
                                                <div className="modal-footer">
                                                    <button type="button" className="btn btn-default"
                                                            data-dismiss="modal">Fechar
                                                    </button>
                                                    <button className="btn btn-primary">Download</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div className="icons-groups icon-group-email"  data-toggle="modal" data-target="#myModal"
                                 style={{display: 'block', marginLeft: '5px'}} title="">&nbsp;</div>

                            <div className="icons-groups icon-group-print" onClick={() => window.print()}
                                 style={{display: 'block', marginLeft: '5px'}} title="">&nbsp;</div>

                            <div className={"icons-groups" + (this.state.showInfo ? " icon-group-info" : " icon-group-info-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.showHide('Info')} title="">&nbsp;</div>

                            {/*<div className={"icons-groups" + (this.state.showCalcs ? " icon-group-calc" : " icon-group-calc-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.showHide('Calcs')} title="">&nbsp;</div>*/}

                            <div className={"icons-groups" + (this.state.showTable ? " icon-group-table" : " icon-group-table-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.showHide('Table')} title="">&nbsp;</div>

                            {/*<div className={"icons-groups" + (this.state.showRegions ? " icon-group-rate" : " icon-group-rate-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.showHide('Regions')} title="">&nbsp;</div>*/}

                            <div className={"icons-groups" + (this.state.showCharts ? " icon-group-chart" : " icon-group-chart-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.showHide('Charts')} title="">&nbsp;</div>

                            <div className={"icons-groups" + (this.state.showMap ? " icon-group-map" : " icon-group-map-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.showHide('Map')} title="">&nbsp;</div>
                        </div>
                    </div>







                    <div className="hidden-print">
                        <br/>

                        <RangePeriodo
                            id={this.state.id}
                            periodicidade={this.props.periodicidade}
                            abrangencia={this.state.abrangencia}
                            changePeriodo={this.changePeriodo}
                            setPeriodos={this.setPeriodos}
                            loading={this.loading}
                            from={this.props.from}
                            to={this.props.to}
                        />
                        <br/>

                    </div>



                    <div style={{borderTop: 'solid 1px #ccc', padding:'10px 0'}} className="text-right">
                        <div style={{clear:'both'}}/>
                    </div>

                    <div style={{display: this.state.showTable ? 'block' : 'none'}}>

                        <Topico icon="icon-group-table" text="Tabela"/>

                        {/*<div style={{textAlign: 'center', clear: 'both'}}>
                            <button className="btn btn-primary btn-lg bg-pri" style={{border:'0'}}>{formatPeriodicidade(this.state.min, this.props.periodicidade)} - {formatPeriodicidade(this.state.max, this.props.periodicidade)}</button>
                            <div style={{marginTop:'-19px'}}>
                                <i className="fa fa-sort-down fa-2x ft-pri"  />
                            </div>
                        </div>
                        <br/>*/}

                        <div style={{display: this.state.loadingItems ? '' : 'none'}} className="text-center"><i className="fa fa-spin fa-spinner fa-4x"/></div>
                        <div style={{display: this.state.loadingItems ? 'none' : ''}}>
                            <ListValoresSeries
                                decimais={decimais}
                                periodicidade={this.props.periodicidade}
                                nomeAbrangencia={this.state.nomeAbrangencia}
                                min={this.state.min}
                                max={this.state.max}
                                data={this.state.valoresPeriodo}
                                tipoUnidade={this.props.tipoUnidade}
                                abrangencia={this.state.abrangencia}
                                /*data={this.state.valoresRegioesPorPeriodo.max}*/
                                /*dataMin={this.state.valoresRegioesPorPeriodo.min}
                                dataMax={this.state.valoresRegioesPorPeriodo.max}*/
                            />
                            <p style={{marginTop: '-50px'}}><strong>Unidade: </strong>{this.props.unidade}</p>
                        </div>

                        <br/><br/>

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
                                    periodicidade={this.props.periodicidade}
                                    min={this.state.min}
                                    max={this.state.max}
                                    periodos={this.state.periodos}
                                    regions={this.state.regions}
                                    abrangencia={this.state.abrangencia}
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
                                            periodicidade={this.props.periodicidade}
                                            /*intervalos={this.state.intervalos}*/
                                            min={this.state.min}
                                            max={this.state.max}
                                            regions={this.state.regions}
                                            abrangencia={this.state.abrangencia}
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
                                    periodicidade={this.props.periodicidade}
                                    data={this.state.valoresRegioesPorPeriodo.max}
                                />
                            </div>
                            <div style={{display: this.state.chartPie ? 'block' : 'none'}}>
                                <ChartPie
                                    intervalos={this.state.intervalos}
                                    periodicidade={this.props.periodicidade}
                                    data={this.state.valoresRegioesPorPeriodo.max}
                                />
                            </div>
                        </div>
                        <br/><br/>
                    </div>

                    {regions}



                    {/*<div className="hidden-print" style={{display: this.state.showCalcs ? 'block' : 'none'}}>

                        <Topico icon="icon-group-calc" text="Cálculos"/>

                        <Calcs
                            id={this.state.id}
                            decimais={decimais}
                            serie={this.state.serie}
                            data={this.state.valoresRegioesPorPeriodo.max}
                        />
                        <br/><br/><br/>
                    </div>*/}

                    <div style={{display: this.state.showMap ? 'block' : 'none'}}>

                        <Topico icon="icon-group-map" text="Mapa"/>

                        <div className="row col-md-12 text-center" style={{display: this.state.loadingMap ? 'block' : 'none'}}>
                            <i className="fa fa-spin fa-spinner fa-4x"/>
                        </div>

                        <div className="row" style={{display: !this.state.loadingMap ? 'block' : 'none'}}>
                            <div className="col-md-6 col-sm-12">
                                <Map
                                    mapId="map1"
                                    id={this.state.id}
                                    serie={this.props.serie}
                                    periodicidade={this.props.periodicidade}
                                    tipoValores={this.props.tipoValores}
                                    decimais={decimais}
                                    /*min={this.state.min}
                                    max={this.state.max}*/
                                    data={this.state.dataMapFrom}
                                    periodo={this.state.min}
                                    //tipoPeriodo="from"
                                    intervalos={this.state.intervalos}
                                    //setIntervalos={this.setIntervalos}
                                    //regions={this.state.regions}
                                    //abrangencia={this.state.abrangencia}
                                    /*typeRegion={this.props.typeRegion}
                                     typeRegionSerie={this.props.typeRegionSerie}*/
                                />
                            </div>
                            <div className="col-md-6 col-sm-12 print-map">
                                <Map
                                    mapId="map2"
                                    id={this.state.id}
                                    serie={this.props.serie}
                                    periodicidade={this.props.periodicidade}
                                    tipoValores={this.props.tipoValores}
                                    decimais={decimais}
                                    /*min={this.state.min}
                                     max={this.state.max}*/
                                    data={this.state.dataMapTo}
                                    periodo={this.state.max}
                                    //tipoPeriodo="to"
                                    intervalos={this.state.intervalos}
                                    //setIntervalos={this.setIntervalos}
                                    //regions={this.state.regions}
                                    //abrangencia={this.state.abrangencia}
                                    /*typeRegion={this.props.typeRegion}
                                     typeRegionSerie={this.props.typeRegionSerie}*/
                                />
                            </div>
                        </div>

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

                        <p><strong>Fonte: </strong>{this.props.fonte}</p>
                       {/* <p><strong>Periodicidade: </strong>{this.props.periodicidade}</p>*/}
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
        periodicidade={periodicidade}
        metadados={metadados}
        fonte={fonte}
        tipoValores={tipoValores}
        unidade={unidade}
        tipoUnidade={tipoUnidade}
        from={from}
        to={to}
        regions={regions}
        abrangencia={abrangencia}
        abrangenciasOk={abrangenciasOk}
        nomeAbrangencia={nomeAbrangencia}
        /*typeRegion={typeRegion}
        typeRegionSerie={typeRegionSerie}*/
    />,
    document.getElementById('pgSerie')
);



