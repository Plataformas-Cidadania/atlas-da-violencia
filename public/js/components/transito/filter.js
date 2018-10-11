class Filter extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            types: [],
            filter_id: this.props.filter_id,
            search: '',
            showtypes: false,
            typesSelected: []
        };

        this.load = this.load.bind(this);
        this.clickSearch = this.clickSearch.bind(this);
        this.handleSearch = this.handleSearch.bind(this);
        this.addType = this.addType.bind(this);
        this.removeType = this.removeType.bind(this);
    }

    componentDidMount() {
        //this.setState({typesSelected: this.props.typesUrl});

        this.load();
    }

    load() {
        $.ajax({
            method: 'POST',
            url: 'valores-filtros',
            data: {
                search: this.state.search,
                filtro_id: this.state.filter_id
            },
            cache: false,
            success: function (data) {
                console.log(data);

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

    addType(item) {
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
                this.props.checkFilter(this.state.typesSelected);
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
            this.props.checkFilter(this.state.typesSelected);
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

            return React.createElement(
                'div',
                { key: 'cat_' + item.id, style: { cursor: 'pointer', color: color }, onClick: () => this.addType(item) },
                React.createElement(
                    'u',
                    null,
                    firstPiece
                ),
                lastPiece
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
            typesSelected = "";
        }

        return React.createElement(
            'div',
            null,
            React.createElement('input', { type: 'text', name: 'titleType', className: 'form-control input-sm', onClick: this.clickSearch, onChange: this.handleSearch, placeholder: 'Pesquise pelo tipo de Sexo' }),
            React.createElement(
                'div',
                { className: 'div-info', style: { border: "solid 1px #CCC", display: this.state.showtypes ? 'block' : 'none' } },
                types
            ),
            React.createElement('hr', { style: { margin: '10px 0' } }),
            React.createElement(
                'div',
                { style: { overflowX: "auto", width: "100%", height: "45px" } },
                React.createElement(
                    'div',
                    { style: { width: "1000px" } },
                    typesSelected
                )
            )
        );
    }
}