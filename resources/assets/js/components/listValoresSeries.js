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

        console.log(props.data);
        this.setState({valores: props.data.valores});
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
        if(!this.state.valores){
            return (<h3>Sem Resultados</h3>);
        }

        let contColor = 0;

        let valores = this.state.valores.map(function (item, index) {

            if(contColor > colors2.length-1){
                contColor = 0;
            }

            let color = colors2[contColor];

            contColor++;

            return (
                <tr key={index}>
                    <th width="10px"><i className="fa fa-square" style={{color: color}}> </i></th>
                    <th>{item.sigla} - {item.nome}</th>
                    <td className="text-right">{formatNumber(item.valor, this.props.decimais, ',', '.')}</td>
                </tr>
            );
        }.bind(this));


        return (
            <table className="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Território</th>
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

