class ListItems extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            type: props.type,
            items: props.items
        };
    }

    componentWillReceiveProps(props) {
        if (props.items != this.state.items) {
            this.setState({ items: props.items });
        }
        if (props.tipo != this.state.type) {
            this.setState({ type: props.type });
        }
    }

    render() {

        let head = null;
        let items = null;

        console.log('ITEMS ########', this.state.items);

        if (this.state.type == 1) {
            head = React.createElement(
                'tr',
                null,
                React.createElement(
                    'th',
                    null,
                    'Local'
                ),
                React.createElement(
                    'th',
                    null,
                    'Locomo\xE7\xE3o'
                ),
                React.createElement(
                    'th',
                    null,
                    'Tipo de Acidente'
                ),
                React.createElement(
                    'th',
                    null,
                    'Sexo'
                ),
                React.createElement(
                    'th',
                    null,
                    'Turno'
                ),
                React.createElement(
                    'th',
                    null,
                    'Data'
                ),
                React.createElement(
                    'th',
                    null,
                    'Hora'
                )
            );
            if (this.state.items.data != undefined) {
                items = this.state.items.data.map(function (item) {
                    console.log(item);
                    return React.createElement(
                        'tr',
                        { key: "item_" + item.id },
                        React.createElement(
                            'td',
                            null,
                            item.endereco
                        ),
                        React.createElement(
                            'td',
                            null,
                            item.tipo
                        ),
                        React.createElement(
                            'td',
                            null,
                            item.tipo_acidente
                        ),
                        React.createElement(
                            'td',
                            null,
                            item.sexo
                        ),
                        React.createElement(
                            'td',
                            null,
                            item.turno
                        ),
                        React.createElement(
                            'td',
                            null,
                            item.data
                        ),
                        React.createElement(
                            'td',
                            null,
                            item.hora
                        )
                    );
                });
            }
        }

        console.log('ITEMS >>>>>>>>>>>>', items);

        if (this.state.type == 2) {
            head = React.createElement(
                'tr',
                null,
                React.createElement(
                    'th',
                    null,
                    'Territ\xF3rio'
                ),
                React.createElement(
                    'th',
                    null,
                    'Total'
                )
            );
            items = this.state.items.map(function (item) {
                return React.createElement(
                    'tr',
                    null,
                    React.createElement(
                        'td',
                        null,
                        item.territorio
                    ),
                    React.createElement(
                        'td',
                        null,
                        item.total
                    )
                );
            });
        }

        return React.createElement(
            'div',
            null,
            React.createElement(
                'table',
                null,
                React.createElement(
                    'thead',
                    null,
                    head
                ),
                items
            )
        );
    }
}