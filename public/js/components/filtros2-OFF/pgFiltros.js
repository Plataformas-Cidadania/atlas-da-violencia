class PgFiltros extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            serieMarked: 0,
            periodos: [],
            from: 0,
            to: 0,
            regions: [],
            tema: this.props.tema_id,
            indicador: 0,
            abrangencia: 0,
            typeRegion: '',
            typeRegionSerie: '',
            tipoValores: ''
        };

        this.serieMarked = this.serieMarked.bind(this);
        this.loading = this.loading.bind(this);
        this.setIndicador = this.setIndicador.bind(this);
        this.setTema = this.setTema.bind(this);
        this.setAbrangencia = this.setAbrangencia.bind(this);
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
        this.setRegions = this.setRegions.bind(this);
    }

    loading(status) {
        this.setState({ loading: status });
    }

    setTema(tema) {
        this.setState({ tema: tema, indicador: 0, serieMarked: 0 });
    }

    setIndicador(indicador) {
        this.setState({ indicador: indicador, serieMarked: 0 });
    }

    setAbrangencia(abrangencia) {
        this.setState({ abrangencia: abrangencia, serieMarked: 0 });
    }

    setRegions(regions) {

        let regionsId = [];
        for (let i in regions) {
            regionsId.push(regions[i].id);
        }

        this.setState({ regions: regionsId, serieMarked: 0 });
    }

    changePeriodo(min, max) {
        this.setState({ from: min, to: max }, function () {
            //this.loadData();
        });
    }

    setPeriodos(periodos) {
        this.setState({ periodos: periodos });
        //console.log('setPeriodos', periodos);
    }

    /*setRegions(regions, typeRegion){
        this.setState({regions: regions, typeRegion: typeRegion});
    }*/

    serieMarked(id, typeRegionSerie, tipoValores) {
        //console.log('serieMarked', id, typeRegionSerie);
        this.setState({ serieMarked: id, typeRegionSerie: typeRegionSerie, tipoValores: tipoValores });
    }

    submit() {
        $('#formFiltros').submit();
    }

    render() {

        //console.log('pgFiltros - render() typeRegion:', this.state.typeRegion);
        //console.log('pgFiltros - render() typeRegionSerie:', this.state.typeRegionSerie);
        //console.log('pgFiltros - render() regions:', this.state.regions);

        //console.log(this.state.tipoValores);

        let indicadores = null;
        if (this.state.tema > 0) {
            indicadores = React.createElement(Indicadores, {
                tema_id: this.state.tema,
                setIndicador: this.setIndicador
            });
        }

        let abrangencia = null;
        if (this.state.indicador > 0) {
            abrangencia = React.createElement(Abrangencia, {
                tema_id: this.state.tema,
                setAbrangencia: this.setAbrangencia,
                setRegions: this.setRegions
            });
        }

        let seriesList = null;
        if (this.state.abrangencia > 0 && this.state.regions.length > 0) {
            seriesList = React.createElement(SeriesList, {
                url: 'get-series',
                select: 'mark-one',
                parameters: { tema_id: this.state.tema, indicador: this.state.indicador, abrangencia: this.state.abrangencia, serieMarked: this.state.serieMarked },
                serieMarked: this.serieMarked
            });
        }

        return React.createElement(
            'div',
            null,
            React.createElement(
                'h1',
                null,
                'Consultas'
            ),
            React.createElement('div', { className: 'line_title bg-pri' }),
            React.createElement('br', null),
            React.createElement(Temas, {
                tema_id: this.state.tema,
                setTema: this.setTema
            }),
            React.createElement('br', null),
            indicadores,
            React.createElement('br', null),
            abrangencia,
            React.createElement('br', null),
            seriesList,
            React.createElement('br', null),
            React.createElement(RangePeriodo, {
                id: this.state.serieMarked,
                abrangencia: this.state.abrangencia,
                changePeriodo: this.changePeriodo,
                setPeriodos: this.setPeriodos,
                loading: this.loading,

                style: { display: this.state.serieMarked > 0 ? 'block' : 'none' }
            }),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement(
                'button',
                {
                    disabled: this.state.regions.length == 0,
                    className: 'btn btn-success btn-lg',
                    style: { display: this.state.serieMarked > 0 ? 'block' : 'none', margin: 'auto', minWidth: '350px' },
                    onClick: this.submit },
                'Continuar'
            ),
            React.createElement(
                'form',
                { id: 'formFiltros', style: { display: 'none' }, action: 'dados-series', method: 'POST' },
                React.createElement('input', { type: 'hidden', name: '_token', value: $('meta[name="csrf-token"]').attr('content') }),
                React.createElement('input', { type: 'hidden', name: 'id', value: this.state.serieMarked }),
                React.createElement('input', { type: 'hidden', name: 'from', value: this.state.from }),
                React.createElement('input', { type: 'hidden', name: 'to', value: this.state.to }),
                React.createElement('input', { type: 'hidden', name: 'periodos', value: this.state.periodos }),
                React.createElement('input', { type: 'hidden', name: 'regions', value: this.state.regions }),
                React.createElement('input', { type: 'hidden', name: 'abrangencia', value: this.state.abrangencia })
            )
        );
    }
}

ReactDOM.render(React.createElement(PgFiltros, { tema_id: tema_id }), document.getElementById('filtros'));