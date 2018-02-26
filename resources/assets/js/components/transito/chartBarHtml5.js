class ChartBarHtml5 extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            chart: props.chart,
            type: props.type, //1 - vertical 2 - horizontal
            values: props.values,
            valuesSelected: props.valuesSelected,
            icons: props.icons,
            show: props.show ? parseInt(props.show) : 1, //1 - valor, 2 - porcentagem, 3 - valor e porcentagem
        }
    }

    componentWillReceiveProps(props){
        if(props.values != this.state.values){
            this.setState({values: props.values});
        }
        if(props.icons != this.state.icons){
            this.setState({icons: props.icons});
        }
    }

    existe(array1, array2){

    }

    total(values){
        let total = 0;

        values.find(function(item){
            total += parseInt(item.value);
        });

        return total;
    }

    max(values) {
        let max = 0;

        values.find(function (item) {
            max = item.value > max ? item.value : max;
        });

        return max;
    }


    render(){


        let total = this.total(this.state.values);
        let max = this.max(this.state.values);

        if(this.props.type==1){
            let bars = this.state.values.map(function(item, index){

                let value = this.state.show===1 || this.state.show===3 ? item.value : null;
                let percent = this.state.show===2 || this.state.show===3 ? formatNumber(item.value*100/total, 2, ',', '.')+"%" : null;
                let parenteseAberto = this.state.show===3 ? '(' : null;
                let parenteseFechado = this.state.show===3 ? ')' : null;

                return (
                    <li key={'itemChartBar'+this.state.chart+"_"+index} style={{height:(item.value*100/max)+'%'}}>
                        <span style={{height:(item.value*100/max)+'%'}} className='bg-pri'><strong className="hidden-xs">{value} {parenteseAberto}{percent}{parenteseFechado}</strong></span>
                        <div className="hidden-xs hidden-sm">{item.type}</div>
                        {/*<div className="hidden-md hidden-lg">E</div>*/}
                    </li>
                );
            }.bind(this));

            return(
                <div>
                    <ul className="chart" style={{height: this.props.height}}>
                        {/*<li>
                            <span style={{height:'100%'}} className={rendaFamiliar <= 227 ? 'bg-pri' : ''}><strong className="hidden-xs">{total}</strong></span>
                            <div className="hidden-xs hidden-sm">Total</div>
                            <div className="hidden-md hidden-lg">E</div>
                        </li>*/}
                        {bars}

                    </ul>
                </div>
            );
        }

        if(this.props.type==2){
            let bars = this.state.values.map(function(item, index){

                return (
                    <div className="row" key={'itemChartBar'+this.state.chart+"_"+index}>
                        <div className="col-xs-1 col-sm-1 col-md-1">
                            <div className="bg-pri icon-bar">
                                {/*<i className="fa fa-car"/>*/}
                                <img src={"img/leaflet/"+this.state.icons[item.type]} alt=""/>
                            </div>
                        </div>
                        <div className="col-xs-8 col-sm-8 col-md-8">
                            <div style={{backgroundColor: "#F4F4F4"}}>
                                <div className="width-bar" style={{width: (item.value*100/total)+"%"}} title={formatNumber(item.value*100/total, 2, ',', '.')+"%"}>&nbsp;</div>
                            </div>
                        </div>
                        <div className="col-xs-3 col-sm-3 col-md-3">
                            <p className="txt-bar">{item.value} ({formatNumber(item.value*100/total, 2, ',', '.')+"%"})</p>
                        </div>
                    </div>
                );
            }.bind(this));

            return(
                <div className="container">
                    {bars}
                </div>

            );

        }

        return(<div>Defina um tipo!</div>);
        

    }
}