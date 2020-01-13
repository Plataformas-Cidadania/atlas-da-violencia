class Page extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            data: null,
            linha: null,
        };

        this.load = this.load.bind(this);
        //this.handleLinha = this.handleLinha.bind(this);
    }

    componentDidMount(){
        this.load();
    }

    /*handleLinha(e){
        this.setState({linha: e.target.value});
    }*/

    load(){
        let _this = this;
        $.ajax({
            method:'GET',
            url: 'get-metro',
            data:{
            },
            cache: false,
            success: function(data) {
                console.log(data);

                _this.setState({data: data});

            },
            error: function(xhr, status, err) {
                console.error(status, err.toString());
                _this.setState({loading: false});
            }
        });
    }

    render(){
        return (
            <div>

                {/*<div className="container">

                    <div className="row box-pesquisa bg-pri">
                        <div className="col-md-12">

                                <h2>Encontre a linha desejada</h2>
                                <div className="form-row align-items-center">
                                    <div className="col-auto">
                                        <input type="text" className="form-control" id="linha" onChange={this.handleLinha}
                                               placeholder="linha"/>
                                    </div>
                                    <div className="col-auto">
                                        <button className="btn btn-default" onClick={() => this.load()}>Pesquisar</button>
                                    </div>
                                    <div><br/><br/></div>
                                </div>

                        </div>
                    </div>
                </div>*/}

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



