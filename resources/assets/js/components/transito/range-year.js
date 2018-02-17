class RangeYear extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: props.id,
            options: [],
            mySlider: null
        };

        this.loadRange = this.loadRange.bind(this);
        this.changeYear = this.changeYear.bind(this);
        this.load = this.load.bind(this);
    }

    componentDidMount(){
        //this.loadRange();
        this.load();
    }

    load(){
        $.ajax({
            method:'POST',
            url: '/years',
            data:{
                id: this.state.id
            },
            cache: false,
            success: function(data) {

                console.log(data);

                this.setState({options: data}, function(){
                    this.loadRange();
                    this.props.checkYear(this.state.options[this.state.options.length-1], false);
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
            target: '#range-year',
            values: this.state.options,
            range: false,
            tooltip: true,
            scale: true,
            labels: false,
            onChange(values){
                _this.props.checkYear(values);
                console.log(values);
                //console.log(_this);
                //console.log(this);
            },
            set: [this.state.options[this.state.options.length-1]],
            callOnChangeInFirstTime: false
        });


        /*mySlider.onChange(function (values) {
            console.log('changeYear', values);
        });*/

        /*mySlider.onChange(function (values) {
            console.log(values);
            _this.props.checkYear(values);
        });

        console.log(mySlider);*/

        this.setState({mySlider: mySlider}, function(){
            //this.changeYear();
        });
    }

    changeYear(){
        let mySlider = this.state.mySlider;
        mySlider.onChange(function(values){
            console.log('changeYear', values);
            this.props.checkYear(values);
        });
        console.log(mySlider);
        this.setState({mySlider: mySlider});
    }

    render(){

        return(
            <div>
                <input type="text" id="range-year" />
            </div>
        );
    }
}


