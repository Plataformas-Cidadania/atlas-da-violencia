
class PgFiltros extends React.Component{
    constructor(props){
        super(props);
    }

    render(){
        return(
            <div>
                <h1>Filtros - {this.props.titulo}</h1>
                <SeriesList url="listar-series-relacionadas" select="mark-one" parameters={{id: this.props.serie_id}}/>,
            </div>

        );
    }
}

ReactDOM.render(
    <PgFiltros serie_id={serie_id} titulo={titulo}/>,
    document.getElementById('filtros')
);
