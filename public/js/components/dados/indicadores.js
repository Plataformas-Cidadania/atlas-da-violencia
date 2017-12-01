class Indicadores extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            tema_id: this.props.tema_id,
            indicadores: [{ id: 1, title: 'Quantidade', on: false, enable: true }, { id: 2, title: 'Taxa por 100 mil Habitantes', on: false, enable: true }]
        };

        this.check = this.check.bind(this);
        this.loadData = this.loadData.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.tema_id != props.tema_id) {
            this.setState({ tema_id: props.tema_id }, function () {
                this.loadData();
            });
        }
    }

    componentDidMount() {
        this.loadData();
    }

    check(event) {

        let id = event.target.value;

        let indicadores = this.state.indicadores;
        let ok = true;
        indicadores.find(function (item) {
            //se o item clicado não estiver habilidado o ok será false para não alterar nada.
            if (item.id === id && !item.enable) {
                ok = false;
            }
        });

        if (ok) {
            indicadores.find(function (item) {
                //se o item clicado não estiver habilidado o ok será false para não alterar nada.
                item.on = false;
                item.on = item.id === id;
            });

            this.props.setIndicador(id);
            this.setState({ indicadores: indicadores });
        }
    }

    loadData() {
        //this.setState({loading: true});
        //console.log(this.state);
        $.ajax({
            method: 'GET',
            url: 'get-indicadores-filtros/' + this.state.tema_id,
            cache: false,
            success: function (data) {
                //console.log('indicadores', data);
                this.setState({ indicadores: data });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro', err);
            }.bind(this)
        });
    }

    render() {
        let indicadores = this.state.indicadores.map(function (item) {
            return React.createElement(
                'option',
                { key: "ind_" + item.id, value: item.id, disabled: !item.enable },
                item.title
            );
        }.bind(this));

        return React.createElement(
            'div',
            { className: 'div-select', style: { float: 'left', marginRight: '5px' } },
            React.createElement(
                'select',
                { className: 'form-control', onClick: this.check, style: { display: 'inline' } },
                React.createElement(
                    'option',
                    { value: '0' },
                    'Todos os Indicadores'
                ),
                indicadores
            )
        );
    }

}