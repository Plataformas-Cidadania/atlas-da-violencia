class AbrangenciaSerie extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            abrangencias: props.abrangencias ? props.abrangencias : [{ id: 1, titulo: this.props.lang_parents }, { id: 2, titulo: this.props.lang_regions }, { id: 3, titulo: this.props.lang_uf }, { id: 4, titulo: this.props.lang_counties }],
            regionsId: [0],
            nomeAbrangencia: props.nomeAbrangencia,
            abrangencia: props.abrangencia,
            abrangenciasOk: props.abrangenciasOk,
            optionsAbrangencia: [{ id: 1, title: 'País', plural: ' os Países', on: false, listAll: 1, height: '250px' }, { id: 2, title: 'Região', plural: 'as Regiões', on: false, listAll: 1, height: '250px' }, { id: 3, title: 'UF', plural: 'os Estados', on: false, listAll: 1, height: '400px' }, { id: 7, title: 'Território', plural: 'os Estados', on: false, listAll: 1, height: '400px' }, { id: 4, title: 'Município', plural: 'os Municípios', on: false, listAll: 0, height: '400px',
                filter: [{ id: 12, title: 'Acre' }, { id: 27, title: 'Alagoas' }, { id: 13, title: 'Amazonas' }, { id: 16, title: 'Amapá' }, { id: 29, title: 'Bahia' }, { id: 23, title: 'Ceará' }, { id: 53, title: 'Distrito Federal' }, { id: 32, title: 'Espirito Santo' }, { id: 52, title: 'Goiás' }, { id: 21, title: 'Maranhão' }, { id: 50, title: 'Mato Grosso do Sul' }, { id: 51, title: 'Mato Grosso' }, { id: 31, title: 'Minas Gerais' }, { id: 15, title: 'Pará' }, { id: 41, title: 'Paraná' }, { id: 25, title: 'Paraíba' }, { id: 26, title: 'Pernambuco' }, { id: 22, title: 'Piauí' }, { id: 33, title: 'Rio de Janeiro' }, { id: 24, title: 'Rio Grande do Norte' }, { id: 43, title: 'Rio Grande do Sul' }, { id: 11, title: 'Rondônia' }, { id: 14, title: 'Roraima' }, { id: 42, title: 'Santa Catarina' }, { id: 35, title: 'São Paulo' }, { id: 28, title: 'Sergipe' }, { id: 17, title: 'Tocantins' }]

            }]
        };

        this.setAbrangencia = this.setAbrangencia.bind(this);
        this.showRegions = this.showRegions.bind(this);
        this.activateOptionsAbrangencia = this.activateOptionsAbrangencia.bind(this);
        this.setRegions = this.setRegions.bind(this);
        this.loadWithRegions = this.loadWithRegions.bind(this);
    }

    componentDidMount() {
        this.activateOptionsAbrangencia();
    }

    componentWillReceiveProps(props) {

        if (this.state.nomeAbrangencia !== props.nomeAbrangencia) {
            this.setState({ nomeAbrangencia: props.nomeAbrangencia });
        }

        if (this.state.abrangencia !== props.abrangencia) {
            this.setState({ abrangencia: props.abrangencia }, function () {
                this.activateOptionsAbrangencia();
            });
        }
    }

    activateOptionsAbrangencia() {
        let optionsAbrangencia = this.state.optionsAbrangencia;
        let nomeAbrangencia = null;

        optionsAbrangencia.find(function (option) {
            option.on = option.id === parseInt(this.state.abrangencia);
            if (option.on) {
                nomeAbrangencia = option.title;
            }
            //console.log(this.state.abrangencia, option.id, option.on);
        }.bind(this));

        this.props.setNomeAbrangencia(nomeAbrangencia);

        //console.log('OPTIONS ABRANGÊNCIAS', optionsAbrangencia);

        /*this.setState({serieMarked: item.id, abrangencia: item.tipo_regiao}, function(){
            if(all){
                this.setState({loadingDefaultValues: true});
                this.loadDefaultValues();
                //this.submit();
                return;
            }
             this.loadPeriodos();
            $("#modalAbrangencias").modal();
        });*/
    }

    showRegions() {
        //this.props.showRegions();
        $("#modalAbrangencias").modal();
    }

    setAbrangencia(abrangencia) {
        this.props.setAbrangencia(abrangencia);
    }

    selectedAbrangencia() {
        let option = null;
        return this.state.optionsAbrangencia.find(function (op) {
            if (op.on) {
                option = op.id;
                return op;
            }
        });
        //return option;
    }

    setRegions(regions) {

        let regionsId = [];
        for (let i in regions) {
            regionsId.push(regions[i].id);
        }

        console.log(regionsId);

        //this.setState({regions: regionsId});

        if (regionsId.length == 0) {
            regionsId.push(0); //0 irá pegar todas as regiões da abrangência no backend
        }

        //this.props.setRegions(regionsId);
        this.setState({ regionsId: regionsId });
    }

    loadWithRegions() {
        this.props.setRegions(this.state.regionsId);
        $("#modalAbrangencias").modal('hide');
    }

    render() {

        let selectItems = React.createElement(SelectItems, {
            url: 'territorios',
            conditions: { id: this.state.id },
            option: this.selectedAbrangencia(),
            options: this.state.optionsAbrangencia,
            setItems: this.setRegions,

            lang_selected_items: this.props.lang_selected_items,
            lang_search: this.props.lang_search,
            lang_select_states: this.props.lang_select_states,
            lang_all: this.props.lang_all,
            lang_remove_all: this.props.lang_remove_all
        });

        let btnContinuar = React.createElement(
            'button',
            { className: 'btn btn-primary', onClick: this.loadWithRegions },
            this.props.lang_continue
        );
        /*let btnContinuar = <button type="button" className="btn btn-primary" onClick={() => this.submit()} disabled >Continuar</button>;
        if(this.state.regions.length > 0 && this.state.periodos.length > 0 && this.state.from && this.state.to && this.state.abrangencia && this.state.serieMarked){
            btnContinuar = <button type="button" className="btn btn-primary" onClick={() => this.submit()}  >Continuar</button>
        }*/

        let tituloAbrangencia = null;

        let filterRegions = null;
        filterRegions = React.createElement(
            'button',
            { className: 'btn btn-info', style: { marginLeft: '10px' }, onClick: this.showRegions },
            React.createElement('i', { className: 'fa fa-filter ' }),
            ' ',
            this.props.lang_filter_uf,
            ' ',
            this.state.nomeAbrangencia
        );
        /*if(this.state.abrangencia!==3){
            filterRegions = <button className="btn btn-info" style={{marginLeft: '10px'}} onClick={this.showRegions}><i className="fa fa-filter "/> Filtrar Regiões</button>;
        }*/

        let abrangencias = this.state.abrangencias.map(function (item) {

            if (item.id == this.state.abrangencia) {
                tituloAbrangencia = item.titulo;
            }
            if (!this.state.abrangenciasOk.includes(item.id)) {
                return React.createElement(
                    'button',
                    { key: 'abrangencia' + item.id, className: "btn btn-type btn-type-inactive", style: { marginLeft: '10px' } },
                    item.titulo
                );
            }
            return React.createElement(
                'button',
                { key: 'abrangencia' + item.id, className: "btn btn-type " + (this.state.abrangencia == item.id ? "btn-type-active" : ''), style: { marginLeft: '10px' }, onClick: () => this.setAbrangencia(item.id) },
                item.titulo
            );
        }.bind(this));

        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: 'col-md-12' },
                    abrangencias,
                    filterRegions
                )
            ),
            React.createElement(Modal, {
                id: 'modalAbrangencias',
                title: this.props.lang_select_territories,
                body: selectItems,
                buttons: React.createElement(
                    'div',
                    null,
                    React.createElement(
                        'button',
                        { type: 'button', className: 'btn btn-default', 'data-dismiss': 'modal' },
                        this.props.lang_cancel
                    ),
                    btnContinuar
                )
            })
        );
    }
}