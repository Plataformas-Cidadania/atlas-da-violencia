class RangeMonth extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: props.id,
            options: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            mySlider: null
        };

        this.loadRange = this.loadRange.bind(this);
    }

    componentDidMount(){
        this.loadRange();
    }

    loadRange(){
        let mySlider = new rSlider({
            target: '#range-month',
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
                <input type="text" id="range-month" />
            </div>
        );
    }
}


