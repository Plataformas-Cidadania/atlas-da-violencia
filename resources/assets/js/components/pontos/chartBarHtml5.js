class ChartBarHtml5 extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            chart: props.chart,
            type: props.type, //1 - vertical 2 - horizontal
            values: props.values,
            valuesSelected: props.valuesSelected,
            icons: props.icons,
            title: props.title,
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

        //console.log(this.state.values);


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
                    <h2>{this.state.title}</h2>
                    <div className="line-title-sm bg-pri"/><hr className="line-hr-sm"/>
                    <br/>
                    <br/>
                    <br/>

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
                        {/*<div className="col-xs-2 col-sm-1 col-md-1">
                            <div className="icon-bar">
                                <i className="fa fa-car"/>
                                <img src={"img/leaflet/"+this.state.icons[item.type]} alt=""/>
                            </div>
                        </div>*/}
                        <div className="col-xs-12">
                            <hr className="hr-bar"/>
                            <div className="row">
                                <div className="col-md-6">
                                    <p className="txt-bar">{item.titulo}</p>
                                </div>
                                <div className="col-md-6 text-right">
                                    <p className="txt-bar">{item.value} ({formatNumber(item.value*100/total, 2, ',', '.')+"%"})</p>
                                </div>
                            </div>
                            <div style={{backgroundColor: "#F4F4F4"}}>
                                <div className="width-bar" style={{width: (item.value*100/total)+"%"}} title={formatNumber(item.value*100/total, 2, ',', '.')+"%"}>&nbsp;</div>
                            </div>
                        </div>
                    </div>
                );
            }.bind(this));

            return(
                <div>
                    <h4>{this.state.title}</h4>
                    {/*<div className="line-title-sm bg-pri"/><hr className="line-hr-sm"/>*/}
                    {bars}
                </div>

            );

        }

        return(<div>Defina um tipo!</div>);


    }
}