class ChartBarHtml5 extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            chart: props.chart,
            type: props.type, //1 - vertical 2 - horizontal
            values: props.values,
            valuesSelected: props.valuesSelected,
            icons: props.icons,
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


    render(){


        let total = this.total(this.state.values);

        if(this.props.type==1){
            let bars = this.state.values.map(function(item, index){

                return (
                    <li key={'itemChartBar'+this.state.chart+"_"+index}>
                        <span style={{height:(item.value*100/total)+'%'}} className='bg-pri'><strong className="hidden-xs">{item.value}</strong></span>
                        <div className="hidden-xs hidden-sm">{item.type}</div>
                        {/*<div className="hidden-md hidden-lg">E</div>*/}
                    </li>
                );
            }.bind(this));

            return(
                <div>
                    <ul className="chart" style={{height: '350px'}}>
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