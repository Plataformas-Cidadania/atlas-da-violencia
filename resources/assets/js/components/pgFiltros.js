
class PgFiltros extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            serieMarked: 0
        };

        this.serieMarked = this.serieMarked.bind(this);
        this.loading = this.loading.bind(this);
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
    }

    loading(status){
        this.setState({loading: status});
    }

    changePeriodo(min, max){
        this.setState({min: min, max: max}, function(){
            //this.loadData();
        });
    }

    setPeriodos(periodos){
        this.setState({periodos: periodos});
    }

    serieMarked(id){
        this.setState({serieMarked: id});
    }

    render(){
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
                <br/>
                <FiltroRegioes
                    id={this.state.serieMarked}
                    loading={this.loading}
                />
            </div>

        );
    }
}

ReactDOM.render(
    <PgFiltros serie_id={serie_id} titulo={titulo}/>,
    document.getElementById('filtros')
);
