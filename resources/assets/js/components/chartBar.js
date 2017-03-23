myChartBar = undefined;

class ChartBar extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            serie: this.props.serie,
            data: {},
            min: 0,
            max: 0,
            intervalos: this.props.intervalos
        };
        //this.loadData = this.loadData.bind(this);
        this.loadChart = this.loadChart.bind(this);
    }

    componentWillReceiveProps(props){
        if(this.state.min != props.data.min || this.state.max != props.data.max || this.state.intervalos != props.intervalos){
            /*this.setState({min: props.min, max: props.max}, function(){
                if(myChartBar){
                    this.chartDestroy();
                }
                this.loadData();
            });*/
            this.setState({min: props.data.min, max: props.data.max, data: props.data.values, intervalos: props.intervalos}, function(){
                if(myChartBar){
                    this.chartDestroy();
                }
                this.loadChart(this.state.data);
            });
        }
    }

    /*loadData(){
        let _this = this;
        $.ajax("valores-regiao/"+this.state.min+"/"+this.state.max, {
            data: {},
            success: function(data){
                //console.log(data);
                _this.loadChartBar(data);
            },
            error: function(data){
                console.log('erro');
            }
        })
    }*/

    loadChart(data){
        //console.log(data);
        let labels = [];
        let values = [];
        for(let i in data){
            labels[i] = data[i].sigla;
            values[i] = data[i].total;
        }

        //console.log(values);

        let canvas2 = document.getElementById('myChartBar');
        let colors = this.getColors(values);
        let dataChart = {
            labels: labels,
            datasets: [
                {
                    label: this.state.serie,
                    backgroundColor: colors,
                    borderColor: "rgba(179,181,198,1)",
                    pointBackgroundColor: "rgba(179,181,198,1)",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgba(179,181,198,1)",
                    data: values
                }
            ]
        };

        let options2 = {
            scale: {
                reverse: false,
                ticks: {
                    beginAtZero: true
                }
            }
        };

        myChartBar = new Chart(canvas2, {
            type: 'bar',
            data: dataChart,
            options: options2
        });

    }

    chartDestroy(){
        myChartBar.destroy();
    }

    getColors(values){
        //console.log('chartbar - getcolors - intervalos', this.state.intervalos.length);
        if(this.state.intervalos.length > 0){
            let colors = [];
            for(let i in values){
                colors.push(convertHex(getColor(values[i], intervalos), 100));
            }
            return colors;
        }
    }

    render(){
        return (
            <div>
                <div style={{textAlign: 'center', clear: 'both'}}>
                    <button className="btn btn-primary btn-lg bg-pri" style={{border:'0'}}>{this.state.max}</button>
                    <div style={{marginTop:'-19px'}}>
                        <i className="fa fa-sort-down fa-2x" style={{color:'#3498DB'}} />
                    </div>
                </div>
                <br/>
                <canvas id="myChartBar" width="400" height="200"> </canvas>
            </div>
        );
    }
}




