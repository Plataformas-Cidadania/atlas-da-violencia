
class PgFiltros extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return React.createElement(
            "div",
            null,
            React.createElement(
                "h1",
                null,
                "Filtros - ",
                this.props.titulo
            ),
            React.createElement(SeriesList, { url: "listar-series-relacionadas", select: "mark-one", parameters: { id: this.props.serie_id } }),
            ","
        );
    }
}

ReactDOM.render(React.createElement(PgFiltros, { serie_id: serie_id, titulo: titulo }), document.getElementById('filtros'));