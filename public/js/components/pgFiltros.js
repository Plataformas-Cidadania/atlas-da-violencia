
class PgFiltros extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            serieMarked: 0
        };

        this.serieMarked = this.serieMarked.bind(this);
        this.loading = this.loading.bind(this);
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
    }

    loading(status) {
        this.setState({ loading: status });
    }

    changePeriodo(min, max) {
        this.setState({ min: min, max: max }, function () {
            //this.loadData();
        });
    }

    setPeriodos(periodos) {
        this.setState({ periodos: periodos });
    }

    serieMarked(id) {
        this.setState({ serieMarked: id });
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
            React.createElement(SeriesList, {
                url: "listar-series-relacionadas",
                select: "mark-one",
                parameters: { id: this.props.serie_id },
                serieMarked: this.serieMarked
            }),
            React.createElement("br", null),
            React.createElement(RangePeriodo, {
                id: this.state.serieMarked,
                changePeriodo: this.changePeriodo,
                setPeriodos: this.setPeriodos,
                loading: this.loading,

                style: { display: this.state.serieMarked > 0 ? 'block' : 'none' }
            }),
            React.createElement("br", null),
            React.createElement(FiltroRegioes, {
                id: this.state.serieMarked,
                loading: this.loading
            })
        );
    }
}

ReactDOM.render(React.createElement(PgFiltros, { serie_id: serie_id, titulo: titulo }), document.getElementById('filtros'));