class Region extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            types: [],
            search: '',
            showtypes: false,
            typesSelected: [],
            tipoTerritorioSelecionado: props.tipoTerritorioSelecionado,
            tiposTerritorios: ['', 'Países', 'Regiões', 'Estados', 'Municípios']
        };

        this.load = this.load.bind(this);
        this.clickSearch = this.clickSearch.bind(this);
        this.handleSearch = this.handleSearch.bind(this);
        this.addType = this.addType.bind(this);
        this.removeType = this.removeType.bind(this);
        this.defaultTypes = this.defaultTypes.bind(this);
    }

    componentDidMount() {
        //this.setState({typesSelected: this.props.typesUrl});

        this.load();
        this.defaultTypes();
    }

    defaultTypes() {
        $.ajax({
            method: 'POST',
            url: 'default-regions',
            data: {
                ids: this.props.codigoTerritorioSelecionado,
                tipo_territorio: this.props.tipoTerritorioSelecionado
            },
            cache: false,
            success: function (data) {
                //console.log('DEFAULT REGIONS', data);

                data.find(function (item) {
                    //console.log(item);
                    this.addType(item, false); //btnFilter = false -> assim não irá habilitar o botão de filter para os filtros default
                }.bind(this));
            }.bind(this),
            error: function (xhr, status, err) {
                console.error(status, err.toString());
                this.setState({ loading: false });
            }.bind(this)
        });
    }

    componentWillReceiveProps(props) {
        if (props.tipoTerritorioSelecionado != this.state.tipoTerritorioSelecionado) {
            this.setState({ tipoTerritorioSelecionado: props.tipoTerritorioSelecionado });
        }
    }

    load() {
        if (this.state.search.length === 0) {
            return;
        }
        $.ajax({
            method: 'POST',
            url: 'regions',
            data: {
                search: this.state.search,
                tipo_territorio: this.state.tipoTerritorioSelecionado
            },
            cache: false,
            success: function (data) {
                //console.log(data);

                //importar categorias passadas pela url//////////////
                let typesUrl = this.props.typesUrl;
                let typesSelected = this.state.typesSelected;
                for (let i in data) {
                    for (let j in typesUrl) {
                        if (data[i].id == typesUrl[j]) {
                            let add = true;
                            for (let k in typesSelected) {
                                //console.log(typesUrl[j], typesSelected[k].id);
                                if (typesUrl[j] == typesSelected[k].id) {
                                    add = false;
                                }
                            }
                            if (add) {
                                typesSelected.push(data[i]);
                            }
                        }
                    }
                }
                //console.log('typesSelected', typesSelected);
                //console.log('typesUrl', this.props.typesUrl);
                ////////////////////////////////////////////////////

                this.setState({ types: data, typesSelected: typesSelected, loading: false });
                //this.setState({loading: false, ads:data})
            }.bind(this),
            error: function (xhr, status, err) {
                console.error(status, err.toString());
                this.setState({ loading: false });
            }.bind(this)
        });
    }

    clickSearch() {
        let showtypes = !this.state.showtypes;
        this.setState({ showtypes: showtypes }, function () {
            this.load();
        });
    }

    handleSearch(e) {
        this.setState({ search: e.target.value }, function () {
            this.load();
        });
    }

    addType(item, enableBtnFilter = true) {
        //console.log('addType', item);
        let add = true;
        this.state.typesSelected.find(function (cat) {
            if (item.title == cat.title) {
                add = false;
            }
        });
        if (add) {
            let typesSelected = this.state.typesSelected;
            typesSelected.push(item);
            //console.log('addType - typesSelected', typesSelected);
            this.setState({ showtypes: false });
            this.setState({ typesSelected: typesSelected }, function () {
                this.props.checkRegion(this.state.typesSelected, enableBtnFilter);
            });
        }
    }

    removeType(e) {

        let typesSelected = this.state.typesSelected;
        let type = {};
        typesSelected.find(function (item) {
            if (item.id == e.target.id) {
                type = item;
            }
        });
        let index = typesSelected.indexOf(type);
        typesSelected.splice(index, 1);
        this.setState({ typesSelected: typesSelected }, function () {
            this.props.checkRegion(this.state.typesSelected, true);
        });
    }

    render() {

        let types = this.state.types.map(function (item) {
            let sizeSearch = this.state.search.length;
            let firstPiece = item.title.substr(0, sizeSearch);
            let lastPiece = item.title.substr(sizeSearch);

            let color = '';
            this.state.typesSelected.find(function (cat) {
                if (item.title == cat.title) {
                    color = '#b7b7b7';
                    return;
                }
            });

            let sigla_uf = null;
            //console.log(item.tipo_territorio);
            if (item.tipo_territorio == "4") {
                sigla_uf = "(" + item.sigla + ")";
            }

            return React.createElement(
                'div',
                { key: 'region_' + item.id + Math.floor(Math.random() * 1000 + 1), style: { cursor: 'pointer', color: color }, onClick: () => this.addType(item) },
                React.createElement(
                    'u',
                    null,
                    firstPiece
                ),
                lastPiece,
                ' ',
                sigla_uf
            );
        }.bind(this));

        let typesSelected = this.state.typesSelected.map(function (item) {
            return React.createElement(
                'button',
                { key: "btn_type_" + item.id, id: item.id, onClick: this.removeType, type: 'button', className: 'btn btn-success btn-xs btn-remove', style: { margin: "0 5px 5px 0" } },
                item.title,
                ' ',
                React.createElement('i', { className: 'fa fa-remove' })
            );
        }.bind(this));

        //console.log(typesSelected);

        if (typesSelected.length === 0) {
            typesSelected = "Pesquise pelo tipo de locomoção";
        }

        let aviso_territorios_pesquisa = null;
        if (this.state.tipoTerritorioSelecionado) {
            aviso_territorios_pesquisa = React.createElement(
                'div',
                { className: 'text-warning' },
                'Pesquisando por ',
                this.state.tiposTerritorios[this.state.tipoTerritorioSelecionado]
            );
        }

        return React.createElement(
            'div',
            null,
            typesSelected,
            React.createElement('hr', { style: { margin: '10px 0' } }),
            React.createElement('input', { type: 'text', name: 'titleType', className: 'form-control input-sm', onClick: this.clickSearch, onChange: this.handleSearch }),
            React.createElement(
                'div',
                { className: 'div-info', style: { border: "solid 1px #CCC", display: this.state.showtypes ? 'block' : 'none' } },
                aviso_territorios_pesquisa,
                types
            )
        );
    }
}