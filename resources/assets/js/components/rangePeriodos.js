class RangePeriodo extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: this.props.id,
            abrangencia: 0,
            firstLoad: true,
            slider: {},
            periodos: []
        };

        this.loadData = this.loadData.bind(this);
        this.updateRange = this.updateRange.bind(this);
        this.loadRange = this.loadRange.bind(this);
        this.convertePeriodos = this.convertePeriodos.bind(this);
        this.getFromTo = this.getFromTo.bind(this);
    }

    componentDidMount(){
        if(this.state.id > 0){
            this.loadData();
        }
    }

    componentWillReceiveProps(props){
        if(this.state.id!=props.id || this.state.abrangencia!=props.abrangencia){
            this.setState({id: props.id, abrangencia: props.abrangencia}, function(){
                this.loadData();
            });
        }
    }

    loading(status){
        this.props.loading(status);
    }

    loadData(){
        this.loading(true);
        $.ajax("periodos/"+this.state.id+"/"+this.state.abrangencia, {
            data: {},
            success: function(data){
                //console.log('range', data);
                this.setState({periodos: data}, function(){
                    this.loadRange();
                    if(!this.state.firstLoad){
                        this.updateRange();
                    }
                    this.setState({firstLoad: false});
                    this.props.setPeriodos(data);
                });
                this.loading(false);
                //periodos = data;
            }.bind(this),
            error: function(data){
              console.log('erro');
            }.bind(this)
        })
    }

    convertePeriodos(){
        let periodos = [];
        let from = null;
        let to = null;
        for(let i in this.state.periodos){
            periodos[i] = formatPeriodicidade(this.state.periodos[i], this.props.periodicidade);
            if(this.state.periodos[i]==this.props.from){
                from = i;
            }
            if(this.state.periodos[i]==this.props.to){
                to = i;
            }
        }

        return {
            periodos: periodos,
            from: from,
            to: to
        }
    }

    getFromTo(periodos, data){
        let iFrom = null;
        let iTo = null;
        for(let i in periodos){
            //console.log('start', periodos[i], data.from_value, data.to_value);
            if(periodos[i]==data.from_value){
                iFrom = i;
            }
            if(periodos[i]==data.to_value){
                iTo = i;
            }
        }

        let from = this.state.periodos[iFrom];
        let to = this.state.periodos[iTo];

        return {
            from: from,
            to: to
        };
    }

    updateRange(){
        //console.log(formatPeriodicidade(this.state.periodos[0], "Anual"));

        let objPeriodos = this.convertePeriodos();

        this.state.slider.update({
            values: objPeriodos.periodos,
            from: objPeriodos.from,
            to: objPeriodos.to,
            //from: this.state.periodos[0],
            //to: this.state.periodos.length-1,
        });
    }


    loadRange(){
        let _this = this;

        let objPeriodos = this.convertePeriodos();

        //console.log(_this.props.from);
        //console.log(_this.props.to);
        $("#range").ionRangeSlider({
            values: objPeriodos.periodos,
            hide_min_max: true,
            //keyboard: true,
            //min: 0,
            //max: 5000,
            from: objPeriodos.from,
            to: objPeriodos.to,
            type: 'double',
            //step: 1,
            prefix: "",
            //postfix: " million pounds",
            grid: true,
            onStart: function (data) {
                //console.log('range onStart', data);
                //console.log(data.from_value, data.to_value);

                let periodos = this.values;

                let fromTo = _this.getFromTo(periodos, data);

                _this.props.changePeriodo(fromTo.from, fromTo.to);

                //_this.props.changePeriodo(data.from_value, data.to_value);
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
                //console.log(data.from_value, data.to_value);

                let periodos = this.values;
                let fromTo = _this.getFromTo(periodos, data);

                _this.props.changePeriodo(fromTo.from, fromTo.to);

                //_this.props.changePeriodo(data.from_value, data.to_value);
                //min = data.from_value;
                //max = data.to_value;
                //dataToMap(data.from_value, data.to_value);
                //clearCharts();
                //dataToChart(data.from_value, data.to_value);
                //dataToChartRadar(data.from_value, data.to_value);
            },
            onUpdate: function (data) {

                let periodos = this.values;
                let fromTo = _this.getFromTo(periodos, data);

                _this.props.changePeriodo(fromTo.from, fromTo.to);

                //_this.props.changePeriodo(data.from_value, data.to_value);
                //console.log('onUpdate');
            }

        });

        let slider = $("#range").data("ionRangeSlider");

        this.setState({slider: slider});
    }

    change(){

    }

    render(){
        return(
            <div className="hidden-print" style={{display: this.state.periodos.length > 0 ? 'block' : 'none'}}>
                <h4>{this.props.lang_select_period}</h4>
                <input type="text" id="range" value={this.props.from+';'+this.props.to}  name="range" onChange={this.change} />
            </div>
        );
    }
}

