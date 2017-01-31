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
        if(this.state.min != props.data.min || this.state.max != props.data.max){
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
            labels[i] = data[i].uf;
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
        //console.log(this.state.intervalos.length);
        if(this.state.intervalos.length > 0){
            let colors = [];
            for(let i in values){
                colors.push(convertHex(getColor(values[i]), 100));
            }
            return colors;
        }
    }

    render(){
        return (<canvas id="myChartBar" width="400" height="200"> </canvas>);
    }
}




