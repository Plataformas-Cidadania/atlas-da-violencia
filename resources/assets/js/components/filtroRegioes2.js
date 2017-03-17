class FiltroRegions extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: this.props.id,
            regions: [],
        };

        this.loadData = this.loadData.bind(this);
        this.loading = this.loading.bind(this);
    }

    componentDidMount(){
        if(this.state.id > 0){
            this.loadData();
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
        $.ajax("regioes/"+this.state.id, {
            data: {},
            success: function(data){
                console.log('regions', data);
                this.setState({regions: data}, function(){
                    //this.classDefault();
                });
                this.loading(false);
                //periodos = data;
            }.bind(this),
            error: function(data){
                console.log('erro');
            }.bind(this)
        })
    }

    render(){
        return(
            <div>...</div>
        );
    }
}
