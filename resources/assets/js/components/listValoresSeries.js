class ListValoresSeries extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            valores: []
        }
    }

    componentDidMount(){
        this.loadValues();
    }

    loadValues(){
        $.ajax({
            method:'GET',
            url: "valores-series/"+this.props.min+"/"+this.props.max,
            cache: false,
            success: function(data) {
                //console.log(data);
                this.setState({valores: data});
                loadMap(data);
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    render(){
        //let _this = this;
        let valores = this.state.valores.map(function (item, index) {
            return (
                <tr key={index}>
                    <th>{item.uf}</th>
                    <td>{item.total}</td>
                </tr>
            );
        });

        return (
            <table className="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Uf</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    {valores}
                </tbody>
            </table>
        );
    }
}

ReactDOM.render(
    <ListValoresSeries min="2004" max="2006" valores=""/>,
    document.getElementById('listValoresSeries')
);