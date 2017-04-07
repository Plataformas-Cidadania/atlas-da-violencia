myChartLine = undefined;

class ChartLine extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: this.props.id,
            serie: this.props.serie,
            loading: false,
            min: 0,
            max: 0,
            intervalos: this.props.intervalos
        };
        this.loadData = this.loadData.bind(this);
        this.loadChartLine = this.loadChartLine.bind(this);
        this.singleChart = this.singleChart.bind(this);
        this.multipleChart = this.multipleChart.bind(this);
        this.getColors = this.getColors.bind(this);
    }

    componentWillReceiveProps(props){
        if(this.state.min != props.min || this.state.max != props.max || this.state.intervalos != props.intervalos){
            this.setState({min: props.min, max: props.max, intervalos: props.intervalos}, function(){
                if(myChartBar){
                    this.chartDestroy();
                }
                this.loadData();
            });
        }
    }

    loadData(){
        this.setState({loading: true});
        let _this = this;
        //$.ajax("periodo/"+this.state.id+"/"+this.state.min+"/"+this.state.max, {
        $.ajax("periodo/"+this.state.id+"/"+this.state.min+"/"+this.state.max+"/"+this.props.regions+"/"+this.props.territorio, {
            data: {},
            success: function(data){
                //console.log('charline', data);
                _this.setState({loading: false}, function(){
                    _this.loadChartLine(data);
                });
            },
            error: function(data){
                console.log('erro');
            }
        })
    }

    loadChartLine(data){
        if(this.props.regions==''){
            this.singleChart(data);
            return;
        }

        this.multipleChart(data);

    }

    singleChart(data){
        let labels = [];
        let values = [];
        for(let i in data){
            labels[i] = data[i].periodo;
            values[i] = data[i].total;
        }

        let canvas = document.getElementById('myChartLine');

        let dataChart = {
            labels: labels,
            datasets: [
                {
                    label: this.state.serie,
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

        myChartLine = Chart.Line(canvas,{
            data:dataChart,
            options:option
        });
    }

    multipleChart(data){
        let labels = [];
        let datasets = [];

        let canvas = document.getElementById('myChartLine');

        let cont = 0;
        let contLabel = 0;
        for(let region in data){

            let values = [];

            for(let periodo in data[region]){
                values.push(data[region][periodo]);
                if(cont==0){
                    labels[contLabel] = periodo;
                    contLabel++;
                }
            }

            //console.log('values', values);

            //let colors = this.getColors(values);

            let colorChart = [];
            for(let i in colors2){
                colorChart.push(convertHex(colors2[i], 100));
            }

            colorChart = ['#cccccc', '#000000', '#f00000', 'ff0000'];
            colorChart = [convertHex('#cccccc', 100), convertHex('#000000', 100), convertHex('#f00000', 100), convertHex('ff0000', 100)];


            datasets[cont++] = {
                label: region,
                fill: false,
                lineTension: 0.1,
                backgroundColor: colorChart,
                borderColor: colorChart,
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: colorChart,
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: colorChart,
                pointHoverBorderColor: "rgba(220,220,220,1)",
                pointHoverBorderWidth: 2,
                pointRadius: 5,
                pointHitRadius: 10,
                data: values,
            }

        }

        //console.log(labels);


        var dataChart = {
            labels: labels,
            datasets: datasets
        }

        let option = {
            showLines: true
        };

        myChartLine = Chart.Line(canvas,{
            data:dataChart,
            options:option
        });
    }

    chartDestroy(){
        //myChartLine.destroy();
        //myChartLine.update();
    }

    getColors(values){
        //console.log('chartline - getcolors - intervalos', this.state.intervalos.length);
        //console.log('chartline - getcolors - values', values);
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
                <div className="text-center" style={{display: this.state.loading ? 'block' : 'none'}}>
                    <i className="fa fa-5x fa-spinner fa-spin"> </i>
                </div>
                <div style={{display: this.state.loading ? 'none' : 'block'}}>
                    <div style={{textAlign: 'center', clear: 'both'}}>
                        <button className="btn btn-primary btn-lg bg-pri" style={{border:'0'}}>
                            {this.state.min} - {this.state.max}
                            </button>
                        <div style={{marginTop:'-19px'}}>
                            <i className="fa fa-sort-down fa-2x" style={{color:'#3498DB'}} />
                        </div>
                    </div>
                    <canvas  id="myChartLine" width="400" height="200"> </canvas>
                </div>

            </div>
        );
    }
}
