class ListValoresSeries extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            valores: [],
            min: this.props.min,
            max: this.props.max,
            loading: true,
            columnsTd: null,
            dataTable: null,
            abrangencia: null,
        };
        //this.loadData = this.loadData.bind(this);
        this.generateTable = this.generateTable.bind(this);
    }

    componentDidMount(){
        //this.loadData();
        this.generateTable();
    }

    componentWillReceiveProps(props){
        /*this.setState({min: props.min, max: props.max}, function(){
         this.loadData();
         });*/

        //console.log(props.data);
        if(this.state.abrangencia!=props.abrangencia){
            this.setState({columnsTd: (<td>&nbsp;</td>), dataTable: (<tr><td>&nbsp;</td></tr>), loading:true}, function(){
                this.generateTable();
            });
        }

        if(this.state.valores!=props.data || this.state.abrangencia!=props.abrangencia){
            this.setState({valores: props.data, loading:true}, function(){
                this.generateTable();
            });
        }

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

    generateTable(){

        let labels = [];
        let datasets = [];
        let cont = 0;
        let contLabel = 0;
        let contColor = 0;
        let data = this.state.valores;
        let currentPer = null;


        //////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////
        let columns = [];
        columns.push(null);
        columns.push(this.props.nomeAbrangencia);
        let values = [];
        let colors = this.getColors();

        for(let region in data){

            if(contColor > colors.length-1){
                contColor = 0;
            }

            let register = {};
            register['legend'] = colors[contColor];
            register['region'] = region;

            for(let periodo in data[region]){
                //console.log('##########', periodo);
                //console.log('>>>>>>>>', columns.indexOf(periodo));
                if(columns.indexOf(periodo) == -1){
                    columns.push(periodo);
                }
                register[periodo] = data[region][periodo];
            }

            contColor++;

            datasets.push(register);
        }

        //console.log('COLUMNS', columns);

        for(let register in datasets){
            //console.log('--------------------------------');
            //console.log(datasets[register]);
            for(let column in columns){
                //console.log('###', columns[column]);
                //console.log('A COLUNA EXISTE?', datasets[register].hasOwnProperty(columns[column]));
                if(!datasets[register].hasOwnProperty(columns[column])){
                    //console.log('ADICIONAR COLUNA', columns[column], 'COM VALOR NULL');
                    datasets[register][columns[column]] = null;
                }
            }
            //console.log('DATASETS[REGISTER] DEPOIS', datasets[register]);
        }


        let columnsTd = columns.map(function (column, index){

            if(index >= 2){
                column = formatPeriodicidade(column, this.props.periodicidade);
            }

            return(
                <th key={"col_list_"+index} style={{textAlign: 'right', fontWeight: 'bold'}}>{column}</th>
            );
        }.bind(this));

        let dataTable = datasets.map(function (item, index) {

            let valores = [];

            for(let i in columns){

                let column = columns[i];

                let valor = item[column];

                //testa se é numero
                let regra = /^[0-9]+$/;
                if(item[column]){
                    if(item[column].match(regra)){
                        valor = formatNumber(item[column], this.props.decimais, ',', '.');
                    }
                }


                let classValor = "text-right";
                if(item[column]==0){
                    valor = '-';
                    classValor = "text-center"
                }

                let td = (<td key={"valor_tabela_"+index+'_'+column} className={classValor}>{valor}</td>);

                if(i==0){
                    td = (<th key={"valor_tabela_"+index+'_'+column} width="10px"><i className="fa fa-square" style={{color: item['legend']}}> </i></th>);
                }

                if(i==1){
                    td = (<th key={"valor_tabela_"+index+'_'+column}>{item['region']}</th>);
                }

                valores.push (td);
            }


            return (
                <tr key={"col_valores_"+index}>
                    {valores}
                </tr>
            );
        }.bind(this));

        this.setState({columnsTd: columnsTd, dataTable:dataTable, loading: false});
    }


    render(){
        if(!this.state.valores){
            return (<h3>Sem Resultados</h3>);
        }

        console.log(this.state.columnsTd);

        return (
            <div>
                <div style={{display: this.state.loading || !this.state.dataTable ? '' : 'none'}} className="text-center"><i className="fa fa-spin fa-spinner fa-4x"/></div>
                <div  style={{display: this.state.loading || !this.state.dataTable ? 'none' : ''}}>

                        <div className="Container">
                            <div className="Content" style={{overflowY: 'auto', maxHeight: '600px'}}>
                                <table className="table table-striped table-bordered" id="listValoresSeries">
                                    <thead>
                                    <tr>
                                        {this.state.columnsTd}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {this.state.dataTable}
                                    </tbody>
                                </table>
                            </div>
                        </div>

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

    }
}

