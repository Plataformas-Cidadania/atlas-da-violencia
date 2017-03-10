class ListValoresSeries extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            valores: [],
        };
        //this.loadData = this.loadData.bind(this);
    }

    componentWillReceiveProps(props){
        /*this.setState({min: props.min, max: props.max}, function(){
            this.loadData();
        });*/

        this.setState({valores: props.data.values});
    }

    /*loadData(){
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
    }*/

    render(){
        if(!this.state.valores.length){
            return (<h3>Sem Resultados</h3>);
        }
        //console.log('========================================================');
        let valores = this.state.valores.map(function (item, index) {
            return (
                <tr key={index}>
                    <th width="10px"><i className="fa fa-square" style={{color: getColor(item.total, intervalos)}}> </i></th>
                    <th>{item.uf} - {item.nome}</th>
                    <td className="text-right">{numeral(item.total).format('0,0')}</td>
                </tr>
            );
        });
        //console.log('========================================================');

        return (
            <table className="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Uf</th>
                        <th className="text-right">Ocorrências</th>
                    </tr>
                </thead>
                <tbody>
                    {valores}
                </tbody>
            </table>
        );
    }
}

