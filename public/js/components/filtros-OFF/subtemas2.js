class Subtema extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            subtemas: [],
            tema_id: this.props.tema_id,
            id: 0
        };

        this.select = this.select.bind(this);
        this.loadData = this.loadData.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.tema_id != props.tema_id) {
            this.setState({ tema_id: props.tema_id });
        }
    }

    componentDidMount() {
        this.loadData();
    }

    select(event) {
        let id = event.target.value;
        this.props.setTema(id);
        this.setState({ id: id });
    }

    select2(id) {
        this.setState({ id: id }, function () {
            this.props.setTema(id);
        });
    }

    loadData() {
        //this.setState({loading: true});
        //console.log(this.state);
        console.log(this.state.tema_id);
        $.ajax({
            method: 'GET',
            url: 'get-temas/' + this.state.tema_id,
            cache: false,
            success: function (data) {
                console.log('subtemas', data);
                this.setState({ subtemas: data });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro', err);
            }.bind(this)
        });
    }

    render() {

        console.log(this.state.subtemas);

        let subtemas = this.state.subtemas.map(function (item) {
            return React.createElement(
                'option',
                { key: "subtema_" + item.id, value: item.id },
                item.tema
            );
        }.bind(this));

        return React.createElement(
            'div',
            null,
            React.createElement(
                'h4',
                null,
                'Selecione o Subtema'
            ),
            React.createElement(
                'select',
                { className: 'form-control', onChange: this.select },
                React.createElement(
                    'option',
                    { value: '' },
                    'Selecione'
                ),
                subtemas
            )
        );
    }

}