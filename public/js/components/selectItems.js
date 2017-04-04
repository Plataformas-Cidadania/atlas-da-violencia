class SelectItems extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            todos: false,
            option: this.props.option,
            search: 'aaaaaa',
            parameters: {},
            items: [],
            style: {
                boxItems: {
                    height: '200px',
                    overflow: 'auto',
                    border: 'solid 1px #ccc'
                },
                item: {}
            }
        };

        this.checkAll = this.checkAll.bind(this);
        this.loadData = this.loadData.bind(this);
    }

    componentDidMount() {
        //this.loadData();
    }

    componentWillReceiveProps(props) {
        if (this.state.option != props.option) {
            let parameters = this.state.parameters;
            parameters.option = props.option;
            this.setState({ option: props.option }, function () {
                this.loadData();
            });
        }
    }

    loadData() {
        this.setState({ loading: true });
        console.log(this.state);
        $.ajax({
            method: 'POST',
            url: this.props.url,
            data: {
                search: this.state.search,
                parameters: this.state.parameters
            },
            cache: false,
            success: function (data) {
                console.log('selectItems', data);
                this.setState({ items: data }, function () {
                    this.setState({ loading: false });
                });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro', err);
            }.bind(this)
        });
    }

    checkAll() {
        let todos = this.state.todos;
        this.setState({ todos: !todos });
    }

    render() {

        let items = this.state.items.map(function (item) {
            return React.createElement(
                'div',
                { key: item.id },
                React.createElement(
                    'div',
                    { style: this.state.style.item },
                    React.createElement('i', { className: 'fa fa-square-o' }),
                    ' ',
                    item.title
                ),
                React.createElement('hr', { style: { margin: '5px' } })
            );
        }.bind(this));

        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { style: { cursor: 'pointer' }, onClick: this.checkAll },
                React.createElement(
                    'div',
                    { style: { display: this.state.todos ? 'block' : 'none' } },
                    React.createElement('img', { src: 'img/checkbox_on.png', alt: '' }),
                    ' Todos os Munic\xEDpios'
                ),
                React.createElement(
                    'div',
                    { style: { display: this.state.todos ? 'none' : 'block' } },
                    React.createElement('img', { src: 'img/checkbox_off.png', alt: '' }),
                    ' Todos os Munic\xEDpios'
                )
            ),
            React.createElement('br', null),
            React.createElement('input', { type: 'text', className: 'form-control', placeholder: 'Pesquisa', onChange: this.handleChange }),
            React.createElement('br', null),
            React.createElement(
                'div',
                { style: { cursor: 'pointer' }, onClick: this.checkAll },
                React.createElement(
                    'div',
                    { style: { display: this.state.todos ? 'block' : 'none' } },
                    React.createElement('img', { src: 'img/checkbox_on.png', alt: '' }),
                    ' Marcar todos os listados abaixo'
                ),
                React.createElement(
                    'div',
                    { style: { display: this.state.todos ? 'none' : 'block' } },
                    React.createElement('img', { src: 'img/checkbox_off.png', alt: '' }),
                    ' Marcar todos os listados abaixo'
                )
            ),
            React.createElement('br', null),
            React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: 'col-md-7', style: this.state.style.boxItems },
                    React.createElement(
                        'div',
                        { style: this.state.boxItems },
                        items
                    )
                ),
                React.createElement('div', { className: 'col-md-5' })
            )
        );
    }
}