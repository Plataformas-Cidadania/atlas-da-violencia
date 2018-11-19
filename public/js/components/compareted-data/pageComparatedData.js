class PageComparatedData extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            ids: props.ids,
            min: props.from,
            max: props.to,
            regions: props.regions,
            abrangencia: props.abrangencia
        };
    }

    render() {

        return React.createElement(
            'div',
            null,
            React.createElement(ChartLineComparatedSeries, {
                ids: this.state.ids,
                periodicidade: this.props.periodicidade,
                min: this.state.min,
                max: this.state.max,
                periodos: this.state.periodos,
                regions: this.state.regions,
                abrangencia: this.state.abrangencia
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
    abrangenciasOk: abrangenciasOk
}), document.getElementById('pageComparatedData'));