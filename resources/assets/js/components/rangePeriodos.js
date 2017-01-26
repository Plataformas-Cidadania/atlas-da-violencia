class RangePeriodo extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: this.props.id,
            periodos: []
        }
    }

    componentDidMount(){
        this.loadData();
    }

    loading(status){
        this.props.loading(status);
    }

    loadData(){
        this.loading(true);
        let _this = this;
        $.ajax("periodos/"+this.state.id, {
            data: {},
            success: function(data){
                console.log(data);
                _this.setState({periodos: data}, function(){
                    _this.loadRange();
                    _this.props.setPeriodos(data);
                });
                _this.loading(false);
                //periodos = data;
            },
            error: function(data){
                console.log('erro');
            }
        })
    }

    loadRange(){
        let _this = this;
        $("#range").ionRangeSlider({
            values: _this.state.periodos,
            hide_min_max: true,
            keyboard: true,
            /*min: 0,
             max: 5000,
             from: 1000,
             to: 4000,*/
            type: 'double',
            step: 1,
            prefix: "",
            //postfix: " million pounds",
            grid: true,
            prettify_enabled: false,
            onStart: function (data) {
                //console.log('range onStart', data);
                _this.props.changePeriodo(data.from_value, data.to_value);
                //min = data.from_value;
                //max = data.to_value;
                //dataToMap(data.from_value, data.to_value);
                //dataToChart(data.from_value, data.to_value);
                //dataToChartRadar(data.from_value, data.to_value);
            },
            onChange: function (data) {
                //console.log('onChange');
            },
            onFinish: function (data) {
                //console.log('range onFinish', data);
                _this.props.changePeriodo(data.from_value, data.to_value);
                //min = data.from_value;
                //max = data.to_value;
                //dataToMap(data.from_value, data.to_value);
                //clearCharts();
                //dataToChart(data.from_value, data.to_value);
                //dataToChartRadar(data.from_value, data.to_value);
            },
            onUpdate: function (data) {
                //console.log('onUpdate');
            }

        });
    }

    render(){
        return(
            <div className="hidden-print">
                <input type="text" id="range" value=""  name="range" />
            </div>
        );
    }
}

