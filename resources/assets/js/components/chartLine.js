class ChartLine extends React.Component{
    constructor(props){
        super(props);
        this.loadData = this.loadData.bind(this);
    }

    componentWillReceiveProps(props){
        this.setState({min: props.min, max: props.max}, function(){
            if(this.myLineChart!=undefined){
                this.chartDestroy();
            }
            this.loadData();
        });
    }

    loadData(){
        let _this = this;
        $.ajax("periodo/"+this.state.min+"/"+this.state.max, {
            data: {},
            success: function(data){
                //console.log(data);
                _this.loadChartLine(data);
            },
            error: function(data){
                console.log('erro');
            }
        })
    }

    loadChartLine(data){
        let labels = [];
        let values = [];
        for(let i in data){
            labels[i] = data[i].periodo;
            values[i] = data[i].total;
        }

        let canvas = document.getElementById('myChartLine');
        let dataChart = {
            //labels: ["January", "February", "March", "April", "May", "June", "July"],
            labels: labels,
            datasets: [
                {
                    label: "Homicidios no Brasil",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(75,192,192,0.4)",
                    borderColor: "rgba(75,192,192,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(75,192,192,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 5,
                    pointHitRadius: 10,
                    data: values,
                }
            ]
        };

        let option = {
            showLines: true
        };
        let myLineChart = Chart.Line(canvas,{
            data:dataChart,
            options:option
        });

        this.setState({myLineChart: myLineChart});

    }

    chartDestroy(){
        this.state.myLineChart.destroy();
    }

    render(){
        return (<canvas id="myChartLine" width="400" height="200"> </canvas>);
    }
}
