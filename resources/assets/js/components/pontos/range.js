class RangeYear extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: this.props.id,
            abrangencia: 0,
            firstLoad: true,
            slider: {},
            periodos: ['2010', '2011', '2012', '2013', '2014']
        };

        this.loadData = this.loadData.bind(this);
        this.updateRange = this.updateRange.bind(this);
        this.loadRange = this.loadRange.bind(this);
    }
1
    componentDidMount(){
        this.loadRange();
        if(this.state.id > 0){
            //this.loadData();
        }
    }

    componentWillReceiveProps(props){
        if(this.state.id!=props.id){
            this.setState({id: props.id}, function(){
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

    updateRange(){
        this.state.slider.update({
            values: this.state.periodos,
            from: this.state.periodos[0],
            to: this.state.periodos.length-1,
        });
    }


    loadRange(){
        let _this = this;
        $("#range").ionRangeSlider({
            values: _this.state.periodos,
            hide_min_max: true,
            keyboard: true,
            //min: 0,
            //max: 5000,
            //from:0,
            //to: 5000,
            type: 'single',
            step: 1,
            prefix: "",
            //postfix: " million pounds",
            grid: true,
            prettify_enabled: false,
            onStart: function (data) {
                //_this.props.changePeriodo(data.from_value, data.to_value);
            },
            onChange: function (data) {
            },
            onFinish: function (data) {
                //_this.props.changePeriodo(data.from_value, data.to_value);
            },
            onUpdate: function (data) {
                //_this.props.changePeriodo(data.from_value, data.to_value);
            }

        });

        let slider = $("#range").data("ionRangeSlider");

        this.setState({slider: slider});
    }

    render(){
        return(
            <div className="hidden-print" style={{display: this.state.periodos.length > 0 ? 'block' : 'none'}}>
                <h4>Selecione o período desejado</h4>
                <input type="text" id="range" value={this.props.from+';'+this.props.to}  name="range" />
            </div>
        );
    }
}

