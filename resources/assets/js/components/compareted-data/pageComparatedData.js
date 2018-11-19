class PageComparatedData extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            ids: props.ids,
            min: props.from,
            max: props.to,
            regions: props.regions,
            region: null,
            abrangencia: props.abrangencia,
        }

        this.selectRegion = this.selectRegion.bind(this);

    }

    selectRegion(id){
        this.setState({region: id});
        console.log(id);
    }


    render(){


        return(
            <div>
                <Regions 
                    regions={this.state.regions}
                    region={this.state.region}
                    selectRegion={this.selectRegion}
                    abrangencia={this.state.abrangencia}
                />
                <br/><br/>
                <ChartLineComparatedSeries
                    ids={this.state.ids}
                    periodicidade={this.props.periodicidade}
                    min={this.state.min}
                    max={this.state.max}
                    periodos={this.state.periodos}
                    regions={this.state.regions}
                    region={this.state.region}
                    abrangencia={this.state.abrangencia}
                    selectRegion={this.selectRegion}
                    /*typeRegion={this.props.typeRegion}
                    typeRegionSerie={this.props.typeRegionSerie}
                    intervalos={this.state.intervalos}*/
                />
            </div>
        );
    }

}

ReactDOM.render(
    <PageComparatedData
        ids={ids}
        from={from}
        to={to}
        regions={regions}
        abrangencia={abrangencia}
        abrangenciasOk={abrangenciasOk}
        periodicidade={periodicidade}
    />,
    document.getElementById('pageComparatedData')
);