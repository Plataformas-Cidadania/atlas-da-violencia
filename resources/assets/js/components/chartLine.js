


myChartLine = undefined;
class ChartLine extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: this.props.id,
            serie: this.props.serie,
            loading: false,
            min: props.min,
            max: props.max,
            intervalos: this.props.intervalos,
            regions: this.props.regions,
            abrangencia: this.props.abrangencia,
        };
        this.loadData = this.loadData.bind(this);
        this.loadChartLine = this.loadChartLine.bind(this);
        this.singleChart = this.singleChart.bind(this);
        this.multipleChart = this.multipleChart.bind(this);
        this.getColors = this.getColors.bind(this);
    }

    componentDidMount(){
        if(this.state.min && this.state.max){
            this.loadData();
        }

    }

    componentWillReceiveProps(props){
        if(
            this.state.min != props.min ||
            this.state.max != props.max ||
            this.state.intervalos != props.intervalos ||
            this.state.regions != props.regions ||
            this.state.abrangencia != props.abrangencia
        ){
            this.setState({min: props.min, max: props.max, intervalos: props.intervalos, regions: props.regions, abrangencia: props.abrangencia}, function(){
                if(myChartBar){
                    this.chartDestroy();
                }
                if(this.state.min && this.state.max){
                    this.loadData();
                }
            });
        }
    }

    loadData(){
        this.setState({loading: true});
        let _this = this;
        //$.ajax("periodo/"+this.state.id+"/"+this.state.min+"/"+this.state.max, {
        $.ajax("periodo/"+this.state.id+"/"+this.state.min+"/"+this.state.max+"/"+this.state.regions+"/"+this.state.abrangencia, {
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
        let contColor = 0;

        //Preencher labels com os períodos
        for(let region in data){
            for(let periodo in data[region]){
                let per = formatPeriodicidade(periodo, this.props.periodicidade);
                if(!labels.includes(per)){
                    labels[contLabel] = per;
                    contLabel++;
                }
                data[region][per] = data[region][periodo]};//grava um indice com o periodo formatado ex: 2000-01-15 para 2000
                delete data[region][periodo];//remove o periodo não formatado
            }
        }

        console.log(labels);
        console.log(data);

        //Ordenar os períodos
        labels.sort();
        for (let i in labels){
            console.log(labels[i]);
        }


        for(let region in data){
            for(let i in labels){
                if(!data[region].hasOwnProperty(labels[i])){
                    data[region][labels[i]] = '';
                }
            }
        }

        console.log(data);

        for(let region in data){

            let values = [];

            for(let periodo in data[region]){
                values.push(data[region][periodo]);
                /*if(cont==0){
                    labels[contLabel] = formatPeriodicidade(periodo, this.props.periodicidade);
                    contLabel++;
                }*/
            }

            //console.log('values', values);

            //let colors = this.getColors(values);

            let colors = this.getColors();

            if(contColor > colors.length-1){
                contColor = 0;
            }





            datasets[cont] = {
                label: region,
                fill: false,
                lineTension: 0.1,
                backgroundColor: colors[contColor],
                borderColor: colors[contColor],
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: colors[contColor],
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: colors[contColor],
                pointHoverBorderColor: colors[contColor],
                pointHoverBorderWidth: 2,
                pointRadius: 5,
                pointHitRadius: 10,
                data: values,
            };

            cont++;
            contColor++;

        }

        //console.log('labels', labels);
        //console.log('datasets', datasets);

        var dataChart = {
            labels: labels,
            datasets: datasets
        };

        let option = {
            showLines: true,
            animation:{
                onComplete: function() {
                    downloadCanvas('downChart', 'myChartLine', 'chartline.png');
                }
            },
            responsive: true,
            /*legend:{
                display: datasets.length<11
            },*/
            legend: false,
            legendCallback: function(chart){
                let text = [];

                for(let i in chart.data.datasets){
                    let box = '<div style="background-color:'+chart.data.datasets[i].backgroundColor+'; width:40px; height: 15px; float:left;"/>';
                    let str = '<div style="float:left; margin-top: -3px;">&nbsp;'+chart.data.datasets[i].label+'</div>';
                    let legend = '<div style="display: inline-block; margin-right:10px; padding: 5px;">'+box+str+'</div>';
                    text.push(legend);
                }

                return text;
            }
        };

        myChartLine = Chart.Line(canvas,{
            data:dataChart,
            options:option,
        });

        $("#chartjs-legend").html(myChartLine.generateLegend());

        $("#chartjs-legend").on('click', "div", function() {

            //let legendItem = $(this);

            var index = $(this).index();
            var meta = myChartLine.getDatasetMeta(index);

            // See controller.isDatasetVisible comment
            meta.hidden = meta.hidden === null? !myChartLine.data.datasets[index].hidden : null;

            // We hid a dataset ... rerender the chart
            myChartLine.update();

            //console.log(myChartLine.data.datasets[$(this).index()]);
        });

        //downloadImage($('#divChartLine'), "downloadMyChartLine", 'chartline.png');


    }

    chartDestroy(){
        if(myChartLine){
            myChartLine.destroy();
        }

        //myChartLine.update();
    }

    //getColors(values){
    getColors(){

        let colors = [];
        for(let i in colors2){
            colors.push(convertHex(colors2[i], 100));
        }
        return colors;
    }

    render(){

        let min = formatPeriodicidade(this.state.min, this.props.periodicidade);
        let max = formatPeriodicidade(this.state.max, this.props.periodicidade);

        return (
            <div>
                <div className="text-center" style={{display: this.state.loading ? 'block' : 'none'}}>
                    <i className="fa fa-5x fa-spinner fa-spin"> </i>
                </div>
                <div style={{display: this.state.loading ? 'none' : 'block'}}>
                    <div style={{textAlign: 'center', clear: 'both'}}>
                        <button className="btn btn-primary btn-lg bg-pri" style={{border:'0'}}>
                            {min} - {max}
                            </button>
                        <div style={{marginTop:'-19px'}}>
                            <i className="fa fa-sort-down fa-2x ft-pri"  />
                        </div>
                    </div>
                    <div id="divChartLine">
                        <div id="chartjs-legend" style={{width: "100%", height: "40px", overflowX: "auto", overflowY: "hidden", whiteSpace: "nowrap"}}/>
                        <canvas  id="myChartLine" width="400" height="200" style={{marginTop: '10px'}}> </canvas>
                    </div>
                    <div style={{float: 'right', marginLeft:'5px'}}>
                        {/*<Download btnDownload="downloadMyChartLine" divDownload="divChartLine"/>*/}
                        <a id="downChart" style={{cursor: 'pointer'}}  >
                            <div className="icons-components icons-component-download"/>
                        </a>
                    </div>
                    <div style={{float: 'right', marginLeft:'5px'}}>
                        <div className="icons-components icons-component-print" onClick={() => printCanvas('myChartLine')}/>
                    </div>
                    <div style={{clear: 'both'}}/>

                </div>

            </div>
        );
    }
}
