class PgSerie extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            min: 0,
            max: 0
        };
        this.changePeriodo = this.changePeriodo.bind(this);
    }

    changePeriodo(min, max){
        this.setState({min: min, max: max});
    }

    render(){
        return(
            <div>
                <RangePeriodo changePeriodo={this.changePeriodo}/>
                <br/>
                <div id="mapid"></div>
                <br/><br/>
                <canvas id="myChart" width="400" height="200"> </canvas>
                <canvas id="myChartRadar" width="400" height="200"> </canvas>
                <ListValoresSeries min={this.state.min} max={this.state.max}/>
            </div>
        );
    }
}

ReactDOM.render(
    <PgSerie/>,
    document.getElementById('pgSerie')
);