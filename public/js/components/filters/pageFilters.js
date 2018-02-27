class PageFilters extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            items: [],
            tema: props.tema_id,
            search: '',
            indicadores: [],
            abrangencias: []
        };

        this.setTema = this.setTema.bind(this);
    }

    componentDidMount() {
        this.loadItems();
    }

    setTema(tema) {
        this.setState({ tema: tema });
    }

    loadItems() {
        $.ajax({
            method: 'POST',
            url: "list-series",
            data: {
                parameters: {
                    tema_id: this.state.tema,
                    search: this.state.search,
                    indicadores: this.state.indicadores,
                    abrangencias: this.state.abrangencias
                }
            },
            cache: false,
            success: function (data) {
                //console.log('values-for-types', data);
                this.setState({ items: data });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    render() {
        return React.createElement(
            'div',
            { className: 'container' },
            React.createElement(Temas, {
                tema_id: this.state.tema,
                setTema: this.setTema
            }),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement('hr', { style: { width: '95%' } }),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: 'col-md-3' },
                    React.createElement(
                        'fieldset',
                        null,
                        React.createElement(
                            'legend',
                            null,
                            'Pesquisa'
                        ),
                        React.createElement(
                            'div',
                            { style: { margin: '10px' } },
                            React.createElement('input', { className: 'form-control', type: 'text' })
                        )
                    ),
                    React.createElement(
                        'fieldset',
                        null,
                        React.createElement(
                            'legend',
                            null,
                            'Indicadores'
                        ),
                        React.createElement(
                            'div',
                            { style: { margin: '10px' } },
                            React.createElement(Filter, {
                                url: 'get-indicadores',
                                text: 'pesquise pelos indicadores',
                                conditions: {
                                    tema_id: this.state.tema
                                }
                            })
                        )
                    ),
                    React.createElement(
                        'fieldset',
                        null,
                        React.createElement(
                            'legend',
                            null,
                            'Abrang\xEAncias'
                        ),
                        React.createElement(
                            'div',
                            { style: { margin: '10px' } },
                            React.createElement(Filter, {
                                url: 'get-abrangencias',
                                text: 'pesquise pelas abrangencias',
                                conditions: {
                                    tema_id: this.state.tema
                                }
                            })
                        )
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'col-md-9' },
                    React.createElement(List, {
                        items: this.state.items,
                        head: ['Série', 'Unidade', 'Frequência', 'Período']
                    })
                )
            )
        );
    }
}

ReactDOM.render(React.createElement(PageFilters, { tema_id: tema_id }), document.getElementById('filtros'));