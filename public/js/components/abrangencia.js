class Abrangencia extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            options: [{ id: 1, title: 'País', plural: ' os Países', on: false, listAll: 1, height: '250px' }, { id: 2, title: 'Região', plural: 'as Regiões', on: false, listAll: 1, height: '250px' }, { id: 3, title: 'UF', plural: 'os Estados', on: false, listAll: 1, height: '400px' }, { id: 4, title: 'Município', plural: 'os Municípios', on: false, listAll: 0, height: '400px',
                filter: [{ id: 12, title: 'Acre' }, { id: 27, title: 'Alagoas' }, { id: 13, title: 'Amazonas' }, { id: 16, title: 'Amapá' }, { id: 29, title: 'Bahia' }, { id: 23, title: 'Ceará' }, { id: 53, title: 'Distrito Federal' }, { id: 32, title: 'Espirito Santo' }, { id: 52, title: 'Goiás' }, { id: 21, title: 'Maranhão' }, { id: 50, title: 'Mato Grosso do Sul' }, { id: 51, title: 'Mato Grosso' }, { id: 31, title: 'Minas Gerais' }, { id: 15, title: 'Pará' }, { id: 41, title: 'Paraná' }, { id: 25, title: 'Paraíba' }, { id: 26, title: 'Pernambuco' }, { id: 22, title: 'Piauí' }, { id: 33, title: 'Rio de Janeiro' }, { id: 24, title: 'Rio Grande do Norte' }, { id: 43, title: 'Rio Grande do Sul' }, { id: 11, title: 'Rondônia' }, { id: 14, title: 'Roraima' }, { id: 42, title: 'Santa Catarina' }, { id: 35, title: 'São Paulo' }, { id: 28, title: 'Sergipe' }, { id: 17, title: 'Tocantins' }]

            }]
        };

        this.check = this.check.bind(this);
        this.selected = this.selected.bind(this);
        this.setRegions = this.setRegions.bind(this);
    }

    check(id) {
        let options = this.state.options;
        options.find(function (item) {
            item.on = false;
            item.on = item.id === id;
        });

        this.props.setTerritorio(id);

        this.setState({ options: options }, function () {});
    }

    selected() {
        let option = null;
        return this.state.options.find(function (op) {
            if (op.on) {
                option = op.id;
                return op;
            }
        });
        //return option;
    }

    setRegions(regions) {
        this.props.setRegions(regions);
    }

    render() {

        let options = this.state.options.map(function (item) {
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

        let selectItems = null;
        if (this.selected()) {
            selectItems = React.createElement(SelectItems, {
                url: 'territorios',
                option: this.selected(),
                options: this.state.options,
                setItems: this.setRegions
            });
        }

        return React.createElement(
            'div',
            null,
            React.createElement(
                'h4',
                null,
                'Selecione a abrangencia'
            ),
            React.createElement('hr', null),
            options,
            React.createElement('div', { style: { clear: 'left' } }),
            React.createElement('br', null),
            selectItems,
            React.createElement('br', null)
        );
    }

}