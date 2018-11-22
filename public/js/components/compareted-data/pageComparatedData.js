class PageComparatedData extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            ids: props.ids,
            min: props.from,
            max: props.to,
            regions: props.regions,
            region: null,
            abrangencia: props.abrangencia,
            abrangenciasOk: props.abrangenciasOk
        };

        this.selectRegion = this.selectRegion.bind(this);
        this.setAbrangencia = this.setAbrangencia.bind(this);
        this.setNomeAbrangencia = this.setNomeAbrangencia.bind(this);
        this.setRegions = this.setRegions.bind(this);
    }

    selectRegion(id) {
        this.setState({ region: id });
        console.log(id);
    }

    setAbrangencia(abrangencia) {
        $.ajax({
            method: 'GET',
            url: "get-regions/" + abrangencia,
            cache: false,
            success: function (data) {
                console.log('GET-REGIONS IN PGSERIE', data);
                this.setState({ regions: data, abrangencia: abrangencia });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    setNomeAbrangencia(nomeAbrangencia) {
        this.setState({ nomeAbrangencia: nomeAbrangencia });
    }

    setRegions(regions) {
        //console.log(regions);
        this.setState({ regions: regions }, function () {
            this.loadData();
            this.loadDataPeriodo();
            this.loadDataMaps();
        });
    }

    render() {

        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { className: 'col-md-6' },
                React.createElement(AbrangenciaSerie, {
                    abrangencia: this.state.abrangencia,
                    nomeAbrangencia: this.state.nomeAbrangencia,
                    setAbrangencia: this.setAbrangencia,
                    abrangenciasOk: this.state.abrangenciasOk,
                    setRegions: this.setRegions,
                    setNomeAbrangencia: this.setNomeAbrangencia

                    /*lang_parents={this.props.lang_parents}
                    lang_regions={this.props.lang_regions}
                    lang_uf={this.props.lang_uf}
                    lang_counties={this.props.lang_counties}
                    lang_filter_uf={this.props.lang_filter_uf}
                     lang_select_territories={this.props.lang_select_territories}
                    lang_search={this.props.lang_search}
                    lang_select_states={this.props.lang_select_states}
                    lang_selected_items={this.props.lang_selected_items}
                    lang_cancel={this.props.lang_cancel}
                    lang_continue={this.props.lang_continue}
                    lang_all={this.props.lang_all}
                    lang_remove_all={this.props.lang_remove_all}*/
                })
            ),
            React.createElement(Regions, {
                regions: this.state.regions,
                region: this.state.region,
                selectRegion: this.selectRegion,
                abrangencia: this.state.abrangencia
            }),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement(ChartLineComparatedSeries, {
                ids: this.state.ids,
                periodicidade: this.props.periodicidade,
                min: this.state.min,
                max: this.state.max,
                periodos: this.state.periodos,
                regions: this.state.regions,
                region: this.state.region,
                abrangencia: this.state.abrangencia,
                selectRegion: this.selectRegion
                /*typeRegion={this.props.typeRegion}
                typeRegionSerie={this.props.typeRegionSerie}
                intervalos={this.state.intervalos}*/
            }),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement(ListValoresComparatedSeries, {
                ids: this.state.ids,
                periodicidade: this.props.periodicidade,
                min: this.state.min,
                max: this.state.max,
                periodos: this.state.periodos,
                regions: this.state.regions,
                region: this.state.region,
                abrangencia: this.state.abrangencia,
                selectRegion: this.selectRegion
                /*typeRegion={this.props.typeRegion}
                typeRegionSerie={this.props.typeRegionSerie}
                intervalos={this.state.intervalos}*/
            })
        );
    }

}

ReactDOM.render(React.createElement(PageComparatedData, {
    ids: ids,
    from: from,
    to: to,
    regions: regions,
    abrangencia: abrangencia,
    abrangenciasOk: abrangenciasOk,
    periodicidade: periodicidade
}), document.getElementById('pageComparatedData'));