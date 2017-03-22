class Rates extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            min: 0,
            max: 0
        };
    }

    componentWillReceiveProps(props){
        if(this.state.min != props.min || this.state.max != props.max){
            this.setState({min: props.min, max: props.max}, function(){
                //this.loadData();
            });
        }
    }

    loadData(){
        let _this = this;
        $.ajax("valores-regiao/"+this.state.min+"/"+this.state.max, {
            data: {},
            success: function(data){
                //console.log(data);
            },
            error: function(data){
                console.log('erro');
            }
        })
    }

    render(){
        return(
            <h3>Rates</h3>
        );
    }
}
