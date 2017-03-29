class Newsletter extends React.Component{
    constructor(props){
        super(props);
        state = {
            nome: "",
            email: ""
        };

        this.submit = this.submit.bind(this);
        this.handleChande = this.handleChande.bind(this);
    }

    handleChande(event){
        console.log(event.target);
    }

    submit(){

    }




    render(){
        return(
            <div className="bg-pri box-news" ng-class="{'alto-contraste': altoContrasteAtivo}">
                <div className="container">
                    <br/>
                        <div className="row">
                            <div className="col-md-4">
                                <h3>Newsletter</h3>
                                <p>Receba nossas novidades do portal e novos índices</p>
                            </div>
                            <div className="col-md-3">
                                <br/><br/>
                                <input type="text" class="form-control" placeholder="Nome" onChange={this.handleChande}/>
                            </div>
                            <div className="col-md-3">
                                <br/><br/>
                                <input type="text" class="form-control" placeholder="E-mail" onChange={this.handleChande}/>
                            </div>
                            <div className="col-md-2">
                                <br/>
                                    <button className="btn btn-sec btn-padding">Enviar</button>
                            </div>
                        </div>
                        <br/>
                </div>
            </div>
        );
    }
}