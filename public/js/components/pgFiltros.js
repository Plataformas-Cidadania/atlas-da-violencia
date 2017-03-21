
class PgFiltros extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            serieMarked: 0,
            periodos: [],
            from: 0,
            to: 0,
            regions: [],
            typeRegion: ''
        };

        this.serieMarked = this.serieMarked.bind(this);
        this.loading = this.loading.bind(this);
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
        this.setRegions = this.setRegions.bind(this);
    }

    loading(status) {
        this.setState({ loading: status });
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

    setRegions(regions, typeRegion) {

        this.setState({ regions: regions, typeRegion: typeRegion });

        /*let regionsSelected = regions.map(function(item){
           if(item.selected){
               return item.uf;
           }
        });*/
        /*let regionsSelected = [];
        for(let i in regions){
            if(regions[i].selected)
                regionsSelected.push(regions[i].uf)
        }
        this.setState({regions: regionsSelected});*/
        //console.log('setRegions', regions);
    }

    serieMarked(id) {
        this.setState({ serieMarked: id });
    }

    submit() {
        $('#formFiltros').submit();
    }

    render() {

        console.log(this.state.typeRegion, this.state.regions);

        return React.createElement(
            'div',
            null,
            React.createElement(
                'h1',
                null,
                'Filtros - ',
                this.props.titulo
            ),
            React.createElement(SeriesList, {
                url: 'listar-series-relacionadas',
                select: 'mark-one',
                parameters: { id: this.props.serie_id },
                serieMarked: this.serieMarked
            }),
            React.createElement('br', null),
            React.createElement(RangePeriodo, {
                id: this.state.serieMarked,
                changePeriodo: this.changePeriodo,
                setPeriodos: this.setPeriodos,
                loading: this.loading,

                style: { display: this.state.serieMarked > 0 ? 'block' : 'none' }
            }),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement(FiltroRegions, {
                id: this.state.serieMarked,
                setRegions: this.setRegions,
                loading: this.loading
            }),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement(
                'button',
                {
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
                React.createElement('input', { type: 'hidden', name: 'regions', value: this.state.typeRegion })
            )
        );
    }
}

ReactDOM.render(React.createElement(PgFiltros, { serie_id: serie_id, titulo: titulo }), document.getElementById('filtros'));