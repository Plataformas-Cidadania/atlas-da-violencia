class PgSerie extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            min: 0,
            max: 0,
            periodos: [],
            chartLine: true,
            chartRadar: false,
            chartBar:false
        };
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
    }

    changePeriodo(min, max){
        this.setState({min: min, max: max});
    }

    setPeriodos(periodos){
        this.setState({periodos: periodos});
    }

    changeChart(chart) {
        this.setState({chartLine:false, chartRadar:false, chartBar:false}, function(){
            let chartLine = {};
            chartLine[chart] = true;
            this.setState(chartLine);
        });
    }


    render(){
        return(
            <div>
                <RangePeriodo changePeriodo={this.changePeriodo} setPeriodos={this.setPeriodos}/>
                <br/>
                <div id="mapid"></div>
                <br/><br/>
                <div style={{textAlign: 'right'}}>
                    <i className="fa fa-3x fa-line-chart" onClick={() => this.changeChart('chartLine')}
                       style={{color: this.state.chartLine ? 'green' : '', cursor:'pointer'}}> </i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <i className="fa fa-3x fa-bar-chart" onClick={() => this.changeChart('chartBar')}
                       style={{color: this.state.chartBar ? 'green' : '', cursor:'pointer'}}> </i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <i className="fa fa-3x fa-star" onClick={() => this.changeChart('chartRadar')}
                       style={{color: this.state.chartRadar ? 'green' : '', cursor:'pointer'}}> </i>&nbsp;
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
                <br/><br/>
                <ListValoresSeries min={this.state.min} max={this.state.max} />
            </div>
        );
    }
}

ReactDOM.render(
    <PgSerie/>,
    document.getElementById('pgSerie')
);