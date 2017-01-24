class Regions extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            data: {},
            min: 0,
            max: 0,
            minValue: 0,
            maxValue: 0,
            maxUp: 0,
            maxDown: 0,
            styleNumber: {fontSize: '50px', fontWeight: 'bold'}
        };

        this.minMaxValue = this.minMaxValue.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.min != props.data.min || this.state.max != props.data.max) {
            this.setState({min: props.data.min, max: props.data.max, data: props.data.values}, function(){
                this.minMaxValue(this.state.data);
                this.loadData();
            });
        }
    }

    loadData(){
        $.ajax({
            method:'GET',
            url: "valores-inicial-final-regiao/"+this.state.min+"/"+this.state.max,
            cache: false,
            success: function(data) {
                //console.log('region.js, loaddata', data);
                this.setState({data: data}, function(){
                    this.calcMaxUpDown();
                });
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    minMaxValue(data) {
        //console.log(data);
        let sort = data.sort(function(a, b){
            if(parseFloat(a.total) < parseFloat(b.total)){
                return -1;
            }
            if(parseFloat(a.total) > parseFloat(b.total)){
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

            let start = parseFloat(this.state.data[i].valor);
            let end = parseFloat(this.state.data[++i].valor);

            let variacao = (end * 100 / start) - 100;
            regions[cont] = {
                uf: this.state.data[i].uf,
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

        this.setState({
            maxDown: regions[0],
            maxUp: regions[last]
        });
    }


    render(){

        let iconGreenUp = <div className="icons-arrows icon-green-up"></div>;
        let iconGreenDown = <div className="icons-arrows icon-green-down"></div>;
        let iconRedUp = <div className="icons-arrows icon-red-up"></div>;
        let iconRedDown = <div className="icons-arrows icon-red-down"></div>;

        let down = <p>Maior queda</p>;
        let multiplicadorDown = -1;
        let iconDown = iconRedDown;
        if(this.state.maxDown >= 0){
            down = <p>Menor crescimento</p>;
            multiplicadorDown = 1;
            iconDown = iconGreenUp;
        }

        let up = <p>Maior crescimento</p>;
        let multiplicadorUp = 1;
        let iconUp = iconGreenUp;
        if(this.state.maxDown < 0){
            up = <p>Menor queda</p>;
            multiplicadorUp = 1;
            iconUp = iconRedDown;
        }

        return(
            <div className="row">
                <div className="col-md-3 col-lg-3 text-center">
                    <h4>{this.state.minValue.uf} - {this.state.minValue.nome}</h4>
                    <div className="line_title bg-pri"></div>
                    <br/>
                    <img src={"img/maps/png/"+this.state.minValue.uf+".png"} alt=""/>
                    <br/>
                    <p>É a região com menor índice</p>
                    <br/>
                    <p style={this.state.styleNumber}>{this.state.minValue.total}</p>
                </div>
                <div className="col-md-3 col-lg-3 text-center">
                    <h4>{this.state.maxValue.uf} - {this.state.maxValue.nome}</h4>
                    <div className="line_title bg-pri"></div>
                    <br/>
                    <img src={"img/maps/png/"+this.state.maxValue.uf+".png"} alt=""/>
                    <br/>
                    <p>É a região com maior índice</p>
                    <br/>
                    <p style={this.state.styleNumber}>{this.state.maxValue.total}</p>
                </div>
                <div className="col-md-3 col-lg-3 text-center">
                    <h4>{this.state.maxDown.uf} - {this.state.maxDown.nome}</h4>
                    <div className="line_title bg-pri"></div>
                    <br/>
                    <img src={"img/maps/png/"+this.state.maxDown.uf+".png"} alt=""/>
                    <br/>
                    {down} {iconDown}
                    <br/>
                    <p style={this.state.styleNumber}>{numeral(this.state.maxDown.variacao*multiplicadorDown).format('0,0.00')}%</p>
                </div>
                <div className="col-md-3 col-lg-3 text-center">
                    <h4>{this.state.maxUp.uf} - {this.state.maxUp.nome}</h4>
                    <div className="line_title bg-pri"></div>
                    <br/>
                    <img src={"img/maps/png/"+this.state.maxUp.uf+".png"} alt=""/>
                    <br/>
                    {up} {iconUp}
                    <br/>
                    <p style={this.state.styleNumber}>{numeral(this.state.maxUp.variacao*multiplicadorUp).format('0,0.00')}%</p>
                </div>
            </div>
        );
    }
}
