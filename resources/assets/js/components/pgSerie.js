class PgSerie extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            min: 0,
            max: 0,
            periodos: [],
            showMap: true,
            showCharts: true,
            showRates: true,
            showTable: true,
            showCalcs: true,
            chartLine: true,
            chartRadar: false,
            chartBar:false,
            chartPie:false
        };
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
        this.showHide = this.showHide.bind(this);
    }

    changePeriodo(min, max){
        this.setState({min: min, max: max});
    }

    setPeriodos(periodos){
        this.setState({periodos: periodos});
    }

    changeChart(chart) {
        this.setState({chartLine:false, chartRadar:false, chartBar:false, chartPie:false}, function(){
            let chartChosen = {};
            chartChosen[chart] = true;
            this.setState(chartChosen);
        });
    }

    showHide(target){
        let value = this.state['show'+target];
        let inverseValue = !value;
        this.setState({['show'+target]:inverseValue});
    }

    render(){
        return(
            <div>
                
                <div className="row">
                    <div className="col-md-6 h3" style={{margin:0}}>
                        <img style={{marginLeft: '5px'}} src="imagens/links/8516-01.png" width="52" alt="" title=""/>
                        &nbsp;Homicídios no Brasil
                    </div>
                    <div className="col-md-6 text-right">
                        <div className="icons-groups icon-group-print" style={{marginLeft: '5px'}} title=""></div>
                        <div className={"icons-groups" + (this.state.showCalcs ? " icon-group-calc" : " icon-group-calc-disable")}
                             style={{marginLeft: '5px'}} onClick={() => this.showHide('Calcs')} title=""></div>
                        <div className={"icons-groups" + (this.state.showTable ? " icon-group-table" : " icon-group-table-disable")}
                             style={{marginLeft: '5px'}} onClick={() => this.showHide('Table')} title=""></div>
                        <div className={"icons-groups" + (this.state.showRates ? " icon-group-rate" : " icon-group-rate-disable")}
                             style={{marginLeft: '5px'}} onClick={() => this.showHide('Rates')} title=""></div>
                        <div className={"icons-groups" + (this.state.showCharts ? " icon-group-chart" : " icon-group-chart-disable")}
                             style={{marginLeft: '5px'}} onClick={() => this.showHide('Charts')} title=""></div>
                        <div className={"icons-groups" + (this.state.showMap ? " icon-group-map" : " icon-group-map-disable")}
                             style={{marginLeft: '5px'}} onClick={() => this.showHide('Map')} title=""></div>
                    </div>
                </div>
                <br/>
                <div className="line_title bg-pri"></div>

                <br/>

                <RangePeriodo changePeriodo={this.changePeriodo} setPeriodos={this.setPeriodos}/>
                <br/>

                <br/><hr/><br/>

                <div style={{display: this.state.showMap ? 'block' : 'none'}}>
                    <Map min={this.state.min} max={this.state.max} />
                    <br/><hr/><br/>
                </div>

                <div style={{display: this.state.showCharts ? 'block' : 'none'}}>
                    <div>
                        <div style={{textAlign: 'right'}}>
                            <div className={"icons-charts" + (this.state.chartLine ? " icon-chart-line" : " icon-chart-line-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.changeChart('chartLine')} title=""></div>

                            <div className={"icons-charts" + (this.state.chartBar ? " icon-chart-bar" : " icon-chart-bar-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.changeChart('chartBar')} title=""></div>

                            <div className={"icons-charts" + (this.state.chartRadar ? " icon-chart-radar" : " icon-chart-radar-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.changeChart('chartRadar')} title=""></div>

                            <div className={"icons-charts" + (this.state.chartPie ? " icon-chart-pie" : " icon-chart-pie-disable")}
                                 style={{marginLeft: '5px'}} onClick={() => this.changeChart('chartPie')} title=""></div>
                        </div>
                        <div style={{display: this.state.chartLine ? 'block' : 'none'}}>
                            <ChartLine min={this.state.min} max={this.state.max} periodos={this.state.periodos} />
                        </div>
                        <div style={{display: this.state.chartBar ? 'block' : 'none'}}>
                            <ChartBar min={this.state.min} max={this.state.max} />
                        </div>
                        <div style={{display: this.state.chartRadar ? 'block' : 'none'}}>
                            <ChartRadar min={this.state.min} max={this.state.max} />
                        </div>
                        <div style={{display: this.state.chartPie ? 'block' : 'none'}}>
                            <ChartPie min={this.state.min} max={this.state.max} />
                        </div>
                    </div>
                    <br/><hr/><br/>
                </div>

                <div style={{display: this.state.showRates ? 'block' : 'none'}}>
                    Taxas
                    <br/><hr/><br/>
                </div>

                <div style={{display: this.state.showTable ? 'block' : 'none'}}>
                    <ListValoresSeries min={this.state.min} max={this.state.max} />
                    <br/><hr/><br/>
                </div>

                <div style={{display: this.state.showCalcs ? 'block' : 'none'}}>
                    Cálculos
                </div>


            </div>
        );
    }
}

ReactDOM.render(
    <PgSerie/>,
    document.getElementById('pgSerie')
);