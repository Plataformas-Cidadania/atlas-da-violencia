

class FiltroRegions extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: this.props.id,
            regions: [],
            ufsSelected: [],
            regionsSelected: [],
            typeSelected: '',
            itemSelected: ''
        };

        this.loadData = this.loadData.bind(this);
        this.loading = this.loading.bind(this);
        this.openRegion = this.openRegion.bind(this);
        this.selectUf = this.selectUf.bind(this);
        this.selectAllUf = this.selectAllUf.bind(this);
        this.selectRegion = this.selectRegion.bind(this);
        this.verifyTypeSelected = this.verifyTypeSelected.bind(this);
        this.callSelected = this.callSelected.bind(this);
        this.clearRegionsSelected = this.clearRegionsSelected.bind(this);
        this.clearUfsSelected = this.clearUfsSelected.bind(this);
        this.verifyExistTypeSelected = this.verifyExistTypeSelected.bind(this);
        this.selectRegions = this.selectRegions.bind(this);
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

    selectRegion(region) {

        let regions = this.state.regions;

        regions.find(function (item) {
            if (item.region == region) {
                //console.log(item);
                item.selected = !item.selected;
            }
        });
        regions = this.clearUfsSelected(regions);

        let typeSelected = 'region';
        let regionsSelected = this.selectRegions(regions, typeSelected);

        if (this.verifyExistTypeSelected(regions)) {
            typeSelected = '';
        }

        this.props.setRegions(regionsSelected, typeSelected);
        this.setState({ regions: regions, typeSelected: typeSelected, regionsSelected: regionsSelected });
    }

    selectUf(uf) {

        let regions = this.state.regions;

        regions.find(function (region) {
            region.allUfsSelected = false;
            let qtdSelected = 0;
            region.ufs.find(function (item) {
                if (item.uf == uf) {
                    item.selected = !item.selected;
                }
                if (item.selected) {
                    qtdSelected++;
                }
            });
            if (qtdSelected == region.ufs.length) {
                region.allUfsSelected = true;
            }
        });
        regions = this.clearRegionsSelected(regions);

        let typeSelected = 'uf';
        let regionsSelected = this.selectRegions(regions, typeSelected);

        if (this.verifyExistTypeSelected(regions)) {
            typeSelected = '';
        }

        this.props.setRegions(regionsSelected, typeSelected);
        this.setState({ regions: regions, typeSelected: typeSelected, regionsSelected: regionsSelected });
    }

    selectAllUf(region) {
        let regions = this.state.regions;

        regions.find(function (item) {
            item.selected = false; //para desmarcar as regiões selecionadas que são to tipo region e não uf
            if (item.region == region) {
                let all = !item.allUfsSelected;
                item.ufs.find(function (uf) {
                    item.allUfsSelected = all;
                    uf.selected = all;
                });
            }
        });

        regions = this.clearRegionsSelected(regions);

        let typeSelected = 'uf';
        let regionsSelected = this.selectRegions(regions, typeSelected);

        if (this.verifyExistTypeSelected(regions)) {
            typeSelected = '';
        }

        this.props.setRegions(regionsSelected, typeSelected);
        this.setState({ regions: regions, typeSelected: typeSelected, regionsSelected: regionsSelected });
    }

    verifyTypeSelected(type, item) {
        //console.log(type,item);
        if (this.state.typeSelected != type && this.state.typeSelected != '') {
            //itemSelected será usado no "Continuar" do modal
            this.setState({ typeSelected: type, itemSelected: item }, function () {
                $('#modalInfo').modal('show');
            });
        } else {
            //this.setState({typeSelected: type, itemSelected: item}, function(){
            this.callSelected(type, item);
            //});
        }
    }

    callSelected(type, item) {
        //console.log(type, item);
        if (type == 'region') {
            this.selectRegion(item);
            return;
        }
        if (type == 'uf') {
            this.selectUf(item);
        }
    }

    clearRegionsSelected(regions) {
        //let regions = this.state.regions;

        regions.find(function (region) {
            region.selected = false;
        });

        return regions;

        //this.setState({regions: regions});
    }

    clearUfsSelected(regions) {
        //let regions = this.state.regions;

        regions.find(function (region) {
            region.allUfsSelected = false;
            region.ufs.find(function (uf) {
                uf.selected = false;
            });
        });

        return regions;

        //this.setState({regions: regions});
    }

    //verifica se existe algum item marcado para resetar o type selected
    verifyExistTypeSelected(regions) {
        let qtd = 0;
        regions.find(function (region) {
            if (region.selected) {
                qtd++;
                return;
            }
            region.ufs.find(function (uf) {
                if (uf.selected) {
                    qtd++;
                    return;
                }
            });
        });

        return qtd == 0;
    }

    selectRegions(regions, typeSelected) {
        let regionsSelected = [];

        regions.find(function (region) {
            if (typeSelected == "uf") {
                region.ufs.find(function (uf) {
                    if (uf.selected) {
                        regionsSelected.push(uf.codigo);
                    }
                });
            }
            if (typeSelected == "region") {
                if (region.selected) {
                    regionsSelected.push(region.codigo);
                }
            }
        });

        return regionsSelected;
    }

    render() {

        //console.log(this.state.typeSelected);
        console.log(this.state.regionsSelected);
        //console.log(this.state.regions);

        let regions = this.state.regions.map(function (region, indexRegion) {

            let ufs = region.ufs.map(function (item) {

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
                            { className: 'menu-box-btn-square-li', onClick: () => this.verifyTypeSelected('uf', item.uf) },
                            React.createElement('i', { className: "fa " + (item.selected ? "fa-check-square-o" : "fa-square-o"), 'aria-hidden': 'true' })
                        )
                    )
                );
            }.bind(this));

            return React.createElement(
                'div',
                { key: region.sigla, className: 'col-md-r' },
                React.createElement(
                    'div',
                    { className: 'menu-box' },
                    React.createElement(
                        'div',
                        { className: 'btn btn-primary menu-box-btn' },
                        React.createElement(
                            'div',
                            { style: { float: 'left', width: '80%' }, onClick: () => this.openRegion(indexRegion) },
                            React.createElement('i', { className: "fa " + (region.open ? "fa-minus-square-o" : "fa-plus-square-o"), 'aria-hidden': 'true' }),
                            '\xA0',
                            region.region
                        ),
                        React.createElement(
                            'div',
                            { className: 'menu-box-btn-square', style: { width: '20%' } },
                            React.createElement('i', { onClick: () => this.verifyTypeSelected('region', region.region),
                                className: "fa " + (region.selected ? "fa-check-square-o" : "fa-square-o"), 'aria-hidden': 'true' })
                        )
                    ),
                    React.createElement(
                        'ul',
                        { style: { display: region.open ? 'block' : 'none' } },
                        React.createElement(
                            'li',
                            { onClick: () => this.selectAllUf(region.region) },
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
                                    React.createElement('i', { className: "fa " + (region.allUfsSelected ? "fa-check-square-o" : "fa-square-o"), 'aria-hidden': 'true' })
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
            regions,
            React.createElement(
                'div',
                { className: 'modal fade', id: 'modalInfo', role: 'dialog', style: { zIndex: '99999999999999999999' } },
                React.createElement(
                    'div',
                    { className: 'modal-dialog' },
                    React.createElement(
                        'div',
                        { className: 'modal-content' },
                        React.createElement(
                            'div',
                            { className: 'modal-header' },
                            React.createElement(
                                'button',
                                { type: 'button', className: 'close', 'data-dismiss': 'modal' },
                                '\xD7'
                            ),
                            React.createElement(
                                'h4',
                                { className: 'modal-title' },
                                React.createElement('i', { className: 'fa fa-exclamation-triangle', style: { color: '#860000' } }),
                                ' Aviso'
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'modal-body' },
                            'N\xE3o \xE9 permitido marca\xE7\xF5es de tipos diferentes. Se continuar ir\xE1 desmarcar todos os tipos anteriores.'
                        ),
                        React.createElement(
                            'div',
                            { className: 'modal-footer' },
                            React.createElement(
                                'button',
                                { className: 'btn btn-default', 'data-dismiss': 'modal' },
                                'Cancelar'
                            ),
                            React.createElement(
                                'button',
                                { className: 'btn btn-default', 'data-dismiss': 'modal', onClick: () => this.callSelected(this.state.typeSelected, this.state.itemSelected) },
                                'Continuar'
                            )
                        )
                    )
                )
            )
        );
    }
}