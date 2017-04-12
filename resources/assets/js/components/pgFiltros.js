class PgFiltros extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            serieMarked: 0,
            periodos: [],
            from: 0,
            to: 0,
            regions: [],
            indicador:0,
            abrangencia:0,
            typeRegion: '',
            typeRegionSerie: '',
            tipoValores: ''
        };

        this.serieMarked = this.serieMarked.bind(this);
        this.loading = this.loading.bind(this);
        this.setIndicador = this.setIndicador.bind(this);
        this.setAbrangencia = this.setAbrangencia.bind(this);
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
        this.setRegions = this.setRegions.bind(this);
    }


    loading(status){
        this.setState({loading: status});
    }

    setIndicador(indicador){
        this.setState({indicador: indicador, serieMarked: 0});
    }

    setAbrangencia(abrangencia){
        this.setState({abrangencia: abrangencia, serieMarked: 0});
    }

    setRegions(regions){

        let regionsId = [];
        for(let i in regions){
            regionsId.push(regions[i].id)
        }

        this.setState({regions: regionsId, serieMarked: 0});
    }

    changePeriodo(min, max){
        this.setState({from: min, to: max}, function(){
            //this.loadData();
        });
    }

    setPeriodos(periodos){
        this.setState({periodos: periodos});
        //console.log('setPeriodos', periodos);
    }

    /*setRegions(regions, typeRegion){
        this.setState({regions: regions, typeRegion: typeRegion});
    }*/


    serieMarked(id, typeRegionSerie, tipoValores){
        //console.log('serieMarked', id, typeRegionSerie);
        this.setState({serieMarked: id, typeRegionSerie:typeRegionSerie, tipoValores: tipoValores});
    }

    submit(){
        $('#formFiltros').submit();
    }

    render(){

        //console.log('pgFiltros - render() typeRegion:', this.state.typeRegion);
        //console.log('pgFiltros - render() typeRegionSerie:', this.state.typeRegionSerie);
        //console.log('pgFiltros - render() regions:', this.state.regions);

        console.log(this.state.tipoValores);

        let abrangencia = null;
        if(this.state.indicador > 0){
            abrangencia = (
                <Abrangencia
                    setAbrangencia={this.setAbrangencia}
                    setRegions={this.setRegions}
                />
            );
        }

        let seriesList = null;
        if(this.state.abrangencia > 0 && this.state.regions.length > 0){
            seriesList = (
                <SeriesList
                    url="listar-series-relacionadas"
                    select="mark-one"
                    parameters={{id: this.props.serie_id, indicador: this.state.indicador, abrangencia: this.state.abrangencia, serieMarked: this.state.serieMarked}}
                    serieMarked={this.serieMarked}
                />
            );
        }

        return(
            <div>
                <h1>Filtros - {this.props.titulo}</h1>
                <br/>

                <Indicadores
                    setIndicador={this.setIndicador}
                />
                <br/>

                {abrangencia}
                <br/>

                {seriesList}
                <br/>

                <RangePeriodo
                    id={this.state.serieMarked}
                    changePeriodo={this.changePeriodo}
                    setPeriodos={this.setPeriodos}
                    loading={this.loading}

                    style={{display: this.state.serieMarked > 0 ? 'block' : 'none'}}
                />
                <br/><br/>
                <button
                    disabled={this.state.regions.length==0}
                    className="btn btn-success btn-lg"
                    style={{display: this.state.serieMarked > 0 ? 'block' : 'none', margin: 'auto', minWidth: '350px'}}
                    onClick={this.submit}>
                    Continuar
                </button>
                <form id="formFiltros" style={{display:'none'}} action="dados-series" method="POST">
                    <input type="hidden" name="_token" value={$('meta[name="csrf-token"]').attr('content')}/>
                    <input type="hidden" name="id" value={this.state.serieMarked}/>
                    <input type="hidden" name="from" value={this.state.from}/>
                    <input type="hidden" name="to" value={this.state.to}/>
                    <input type="hidden" name="periodos" value={this.state.periodos}/>
                    <input type="hidden" name="regions" value={this.state.regions}/>
                    <input type="hidden" name="abrangencia" value={this.state.abrangencia}/>
                    {/*<input type="hidden" name="typeRegion" value={this.state.typeRegion}/>
                    <input type="hidden" name="typeRegionSerie" value={this.state.typeRegionSerie}/>*/}
                </form>
            </div>

        );
    }
}

ReactDOM.render(
    <PgFiltros serie_id={serie_id} titulo={titulo}/>,
    document.getElementById('filtros')
);
