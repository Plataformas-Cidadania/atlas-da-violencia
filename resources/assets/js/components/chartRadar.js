myChartRadar = undefined;

class ChartRadar extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            serie: this.props.serie,
            periodo: 0
        };
        //this.loadData = this.loadData.bind(this);
        this.loadChart = this.loadChart.bind(this);
    }

    componentWillReceiveProps(props){
        if(this.state.periodo != props.data.periodo){
            /*this.setState({min: props.min, max: props.max}, function(){
                if(myChartRadar){
                    this.chartDestroy();
                }
                this.loadData();
            });*/
            this.setState({periodo: props.data.periodo, data: props.data.valores}, function(){
                if(myChartRadar){
                    this.chartDestroy();
                }
                this.loadChart(this.state.data);
            })
        }
    }

    /*loadData(){
        let _this = this;
        $.ajax("valores-regiao/"+this.state.min+"/"+this.state.max, {
            data: {},
            success: function(data){
                //console.log(data);
                _this.loadChartRadar(data);
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
            values[i] = data[i].valor;
        }

        //console.log(values);

        let canvas2 = document.getElementById('myChartRadar');
        let dataChart = {
            labels: labels,
            datasets: [
                {
                    label: this.state.serie,
                    backgroundColor: "rgba(179,181,198,0.2)",
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
            },
            animation:{
                onComplete: function() {
                    downloadCanvas("downChartBar", "myChartRadar", 'chartradar.png');
                }
            }
        };

        myChartRadar = new Chart(canvas2, {
            type: 'radar',
            data: dataChart,
            options: options2
        });

    }

    chartDestroy(){
        myChartRadar.destroy();
    }

    render(){

        let periodo = null;
        if(this.state.periodo){
            periodo = this.state.periodo.toString();
            if(this.props.periodicidade==="Anual"){
                periodo = periodo.substr(0, 4);
            }
            if(this.props.periodicidade==="Semestral" || this.props.periodicidade==="Trimestral" || this.props.periodicidade==="Mensal"){
                periodo = periodo.substr(0, 7);
            }
        }

        return (
            <div>
                <div style={{textAlign: 'center', clear: 'both'}}>
                    <button className="btn btn-primary btn-lg bg-pri" style={{border:'0'}}>{periodo}</button>
                    <div style={{marginTop:'-19px'}}>
                        <i className="fa fa-sort-down fa-2x ft-pri"  />
                    </div>
                </div>
                <br/>
                <canvas id="myChartRadar" width="400" height="200"> </canvas>
                <div style={{float: 'right', marginLeft:'5px'}}>
                    {/*<Download btnDownload="downloadMyChartLine" divDownload="divChartLine"/>*/}
                    <a id="downChartBar" style={{cursor: 'pointer'}}  >
                        <div className="icons-components icons-component-download"/>
                    </a>
                </div>
                <div style={{float: 'right', marginLeft:'5px'}}>
                    <div className="icons-components icons-component-print" onClick={() => printCanvas("myChartRadar")}/>
                </div>
                <div style={{clear: 'both'}}/>
            </div>
        );
    }
}



