class Subtema extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            subtemas: [],
            tema_id: this.props.tema_id,
            id: 0,
            componentSubtema: null,
            loading: false,
            loadingData: false
        };

        this.select = this.select.bind(this);
        this.loadData = this.loadData.bind(this);
        this.loadSubtemas = this.loadSubtemas.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.tema_id != props.tema_id) {
            this.setState({ tema_id: props.tema_id, componentSubtema: null }, function () {
                this.loadData();
            });
        }
    }

    componentDidMount() {
        this.loadData();
    }

    select(event) {
        let id = event.target.value;
        this.props.setTema(id);

        //excluir o componente subtema
        if (id === '') {
            this.setState({ componentSubtema: null });
        }

        this.setState({ id: id, loading: true });
        let promise = this.loadSubtemas(id).success(function (data) {
            if (data.length && this.state.id != this.state.tema_id) {
                //this.state.id != this.state.tema_id é para que ao selecionar todos no subtema não crie outro subtema repetido
                let subtema = React.createElement(Subtema, { setTema: this.props.setTema, tema_id: id });
                this.setState({ componentSubtema: subtema });
            } else {
                this.setState({ componentSubtema: null });
            }
            this.setState({ loading: false });
        }.bind(this));
    }

    select2(id) {
        this.setState({ id: id }, function () {
            this.props.setTema(id);
        });
    }

    loadData() {
        //this.setState({loading: true});
        //console.log(this.state);
        //console.log(this.state.tema_id);
        this.setState({ loadingData: true });
        $.ajax({
            method: 'GET',
            url: 'get-temas/' + this.state.tema_id,
            cache: false,
            success: function (data) {
                //console.log('subtemas', data);
                this.setState({ subtemas: data, loadingData: false });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro', err);
            }.bind(this)
        });
    }

    loadSubtemas(id) {
        return $.ajax({
            method: 'GET',
            url: 'get-temas/' + id,
            cache: false
        });
    }

    render() {

        if (this.state.tema_id == 0) {
            return null;
        }

        //console.log(this.state.subtemas);

        //let componentSubtema = null;

        let subtemas = this.state.subtemas.map(function (item) {
            return React.createElement(
                'option',
                { key: "subtema_" + item.id, value: item.id },
                item.titulo
            );
        }.bind(this));

        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { style: { display: this.state.subtemas.length > 0 ? '' : 'none' } },
                React.createElement('br', null),
                React.createElement(
                    'div',
                    null,
                    React.createElement(
                        'select',
                        { className: 'form-control', onChange: this.select },
                        React.createElement(
                            'option',
                            { value: this.state.tema_id },
                            'Selecione'
                        ),
                        subtemas
                    )
                ),
                React.createElement(
                    'div',
                    null,
                    this.state.componentSubtema
                )
            ),
            React.createElement(
                'div',
                { className: 'text-center', style: { display: this.state.loading || this.state.loadingData ? '' : 'none', marginTop: '5px' } },
                React.createElement('i', { className: 'fa fa-spin fa-spinner' })
            )
        );
    }

}