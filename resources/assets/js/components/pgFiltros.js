
class PgFiltros extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            serieMarked: 0,
            periodos: [],
            from: 0,
            to: 0,
            regions: [],
            typeRegion: '',
            typeRegionSerie: ''
        };

        this.serieMarked = this.serieMarked.bind(this);
        this.loading = this.loading.bind(this);
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
        this.setRegions = this.setRegions.bind(this);
    }

    loading(status){
        this.setState({loading: status});
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

    setRegions(regions, typeRegion){

        this.setState({regions: regions, typeRegion: typeRegion});


        /*let regionsSelected = regions.map(function(item){
           if(item.selected){
               return item.uf;
           }
        });*/
        /*let regionsSelected = [];
        for(let i in regions){
            if(regions[i].selected)
                regionsSelected.push(regions[i].uf)
        }
        this.setState({regions: regionsSelected});*/
        //console.log('setRegions', regions);
    }

    serieMarked(id, typeRegionSerie){
        console.log('serieMarked', id, typeRegionSerie);
        this.setState({serieMarked: id, typeRegionSerie:typeRegionSerie});
    }

    submit(){
        $('#formFiltros').submit();
    }

    render(){

        console.log('pgFiltros - render() typeRegion:', this.state.typeRegion);
        console.log('pgFiltros - render() typeRegionSerie:', this.state.typeRegionSerie);
        console.log('pgFiltros - render() regions:', this.state.regions);


        return(
            <div>
                <h1>Filtros - {this.props.titulo}</h1>
                <SeriesList
                    url="listar-series-relacionadas"
                    select="mark-one"
                    parameters={{id: this.props.serie_id}}
                    serieMarked={this.serieMarked}
                />
                <br/>
                <RangePeriodo
                    id={this.state.serieMarked}
                    changePeriodo={this.changePeriodo}
                    setPeriodos={this.setPeriodos}
                    loading={this.loading}

                    style={{display: this.state.serieMarked > 0 ? 'block' : 'none'}}
                />
                <br/><br/>
                <FiltroRegions
                    id={this.state.serieMarked}
                    setRegions={this.setRegions}
                    loading={this.loading}
                />
                <br/><br/>
                <button
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
