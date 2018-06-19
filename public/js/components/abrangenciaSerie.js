class AbrangenciaSerie extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            abrangencias: props.abrangencias ? props.abrangencias : [{ id: 2, titulo: 'Regiões' }, { id: 3, titulo: 'UF' }, { id: 4, titulo: 'Municípios' }],
            abrangencia: props.abrangencia,
            abrangenciasOk: props.abrangenciasOk,
            optionsAbrangencia: [{ id: 1, title: 'País', plural: ' os Países', on: false, listAll: 1, height: '250px' }, { id: 2, title: 'Região', plural: 'as Regiões', on: false, listAll: 1, height: '250px' }, { id: 3, title: 'UF', plural: 'os Estados', on: false, listAll: 1, height: '400px' }, { id: 7, title: 'Território', plural: 'os Estados', on: false, listAll: 1, height: '400px' }, { id: 4, title: 'Município', plural: 'os Municípios', on: false, listAll: 0, height: '400px',
                filter: [{ id: 12, title: 'Acre' }, { id: 27, title: 'Alagoas' }, { id: 13, title: 'Amazonas' }, { id: 16, title: 'Amapá' }, { id: 29, title: 'Bahia' }, { id: 23, title: 'Ceará' }, { id: 53, title: 'Distrito Federal' }, { id: 32, title: 'Espirito Santo' }, { id: 52, title: 'Goiás' }, { id: 21, title: 'Maranhão' }, { id: 50, title: 'Mato Grosso do Sul' }, { id: 51, title: 'Mato Grosso' }, { id: 31, title: 'Minas Gerais' }, { id: 15, title: 'Pará' }, { id: 41, title: 'Paraná' }, { id: 25, title: 'Paraíba' }, { id: 26, title: 'Pernambuco' }, { id: 22, title: 'Piauí' }, { id: 33, title: 'Rio de Janeiro' }, { id: 24, title: 'Rio Grande do Norte' }, { id: 43, title: 'Rio Grande do Sul' }, { id: 11, title: 'Rondônia' }, { id: 14, title: 'Roraima' }, { id: 42, title: 'Santa Catarina' }, { id: 35, title: 'São Paulo' }, { id: 28, title: 'Sergipe' }, { id: 17, title: 'Tocantins' }]

            }]
        };

        this.setAbrangencia = this.setAbrangencia.bind(this);
        this.showRegions = this.showRegions.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.abrangencia !== props.abrangencia) {
            this.setState({ abrangencia: props.abrangencia });
        }
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
        this.props.setRegions(regionsId);
    }

    render() {

        let selectItems = React.createElement(SelectItems, {
            url: 'territorios',
            conditions: { id: this.state.id },
            option: this.selectedAbrangencia(),
            options: this.state.optionsAbrangencia,
            setItems: this.setRegions
        });

        let btnContinuar = React.createElement(
            'button',
            { className: 'btn btn-primary' },
            'Continuar'
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
            ' Filtrar Regi\xF5es'
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
                title: 'Selecione os Territ\xF3rios',
                body: selectItems,
                buttons: React.createElement(
                    'div',
                    null,
                    React.createElement(
                        'button',
                        { type: 'button', className: 'btn btn-default', 'data-dismiss': 'modal' },
                        'Cancelar'
                    ),
                    btnContinuar
                )
            })
        );
    }
}