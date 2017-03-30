class Indicadores extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            indicadores: [{ id: 1, title: 'Quantidade', on: true }, { id: 2, title: 'Taxa por 100 mil Habitantes', on: false }]
        };

        this.check = this.check.bind(this);
    }

    check(id) {
        let indicadores = this.state.indicadores;
        indicadores.find(function (item) {
            item.on = false;
            item.on = item.id === id;
        });

        this.setState({ indicadores: indicadores });
    }

    render() {

        let indicadores = this.state.indicadores.map(function (item) {
            return React.createElement(
                'div',
                { key: item.id, style: { float: 'left', marginRight: '20px', cursor: 'pointer' }, onClick: () => this.check(item.id) },
                React.createElement(
                    'div',
                    { style: { display: item.on ? 'block' : 'none' } },
                    React.createElement('img', { src: 'img/checkbox_on.png', alt: '' }),
                    ' ',
                    item.title
                ),
                React.createElement(
                    'div',
                    { style: { display: item.on ? 'none' : 'block' } },
                    React.createElement('img', { src: 'img/checkbox_off.png', alt: '' }),
                    ' ',
                    item.title
                )
            );
        }.bind(this));

        return React.createElement(
            'div',
            null,
            React.createElement(
                'h4',
                null,
                'Escolha o indicador'
            ),
            React.createElement('hr', null),
            indicadores,
            React.createElement('div', { style: { clear: 'left' } }),
            React.createElement('br', null)
        );
    }

}