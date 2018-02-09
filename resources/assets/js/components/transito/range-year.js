class RangeYear extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: props.id,
            options: ['2010', '2011', '2012', '2013', '2014'],
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
            url: '/years',
            data:{
                id: this.state.id
            },
            cache: false,
            success: function(data) {

                console.log(data);

                this.setState({options: data}, function(){
                    this.loadRange();
                })

            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
                this.setState({loading: false});
            }.bind(this)
        })
    }

    loadRange(){
        let mySlider = new rSlider({
            target: '#range-year',
            values: this.state.options,
            range: false,
            tooltip: true,
            scale: true,
            labels: false,
            set: [this.state.options[this.state.options.length-1]]
        });

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


