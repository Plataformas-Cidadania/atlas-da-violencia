class FiltroRegions extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: this.props.id,
            regions: [],
            classRegions: [],
            selecteds: []
        };

        this.loadData = this.loadData.bind(this);
        this.loading = this.loading.bind(this);
        this.classDefault = this.classDefault.bind(this);
        this.handleClick = this.handleClick.bind(this);
        this.selectAll = this.selectAll.bind(this);
        this.selectNone = this.selectNone.bind(this);
        this.updateRegions = this.updateRegions.bind(this);
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
        let regions = [];
        for (let i in this.state.regions) {
            regions[i] = this.state.regions[i];
            regions[i].selected = false;
        }
        this.setState({ regions: regions });
    }

    handleClick(e) {
        e.preventDefault();
        //console.log(e.target.id);
        let regions = this.state.regions;

        regions.filter(function (obj) {
            if (obj.uf == e.target.id) obj.selected = !obj.selected;
        });

        this.updateRegions(regions);
    }

    selectAll(e) {
        e.preventDefault();
        let regions = this.state.regions;

        regions.filter(function (obj) {
            obj.selected = true;
        });

        this.updateRegions(regions);
    }

    selectNone(e) {
        e.preventDefault();
        let regions = this.state.regions;

        regions.filter(function (obj) {
            obj.selected = false;
        });

        this.updateRegions(regions);
    }

    updateRegions(regions) {
        this.setState({ regions: regions }, function () {
            this.props.setRegions(this.state.regions);
        });
    }

    loadData() {
        this.loading(true);
        $.ajax("regioes/" + this.state.id, {
            data: {},
            success: function (data) {
                //console.log('regions', data);
                this.setState({ regions: data }, function () {
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

        let regions = this.state.regions.map(function (item) {
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
            { style: { display: this.state.regions.length > 0 ? 'block' : 'none' } },
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
            regions
        );
    }
}