class RangeMonth extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: props.id,
            options: [],
            mySlider: null
        };

        this.loadRange = this.loadRange.bind(this);
        this.load = this.load.bind(this);
    }

    componentDidMount(){
        //this.loadRange();
        this.load();
    }

    load(){
        $.ajax({
            method:'POST',
            url: '/months',
            data:{
                id: this.state.id
            },
            cache: false,
            success: function(data) {

                this.setState({options: data}, function(){
                    this.loadRange();
                    this.props.checkMonth(this.state.options[this.state.options.length-1], false);
                })

            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
                this.setState({loading: false});
            }.bind(this)
        })
    }

    loadRange(){
        let _this = this;
        let mySlider = new rSlider({
            target: '#range-month',
            values: this.state.options,
            range: false,
            tooltip: true,
            scale: true,
            labels: false,
            onChange(values){
                _this.props.checkMonth(values);
                console.log(values);
                //console.log(_this);
                //console.log(this);
            },
            set: [this.state.options[this.state.options.length-1]],
            callOnChangeInFirstTime: false
        });

        this.setState({mySlider: mySlider}, function(){
            console.log(mySlider);
        });
    }

    render(){

        return(
            <div>
                <input type="text" id="range-month" />
            </div>
        );
    }
}


