class FiltroUfs extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            regions: []
        };
    }

    render() {
        return React.createElement(
            'div',
            null,
            '...'
        );
    }
}

class FiltroRegions extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: this.props.id,
            regions: [],
            ufsSelected: [],
            regionsSelected: []
        };

        this.loadData = this.loadData.bind(this);
        this.loading = this.loading.bind(this);
        this.openRegion = this.openRegion.bind(this);
        this.selectUf = this.selectUf.bind(this);
        this.selectAll = this.selectAll.bind(this);
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

    loadData() {
        this.loading(true);
        $.ajax("regioes/" + this.state.id, {
            data: {},
            success: function (data) {
                console.log('regions', data);
                this.setState({ regions: data }, function () {
                    //this.classDefault();
                });
                this.loading(false);
                //periodos = data;
            }.bind(this),
            error: function (data) {
                console.log('erro');
            }.bind(this)
        });
    }

    openRegion(index) {
        let regions = this.state.regions;
        regions[index].open = !regions[index].open;
        this.setState({ regions: regions });
    }

    selectUf(codigo, uf, all) {
        let ufsSelected = this.state.ufsSelected;

        let remove = false;
        let index = null;
        ufsSelected.find(function (item, i) {
            if (item.uf == uf) {
                index = i;
                remove = true;
            }
        });

        if (remove) {
            ufsSelected.splice(index, 1);
        } else {
            ufsSelected.push({ codigo: codigo, uf: uf });
        }

        this.setState({ ufsSelected: ufsSelected });
    }

    selectAll(indexRegion) {
        let ufsSelected = this.state.ufsSelected;
        let regions = this.state.regions;

        regions[indexRegion].ufs.find(function (item, index) {
            let existe = false;
            for (let i in ufsSelected) {
                if (ufsSelected[i].uf == item.uf) {
                    existe = true;
                }
            }
            if (!existe) {
                ufsSelected.push({ codigo: item.codigo, uf: item.uf });
            }
        }.bind(this));

        regions[indexRegion].allUfsSelected = true;
        this.setState({ regions: regions });
    }

    render() {

        console.log(this.state.ufsSelected);

        let regions = this.state.regions.map(function (item, indexRegion) {

            let ufs = item.ufs.map(function (item) {

                let marcado = false;
                this.state.ufsSelected.find(function (i) {
                    if (i.uf == item.uf) {
                        marcado = true;
                    }
                });

                return React.createElement(
                    'li',
                    { key: item.sigla },
                    React.createElement(
                        'a',
                        null,
                        React.createElement('i', { className: 'fa fa-chevron-right', 'aria-hidden': 'true' }),
                        ' ',
                        item.uf,
                        React.createElement(
                            'div',
                            { className: 'menu-box-btn-square-li', onClick: () => this.selectUf(item.codigo, item.uf, false) },
                            React.createElement('i', { className: "fa " + (marcado ? "fa-check-square-o" : "fa-square-o"), 'aria-hidden': 'true' })
                        )
                    )
                );
            }.bind(this));

            return React.createElement(
                'div',
                { key: item.sigla, className: 'col-md-r' },
                React.createElement(
                    'div',
                    { className: 'menu-box' },
                    React.createElement(
                        'div',
                        { className: 'btn btn-primary menu-box-btn', onClick: () => this.openRegion(indexRegion) },
                        React.createElement('i', { className: "fa " + (item.open ? "fa-minus-square-o" : "fa-plus-square-o"), 'aria-hidden': 'true' }),
                        '\xA0',
                        item.region,
                        React.createElement(
                            'div',
                            { className: 'menu-box-btn-square' },
                            React.createElement('i', { className: 'fa fa-square-o', 'aria-hidden': 'true' })
                        )
                    ),
                    React.createElement(
                        'ul',
                        { style: { display: item.open ? 'block' : 'none' } },
                        React.createElement(
                            'li',
                            { onClick: () => this.selectAll(indexRegion) },
                            React.createElement(
                                'a',
                                null,
                                React.createElement('i', { className: 'fa fa-chevron-right', 'aria-hidden': 'true' }),
                                ' ',
                                React.createElement(
                                    'strong',
                                    null,
                                    'Todos'
                                ),
                                React.createElement(
                                    'div',
                                    { className: 'menu-box-btn-square-li' },
                                    React.createElement('i', { className: "fa " + (item.allUfsSelected ? "fa-check-square-o" : "fa-square-o"), 'aria-hidden': 'true' })
                                )
                            )
                        ),
                        ufs
                    )
                )
            );
        }.bind(this));

        return React.createElement(
            'div',
            { className: 'row' },
            regions
        );
    }
}