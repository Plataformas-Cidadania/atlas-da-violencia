class Filters extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            types: [],
            typesAccident: [],
        };

        this.checkType = this.checkType.bind(this);
        this.checkTypeAccident = this.checkTypeAccident.bind(this);
        this.actionFilter = this.actionFilter.bind(this);

    }

    checkType(types){
        this.setState({types: types}, function(){
            this.props.checkType(this.state.types);
        });
    }

    checkTypeAccident(types){
        this.setState({typesAccident: types}, function(){
            this.props.checkTypeAccident(this.state.typesAccident);
        });
    }

    actionFilter(){
        this.props.actionFilter();
    }

    render(){

        return(
            <div>
                <div className="row">
                    <div className="col-md-6">
                        <RangeYear />
                    </div>
                    <div className="col-md-6">
                        <RangeMonth />
                    </div>
                </div>
                <br/>
                <div className="row">

                    <div className="col-md-3">
                        <fieldset>
                            <legend>Locomoção</legend>
                            <div style={{margin: '10px'}}>
                                <Type checkType={this.checkType}/>
                            </div>
                        </fieldset>
                    </div>

                    <div className="col-md-3">
                        <fieldset>
                            <legend>Tipo de Acidente</legend>
                            <div style={{margin: '10px'}}>
                                <TypeAccident checkTypeAccident={this.checkTypeAccident}/>
                            </div>
                        </fieldset>
                    </div>

                    <div className="col-md-3">
                        <fieldset>
                            <legend>Região</legend>
                            <div style={{margin: '10px'}}>
                                <span>Digite abaixo para filtrar</span>
                                <hr style={{margin: '10px 0'}}/>
                                <input type="text" className="form-control"/>
                            </div>
                        </fieldset>
                    </div>

                    <div className="col-md-3">
                        <fieldset>
                            <legend>Sexo</legend>
                            <div style={{margin: '10px'}}>
                                <span>Selecione para filtrar</span>
                                <hr style={{margin: '10px 0'}}/>
                                <select name="sexo" id="" className="form-control">
                                    <option value="">Todos</option>
                                    <option value="">Masculino</option>
                                    <option value="">Feminino</option>
                                </select>
                            </div>
                        </fieldset>
                    </div>

                </div>
                <br/>
                <div className="row">
                    <div className="col-md-12 text-center">
                        <button className="btn btn-info" style={{width: "300px"}} onClick={this.actionFilter}>Filtrar</button>
                    </div>
                </div>
            </div>
        );
    }
}



