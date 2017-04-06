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
            territorio:0,
            typeRegion: '',
            typeRegionSerie: '',
            tipoValores: ''
        };

        this.serieMarked = this.serieMarked.bind(this);
        this.loading = this.loading.bind(this);
        this.setIndicador = this.setIndicador.bind(this);
        this.setTerritorio = this.setTerritorio.bind(this);
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
        this.setRegions = this.setRegions.bind(this);
    }


    loading(status){
        this.setState({loading: status});
    }

    setIndicador(indicador){
        this.setState({indicador: indicador});
    }

    setTerritorio(territorio){
        this.setState({territorio: territorio});
    }

    setRegions(regions){
        this.setState({regions: regions});
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
                    setTerritorio={this.setTerritorio}
                    setRegions={this.setRegions}
                />
            );
        }

        let seriesList = null;
        if(this.state.territorio > 0 && this.state.regions.length > 0){
            seriesList = (
                <SeriesList
                    url="listar-series-relacionadas"
                    select="mark-one"
                    parameters={{id: this.props.serie_id, indicador: this.state.indicador, territorio: this.state.territorio}}
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
                    <input type="hidden" name="typeRegion" value={this.state.typeRegion}/>
                    <input type="hidden" name="typeRegionSerie" value={this.state.typeRegionSerie}/>
                </form>
            </div>

        );
    }
}

ReactDOM.render(
    <PgFiltros serie_id={serie_id} titulo={titulo}/>,
    document.getElementById('filtros')
);
