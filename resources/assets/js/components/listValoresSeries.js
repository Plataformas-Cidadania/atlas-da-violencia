class ListValoresSeries extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            valores: [],
            min: this.props.min,
            max: this.props.max,
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
        //if(this.state.min!==props.min || this.state.max!==props.max){
            this.setState({valores: props.data});
        //}

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

    getColors(){

        let colors = [];
        for(let i in colors2){
            colors.push(convertHex(colors2[i], 100));
        }
        return colors;
    }


    render(){
        if(!this.state.valores){
            return (<h3>Sem Resultados</h3>);
        }


        //let contColor = 0;

        let labels = [];
        let datasets = [];
        let cont = 0;
        let contLabel = 0;
        let contColor = 0;
        let data = this.state.valores;
        for(let region in data){
            let values = [];

            for(let periodo in data[region]){
                values.push(data[region][periodo]);
                if(cont===0){
                    labels[contLabel] = formatPeriodicidade(periodo, this.props.periodicidade);
                    contLabel++
                }
            }

            let colors = this.getColors();
            if(contColor > colors.length-1){
                contColor = 0;
            }

            datasets[cont] = {
                label: region,
                values: values,
                color: colors[contColor],
            };

            cont++;
            contColor++;

        }


        let periodos = labels.map(function (periodo, index){
            return(
                <td key={"col_per_"+index} style={{textAlign: 'right', fontWeight: 'bold'}}>{periodo}</td>
            );
        });

        let dados = datasets.map(function (item, index) {

            let valores = item.values.map(function(value, i){

                let valor = formatNumber(value, this.props.decimais, ',', '.');
                let classValor = "text-right";
                if(value==0){
                    valor = '-';
                    classValor = "text-center"
                }

                return (
                    <td key={"valor_tabela_"+i} className={classValor}>{valor}</td>
                );
            }.bind(this));

            return (
                <tr key={"col_valores_"+index}>
                    <th width="10px"><i className="fa fa-square" style={{color: item.color}}> </i></th>
                    <th>{item.label}</th>
                    {valores}
                </tr>
            );
        }.bind(this));

        return (
            <div className="Container Flipped">
                <div className="Content">
                    <table className="table table-striped table-bordered" id="listValoresSeries">
                        <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>{this.props.nomeAbrangencia}</th>
                            {periodos}
                        </tr>
                        </thead>
                        <tbody>
                        {dados}
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
            </div>

        );

        /*let valores = this.state.valores.map(function (item, index) {

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
        }.bind(this));*/

        /*return (
            <div>
                <table className="table table-striped table-bordered" id="listValoresSeries">
                    <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Território</th>
                        <th className="text-right">V</th>
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

        );*/
    }
}

