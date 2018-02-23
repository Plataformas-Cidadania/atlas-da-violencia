class ChartBarHtml5 extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            chart: props.chart,
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
        
        
        let rendaFamiliar = 3000;

        let total = this.total(this.state.values);

        /*let bars2 = this.state.values.map(function(item, index){

            return (
                <li key={'itemChartBar'+this.state.chart+"_"+index}>
                    <span style={{height:(item.value*100/total)+'%'}} className={rendaFamiliar <= 227 ? 'bg-pri' : ''}><strong className="hidden-xs">{item.value}</strong></span>
                    <div className="hidden-xs hidden-sm">item.type</div>
                    <div className="hidden-md hidden-lg">E</div>
                </li>
            );
        }.bind(this);*/

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

        console.log(bars);

        
        return(
            <div className="container">
                {bars}
            </div>

        );
        
        /*return(
            <div className="container">
                <ul className="chart">
                    <li>
                        <span style={{height:'100%'}} className={rendaFamiliar <= 227 ? 'bg-pri' : ''}><strong className="hidden-xs">{total}</strong></span>
                        <div className="hidden-xs hidden-sm">Total</div>
                        <div className="hidden-md hidden-lg">E</div>
                    </li>
                    {bars2}
                    {/!*<li>
                        <span style={{height:'6.5%'}} className={rendaFamiliar > 227 && rendaFamiliar <= 648 ? 'bg-pri' : '' }><strong className="hidden-xs">R$ 648,00</strong></span>
                        <div className="hidden-xs hidden-sm">Pobre</div>
                        <div className="hidden-md hidden-lg">D</div>
                    </li>
                    <li>
                        <span style={{height:'10.3%'}} title="" className={rendaFamiliar > 648 && rendaFamiliar <= 1030 ? 'bg-pri' : ''}><strong className="hidden-xs">R$ 1.030,00</strong></span>
                        <div className="hidden-xs hidden-sm">Vulneravel</div>
                        <div className="hidden-md hidden-lg">C2</div>
                    </li>
                    <li>
                        <span style={{height:'15.4%'}} className={rendaFamiliar > 1030 && rendaFamiliar <= 1540 ? 'bg-pri' : ''}><strong className="hidden-xs">R$ 1.540,00</strong></span>
                        <div className="hidden-xs hidden-sm">Baixa Classe Média</div>
                        <div className="hidden-md hidden-lg">C1</div>
                    </li>
                    <li>
                        <span style={{height:'19.2%'}}  className={rendaFamiliar > 1540 && rendaFamiliar <= 1925 ? 'bg-pri' : ''}><strong className="hidden-xs">R$ 1.925,00</strong></span>
                        <div className="hidden-xs hidden-sm">Média Classe Média1</div>
                        <div className="hidden-md hidden-lg">B2</div>
                    </li>
                    <li>
                        <span style={{height:'28%'}}  className={rendaFamiliar > 1925 && rendaFamiliar <= 2813 ? 'bg-pri' : ''}><strong className="hidden-xs">R$ 2.813,00</strong></span>
                        <div className="hidden-xs hidden-sm">Alta Classe Média</div>
                        <div className="hidden-md hidden-lg">B1</div>
                    </li>
                    <li>
                        <span style={{height:'48%'}}  className={rendaFamiliar > 2813 && rendaFamiliar <= 4845 ? 'bg-pri' : ''}><strong className="hidden-xs">R$ 4.845,00</strong></span>
                        <div className="hidden-xs hidden-sm">Baixa Classe Alta</div>
                        <div className="hidden-md hidden-lg">A2</div>
                    </li>
                    <li>
                        <span style={{height:'100%'}} className={rendaFamiliar > 4845 && rendaFamiliar <= 12988 ? 'bg-pri' : ''}><strong className="hidden-xs">R$ 12.988,00</strong></span>
                        <div className="hidden-xs hidden-sm">Alta Classe Alta</div>
                        <div className="hidden-md hidden-lg">A1</div>
                    </li>*!/}
                </ul>
            </div>
        );*/
    }
}