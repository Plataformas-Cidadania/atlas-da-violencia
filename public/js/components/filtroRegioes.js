class FiltroRegioes extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: this.props.id,
            regioes: [],
            classRegioes: [],
            selecteds: []
        };

        this.loadData = this.loadData.bind(this);
        this.loading = this.loading.bind(this);
        this.classDefault = this.classDefault.bind(this);
        this.handleClick = this.handleClick.bind(this);
        this.selectAll = this.selectAll.bind(this);
        this.selectNone = this.selectNone.bind(this);
    }

    componentDidMount() {
        if (this.state.id > 0) {
            this.loadData();
        }
    }

    componentWillReceiveProps(props) {
        if (this.state.id != props.id) {
            this.setState({ id: props.id }, function () {
                this.loadData();
            });
        }
    }

    loading(status) {
        this.props.loading(status);
    }

    classDefault() {
        let regioes = [];
        for (let i in this.state.regioes) {
            regioes[i] = this.state.regioes[i];
            regioes[i].selected = false;
        }
        this.setState({ regioes: regioes });
    }

    handleClick(e) {
        e.preventDefault();
        //console.log(e.target.id);
        let regioes = this.state.regioes;

        regioes.filter(function (obj) {
            if (obj.uf == e.target.id) obj.selected = !obj.selected;
        });

        this.setState({ regioes: regioes });
    }

    selectAll(e) {
        e.preventDefault();
        let regioes = this.state.regioes;

        regioes.filter(function (obj) {
            obj.selected = true;
        });

        this.setState({ regioes: regioes });
        console.log(this.state.regioes);
    }

    selectNone(e) {
        e.preventDefault();
        let regioes = this.state.regioes;

        regioes.filter(function (obj) {
            obj.selected = false;
        });

        this.setState({ regioes: regioes });
        //console.log(this.state.regioes);
    }

    loadData() {
        this.loading(true);
        $.ajax("regioes/" + this.state.id, {
            data: {},
            success: function (data) {
                //console.log('regioes', data);
                this.setState({ regioes: data }, function () {
                    this.classDefault();
                });
                this.loading(false);
                //periodos = data;
            }.bind(this),
            error: function (data) {
                console.log('erro');
            }.bind(this)
        });
    }

    render() {

        let regioes = this.state.regioes.map(function (item) {
            return React.createElement(
                "button",
                { type: "button",
                    id: item.uf,
                    key: item.uf,
                    className: "btn " + (item.selected ? 'btn-success' : 'btn-default'),
                    onClick: this.handleClick,
                    style: { marginRight: '5px', marginTop: '8px' } },
                item.uf
            );
        }.bind(this));

        return React.createElement(
            "div",
            { style: { display: this.state.regioes.length > 0 ? 'block' : 'none' } },
            React.createElement(
                "h4",
                null,
                "Marque as regi\xF5es interessadas"
            ),
            React.createElement(
                "button",
                { className: "btn btn-success", onClick: this.selectAll },
                "Marcar Todos"
            ),
            React.createElement(
                "button",
                { className: "btn btn-default", onClick: this.selectNone, style: { marginLeft: '10px' } },
                "Desmarcar Todos"
            ),
            React.createElement("br", null),
            regioes
        );
    }
}