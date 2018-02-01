class PgSerie extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            tema_id: this.props.tema_id
        };
    }

    render() {
        return React.createElement(
            'div',
            null,
            React.createElement(Filtros, { tema_id: this.state.tema_id })
        );
    }
}

ReactDOM.render(React.createElement(PgSerie, { tema_id: tema_id }), document.getElementById('pgSerie'));