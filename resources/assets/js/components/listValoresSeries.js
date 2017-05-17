class ListValoresSeries extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            valores: [],
        };
        //this.loadData = this.loadData.bind(this);
    }

    componentDidMount(){
        //this.loadData();
    }

    componentWillReceiveProps(props){
        /*this.setState({min: props.min, max: props.max}, function(){
         this.loadData();
         });*/

       //console.log(props.data);
        this.setState({valores: props.data.valores});
    }

    /*loadData(){
        $.ajax({
            method:'GET',
            url: "periodo/"+this.state.id+"/"+this.state.min+"/"+this.state.max+"/"+this.props.regions+"/"+this.props.abrangencia,
            cache: false,
            success: function(data) {
                console.log('listValoresSeries', data);

                let valores = [];
                for(let i in data){
                    let region = {};
                    region[i] = data[i];
                    valores.push(region);
                }

                this.setState({valores: valores});
            }.bind(this),
            error: function(xhr, status, err) {
              console.log('erro', err);
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

            //para que no municipio não aparece repetido o nome
            let sigla = null;
            if(item.sigla!==item.nome){
               sigla = item.sigla+' - '
            }

            return (
                <tr key={index}>
                    <th width="10px"><i className="fa fa-square" style={{color: color}}> </i></th>
                    <th>{sigla}{item.nome}</th>
                    <td className="text-right">{formatNumber(item.valor, this.props.decimais, ',', '.')}</td>
                </tr>
            );
        }.bind(this));


        return (
            <div>
                <table className="table table-striped table-bordered" id="listValoresSeries">
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
                <br/>
                <div style={{float: 'right', marginLeft:'5px'}}>
                    <Download btnDownload="downloadListValoresSeries" divDownload="listValoresSeries" arquivo="tabela.png"/>
                </div>
                <div style={{float: 'right', marginLeft:'5px'}}>
                    <Print divPrint="listValoresSeries" imgPrint="imgPrintList"/>
                </div>
                <div style={{clear: 'both'}}/>
            </div>

        );
    }
}

