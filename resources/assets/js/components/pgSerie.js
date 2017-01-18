class PgSerie extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            min: 0,
            max: 0,
            periodos: []
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

    render(){
        return(
            <div>
                <RangePeriodo changePeriodo={this.changePeriodo} setPeriodos={this.setPeriodos}/>
                <br/>
                <div id="mapid"></div>
                <br/><br/>
                <ChartLine min={this.state.min} max={this.state.max} periodos={this.state.periodos} />
                <ChartRadar min={this.state.min} max={this.state.max} />
                <ListValoresSeries min={this.state.min} max={this.state.max} />
            </div>
        );
    }
}

ReactDOM.render(
    <PgSerie/>,
    document.getElementById('pgSerie')
);