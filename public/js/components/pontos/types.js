class Types extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            type: null,
            types: [{ id: 1, type: 'Automóvel', icon: 'car' }, { id: 2, type: 'Motocicleta', icon: 'motorcycle' }, { id: 3, type: 'Pedestre', icon: 'male' }, { id: 4, type: 'Ônibus', icon: 'bus' }, { id: 5, type: 'Caminhão', icon: 'truck' }, { id: 6, type: 'Bicicleta', icon: 'bicycle' }]
        };

        this.checkType = this.checkType.bind(this);
    }

    checkType(type) {
        this.setState({ type: type });
        this.props.checkType(type);
    }

    render() {

        let types = this.state.types.map(function (item, index) {
            return React.createElement(
                'div',
                { key: "type_" + index, className: 'col-md-2' },
                React.createElement(
                    'div',
                    { className: "btn btn-icon btn-" + (this.state.type == item.id ? 'success' : 'default'), onClick: () => this.checkType(item.id) },
                    React.createElement('i', { className: "fa fa-" + item.icon, 'aria-hidden': 'true' }),
                    ' ',
                    item.type
                )
            );
        }.bind(this));

        return React.createElement(
            'div',
            { className: 'row' },
            types
        );
    }
}