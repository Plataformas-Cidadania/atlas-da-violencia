class Calcs extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: this.props.id,
            serie: this.props.serie,
            data: {},
            periodo: 0,
            minValue: 0,
            maxValue: 0,
            media: [],
            mediaPonderada: [],
            moda: [],
            mediana: [],
            styleCalcs: {paddingTop: '60px'}
        };

        this.calcMinMax = this.calcMinMax.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.periodo != props.data.periodo) {
            this.setState({periodo: props.data.periodo, data: props.data.valores}, function(){
                if(this.state.data){
                    this.calcMinMax(this.state.data);
                    this.calcMedia(this.state.id, this.state.data);
                    this.calcMediaPonderada(this.state.id, this.state.data);
                    this.calcModa(this.state.id, this.state.data);
                    this.calcMediana(this.state.id, this.state.data);
                }
            });
        }
    }

    calcMinMax(data){
        let sort = data.sort(function(a, b){
            if(parseFloat(a.valor) < parseFloat(b.valor)){
                return -1;
            }
            if(parseFloat(a.valor) > parseFloat(b.valor)){
                return 1;
            }
            return 0;
        });
        this.setState({
            minValue: sort[0],
            maxValue: sort[sort.length-1],
        });

    };

    calcMedia(id_serie, serie){
        let valor = 0;
        for(let i in serie){
            valor += parseFloat(serie[i].valor);
        }

        let media = [];
        media[id_serie] = valor/serie.length;
        this.setState({media: media});
    };

    calcModa(id_serie, serie){
        let moda = [];
        let numeros = {};
        for(let i in serie){
            if(!numeros[serie[i].valor]){
                numeros[serie[i].valor] = 0;
            }
            numeros[serie[i].valor]++;
        }
        let modaN = 0;
        let qtd_moda = 0;
        for(let i in numeros){
            //console.log(i);
            if(numeros[i] > qtd_moda){
                modaN = i;
                qtd_moda = numeros[i];
            }
        }
        if(qtd_moda == 1){
            moda[id_serie] = [];
            return;
        }
        let array_moda = [modaN];
        for(let i in numeros){
            //console.log(i);
            if(numeros[i] == qtd_moda && i != modaN){
                array_moda.push(i);
            }
        }
        for(let i in array_moda){
            array_moda[i] = array_moda[i];
        }

        moda[id_serie] = array_moda.join(' - ');
        this.setState({moda: moda});
    };

    calcMediana(id_serie, serie){
        serie.sort(function(a, b){
            return a.valor - b.valor;
        });
        let qtd = serie.length;
        if(qtd%2!=0){
            let pos = (qtd-1)/2;
            let mediana = serie[pos].valor;
            let arrayMediana = [];
            arrayMediana[id_serie] = mediana;
            this.setState({mediana: arrayMediana});
            return;
        }
        let pos = qtd/2;
        let mediana = (parseFloat(serie[pos].valor)+parseFloat(serie[pos-1].valor))/2;
        let arrayMediana = [];
        arrayMediana[id_serie] = mediana;
        this.setState({mediana: arrayMediana});
    }

    calcMediaPonderada(id_serie, serie){
        let total_indices = 0;
        let total_valores = 0;
        let qtd = serie.length;
        for(let i=0;i<qtd;i++){
            total_indices += i+1;
            //console.log(total_indices);
            total_valores += serie[i].valor*(i+1);
        }
        let ponderada = [];
        ponderada[id_serie] = total_valores/total_indices;
        this.setState({mediaPonderada: ponderada});
    }

    render(){
        return(
            <div>
                <div className="row">
                    <div className="col-md-12">
                        <div style={{textAlign: 'center'}}>
                            <button className="btn btn-primary btn-lg bg-pri" style={{border:'0'}}>{this.state.periodo}</button>
                            <div style={{marginTop:'-19px'}}>
                                <i className="fa fa-sort-down fa-2x" style={{color:'#3498DB'}} />
                            </div>
                        </div>
                    </div>
                </div>
                <div className="row" style={{display:'none'}}>
                    <div className="col-md-12">
                        <div className="icons-list-items icon-list-item-1"></div>
                        <div className="icons-list-items icon-list-item-1"></div>
                        <div className="icons-list-items icon-list-item-1"></div>
                        <div className="icons-list-items icon-list-item-1"></div>
                    </div>
                    <br/>
                </div>
                {/*<div className="row">
                    <div className="col-md-12">
                        <div className="icons-list-items icon-list-item-1" style={{float: 'left'}}></div>
                        <h5>&nbsp;&nbsp;{this.state.serie}</h5>
                    </div>
                </div>*/}
                <br/>
                <div className="row text-center" id="calcs">
                    <div className="col-md-2">
                        <div className="icons-list-140-150 icon-list-140-150-1">
                            <h4 style={this.state.styleCalcs}>Mínima</h4>
                        </div>
                        <br/>
                        <h4 className="" >{formatNumber(this.state.minValue.valor, this.props.decimais, ',', '.')}</h4>
                        <p>{this.state.minValue.sigla} - {this.state.minValue.nome}</p>
                    </div>
                    <div className="col-md-2">
                        <div className="icons-list-140-150 icon-list-140-150-1">
                            <h4 style={this.state.styleCalcs}>Máxima</h4>
                        </div>
                        <br/>
                        <h4 className="" >{formatNumber(this.state.maxValue.valor, this.props.decimais, ',', '.')}</h4>
                        <p>{this.state.maxValue.sigla} - {this.state.maxValue.nome}</p>
                    </div>
                    <div className="col-md-2">
                        <div className="icons-list-140-150 icon-list-140-150-1">
                            <h4 style={this.state.styleCalcs}>Média</h4>
                        </div>
                        <br/>
                        <h4 className="">{formatNumber(this.state.media[this.state.id], 2, ',', '.')}</h4>

                    </div>
                    <div className="col-md-2">
                        <div className="icons-list-140-150 icon-list-140-150-1">
                            <h4 style={{paddingTop: '45px'}}>Média Ponderada</h4>
                        </div>
                        <br/>
                        <h4 className="" >{formatNumber(this.state.mediaPonderada[this.state.id], 2, ',', '.')}</h4>

                    </div>
                    <div className="col-md-2">
                        <div className="icons-list-140-150 icon-list-140-150-1">
                            <h4 style={this.state.styleCalcs}>Mediana</h4>
                        </div>
                        <br/>
                        <h4 className="" >{formatNumber(this.state.mediana[this.state.id], 2, ',', '.')}</h4>

                    </div>
                    <div className="col-md-2" style={{opacity: this.state.moda[this.state.id] > 0 ? 1 : 0.5}}>
                        <div className="icons-list-140-150 icon-list-140-150-1">
                            <h4 style={this.state.styleCalcs}>Moda</h4>
                        </div>
                        <br/>
                        <h4 className="" style={{display: this.state.moda[this.state.id] > 0 ? 'block' : 'none'}}>{formatNumber(this.state.moda[this.state.id], 2, ',', '.')}</h4>
                        <h4 className="" style={{display: this.state.moda[this.state.id] > 0 ? 'none' : 'block'}}>-</h4>
                    </div>
                </div>
                <br/>
                <div style={{float: 'right', marginLeft:'5px'}}>
                    <Download btnDownload="downloadCalcs" divDownload="calcs" arquivo="calculos.png"/>
                </div>
                <div style={{float: 'right', marginLeft:'5px'}}>
                    <Print divPrint="calcs" imgPrint="imgCalcs"/>
                </div>
                <div style={{clear: 'both'}}/>
            </div>
        );
    }
}
