class Page extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            data: null,
            linha: null,
        };

        this.load = this.load.bind(this);
        this.handleLinha = this.handleLinha.bind(this);
    }

    componentDidMount(){
    }

    handleLinha(e){
        this.setState({linha: e.target.value});
    }

    load(){
        let _this = this;
        $.ajax({
            method:'GET',
            //url: 'http://dadosabertos.rio.rj.gov.br/apiTransporte/apresentacao/rest/index.cfm/obterPosicoesDaLinha/'+this.state.linha,
            url: 'get-bus/'+this.state.linha,
            data:{
            },
            cache: false,
            success: function(data) {
                //console.log(data);

                _this.setState({data: data});

                //let totalOnibus =  data.DATA.length;
                //document.getElementById('totalOnibus').innerHTML = totalOnibus;

            },
            error: function(xhr, status, err) {
                console.error(status, err.toString());
                _this.setState({loading: false});
            }
        });
        /*
        setInterval(function(){
            this.load();
        }.bind(this), 10000);*/
    }

    render(){
        return (
            <div>

                <div className="container">

                    {/*<div className="row" style={{position: "absolute", top: "-5px", zIndex: 999999999999999999999999999}}>*/}
                    <div className="row box-pesquisa bg-pri">
                        <div className="col-md-12">

                               
                                <div className="form-row align-items-center">
                                    <div className="col-md-10">
                                        <input type="text" className="form-control" id="linha" onChange={this.handleLinha}
                                               placeholder="Encontre a linha desejada"/>
                                    </div>
                                    <div className="col-md-2">
                                        <button className="btn btn-default" onClick={() => this.load()}>Pesquisar</button>
                                    </div>
                                    <div><br/><br/></div>
                                </div>

                        </div>
                    </div>
                </div>

                <Map
                    mapId="mapBus"
                    data={this.state.data}
                />



            </div>

        );
    }
}

ReactDOM.render(
    <Page/>,
    document.getElementById('page')
);
