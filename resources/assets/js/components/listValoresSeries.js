class ListValoresSeries extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            valores: [],
        };
        this.loadDataToList = this.loadDataToList.bind(this);
    }

    componentWillReceiveProps(props){
        this.setState({min: props.min, max: props.max}, function(){
            this.loadDataToList();
        });
    }

    loadDataToList(){
        $.ajax({
            method:'GET',
            url: "valores-series/"+this.props.min+"/"+this.props.max,
            cache: false,
            success: function(data) {
                //console.log(data);
                this.setState({valores: data});
                //loadMap(data);
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    render(){
        //console.log('========================================================');
        let valores = this.state.valores.map(function (item, index) {
            return (
                <tr key={index}>
                    <th><i className="fa fa-square" style={{color: getColor(item.total)}}> </i> {item.uf}</th>
                    <td>{item.total}</td>
                </tr>
            );
        });
        //console.log('========================================================');

        return (
            <table className="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Uf</th>
                        <th>Ocorrências</th>
                    </tr>
                </thead>
                <tbody>
                    {valores}
                </tbody>
            </table>
        );
    }
}

