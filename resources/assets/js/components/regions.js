class Regions extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: this.props.id,
            loading: false,
            data: {},
            min: 0,
            max: 0,
            periodo: 0,
            minValue: 0,
            maxValue: 0,
            maxUp: 0,
            maxDown: 0,
            styleNumber: {fontSize: '35px', fontWeight: 'bold'},
            showMaxUpDown: true
        };

        this.minMaxValue = this.minMaxValue.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.periodo != props.data.periodo || this.state.min != props.min || this.state.max != props.max) {
            this.setState({periodo: props.data.periodo, min: props.min, max: props.max, data: props.data.valores}, function(){
                if(this.state.data){
                    this.minMaxValue(this.state.data);
                }
                this.loadData();
            });
        }
    }

    loadData(){
        this.setState({loading: true});
        $.ajax({
            method:'GET',
            url: "valores-inicial-final-regiao/"+this.state.id+"/"+this.state.min+"/"+this.state.max+"/"+this.props.regions+"/"+this.props.abrangencia,
            cache: false,
            success: function(data) {
               //console.log('### REGIONS.loadData ###', data);
                this.setState({data: data, loading:false}, function(){
                    this.calcMaxUpDown();
                });
            }.bind(this),
            error: function(xhr, status, err) {
              console.log('erro');
            }.bind(this)
        });
    }

    minMaxValue(data) {
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
            maxValue: sort[sort.length-1]
        });

    }

    calcMaxUpDown(){
        //console.log(this.state.data);
        let regions = [];
        let variacoes = [];
        let length = this.state.data.length;
        let cont = 0;
        for(let i=0; i<length; i++){
            //regions[this.state.data[i].uf] = {};
            //regions[this.state.data[i].uf].start = this.state.data[i].valor;
            //regions[this.state.data[i].uf].end = this.state.data[++i].valor;


            let j = i+1;
            //para o fato de não haver valor em um determinado território
            if(!this.state.data[i] || !this.state.data[j]){
                this.setState({showMaxUpDown: false});
                return;
            }
            let start = parseFloat(this.state.data[i].valor);
            let end = parseFloat(this.state.data[++i].valor);


            let variacao = (end * 100 / start) - 100;
            regions[cont] = {
                sigla: this.state.data[i].sigla,
                nome: this.state.data[i].nome,
                variacao: variacao
            };
            cont++;
        }

        regions = regions.sort(function(a, b){
            if(a.variacao < b.variacao){
                return -1;
            }
            if(a.variacao > b.variacao){
                return 1;
            }
            return 0;
        });

        let last = regions.length-1;

       //console.log('### REGIONS.calcMaxUpDown ###', regions);

        this.setState({
            maxDown: regions[0],
            maxUp: regions[last],
            showMaxUpDown: true
        });
    }


    render(){

        let iconGreenUp = <div className="icons-arrows icon-green-up"></div>;
        let iconGreenDown = <div className="icons-arrows icon-green-down"></div>;
        let iconRedUp = <div className="icons-arrows icon-red-up"></div>;
        let iconRedDown = <div className="icons-arrows icon-red-down"></div>;

       //console.log('###  REGIONS.RENDER  ###',this.state.maxDown);

        let down = <p>Maior queda</p>;
        let multiplicadorDown = -1;
        let iconDown = iconRedDown;
        if(this.state.maxDown.variacao >= 0){
            down = <p>Menor crescimento</p>;
            multiplicadorDown = 1;
            iconDown = iconGreenUp;
        }


        let up = <p>Maior crescimento</p>;
        let multiplicadorUp = 1;
        let iconUp = iconGreenUp;
        if(this.state.maxUp.variacao < 0){
            up = <p>Menor queda</p>;
            multiplicadorUp = 1;
            iconUp = iconRedDown;
        }

        return(
            <div>

                <div className="row" id="regions">


                    <div className="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div className="row">
                            <div className="col-md-12">
                                <div style={{textAlign: 'center', clear: 'both'}}>
                                    <button className="btn btn-primary btn-lg bg-pri" style={{border:'0'}}>{this.state.periodo}</button>
                                    <div style={{marginTop:'-19px'}}>
                                        <i className="fa fa-sort-down fa-2x" style={{color:'#3498DB'}} />
                                    </div>
                                </div>
                                <br/>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-md-6 text-center">
                                <h4>Menor Índice</h4>
                                <div className="line_title bg-pri"></div>
                                <br/>
                                <img src={"img/maps/png/"+this.state.minValue.sigla+".png"} alt=""/>
                                <br/>
                                <p>{this.state.minValue.sigla} - {this.state.minValue.nome}</p>
                                <br/>
                                <p style={this.state.styleNumber}>{formatNumber(this.state.minValue.valor, this.props.decimais, ',', '.')}</p>
                            </div>
                            <div className="col-md-6 text-center">
                                <h4>Maior Índice</h4>
                                <div className="line_title bg-pri"></div>
                                <br/>
                                <img src={"img/maps/png/"+this.state.maxValue.sigla+".png"} alt=""/>
                                <br/>
                                <p>{this.state.maxValue.sigla} - {this.state.maxValue.nome}</p>
                                <br/>
                                <p style={this.state.styleNumber}>{formatNumber(this.state.maxValue.valor, this.props.decimais, ',', '.')}</p>
                            </div>
                        </div>
                    </div>





                    <div className="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div className="row">
                            <div className="col-md-12">
                                <div style={{textAlign: 'center', clear: 'both'}}>
                                    <button className="btn btn-primary btn-lg bg-pri" style={{border:'0'}}>{this.state.min} - {this.state.max}</button>
                                    <div style={{marginTop:'-19px'}}>
                                        <i className="fa fa-sort-down fa-2x" style={{color:'#3498DB'}} />
                                    </div>
                                </div>
                                <br/>
                            </div>
                        </div>

                        <div className=" row text-center" style={{display: this.state.showMaxUpDown ? 'none' : 'block'}}>
                            <div className="col-md-12">
                                <br/><br/><br/><br/>
                                <h4 style={{padding: '20px'}}>Em função de não existirem alguns dados não foi possível calcular a queda e crescimento.</h4>
                            </div>
                        </div>

                        <div className="row" style={{display: this.state.showMaxUpDown ? 'block' : 'none'}}>

                            <div className="col-md-12 col-lg-12 text-center text-center" style={{display: this.state.loading ? 'block' : 'none'}}>
                                <br/><br/>
                                <i className="fa fa-5x fa-spinner fa-spin"> </i>
                            </div>

                            <div className="col-md-6 text-center" style={{display: this.state.loading ? 'none' : 'block'}}>
                                <h4>{down}</h4>
                                <div className="line_title bg-pri"></div>
                                <br/>
                                <img src={"img/maps/png/"+this.state.maxDown.sigla+".png"} alt=""/>
                                <br/>
                                {this.state.maxDown.sigla} - {this.state.maxDown.nome}
                                <br/>
                                <div className="row" style={{paddingTop:'15px'}}>
                                    <div className="col-md-8 text-right" style={{paddingTop:'15px'}}>
                                        <p style={this.state.styleNumber}>{formatNumber(this.state.maxDown.variacao*multiplicadorDown, 2, ',', '.')}%</p>
                                    </div>
                                    <div className="col-md-4">
                                        {iconDown}
                                    </div>
                                </div>
                            </div>
                            <div className="col-md-6 text-center" style={{display: this.state.loading ? 'none' : 'block'}}>
                                <h4>{up}</h4>
                                <div className="line_title bg-pri"></div>
                                <br/>
                                <img src={"img/maps/png/"+this.state.maxUp.sigla+".png"} alt=""/>
                                <br/>
                                {this.state.maxUp.sigla} - {this.state.maxUp.nome}
                                <div className="row" style={{paddingTop:'15px'}}>
                                    <div className="col-md-8 text-right" style={{paddingTop:'15px'}}>
                                        <p style={this.state.styleNumber}>{formatNumber(this.state.maxUp.variacao*multiplicadorUp, 2, ',', '.')}%</p>
                                    </div>
                                    <div className="col-md-4">
                                        {iconUp}
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>



                </div>
                <br/>
                <div style={{float: 'right', marginLeft:'5px'}}>
                    <Download btnDownload="downloadRegions" divDownload="regions" arquivo="taxas.png"/>
                </div>
                <div style={{float: 'right', marginLeft:'5px'}}>
                    <Print divPrint="regions" imgPrint="imgRegions"/>
                </div>
                <div style={{clear: 'both'}}/>


            </div>



        );
    }
}
