class Filters extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            types: [],
            typesAccident: [],
            genders: [],
            regions: [],
            btnFilter: false,
            tipoTerritorioSelecionado: props.tipoTerritorioSelecionado,
        };

        this.checkType = this.checkType.bind(this);
        this.checkTypeAccident = this.checkTypeAccident.bind(this);
        this.checkGender = this.checkGender.bind(this);
        this.checkRegion = this.checkRegion.bind(this);
        this.actionFilter = this.actionFilter.bind(this);
        this.enableBtnFilter = this.enableBtnFilter.bind(this);
        this.disableBtnFilter = this.disableBtnFilter.bind(this);
    }

    componentWillReceiveProps(props){
        if(props.tipoTerritorioSelecionado != this.state.tipoTerritorioSelecionado){
            this.setState({tipoTerritorioSelecionado: props.tipoTerritorioSelecionado});
        }
    }

    checkType(types){
        this.setState({types: types}, function(){
            this.props.checkType(this.state.types);
            this.enableBtnFilter();
        });
    }

    checkTypeAccident(types){
        this.setState({typesAccident: types}, function(){
            this.props.checkTypeAccident(this.state.typesAccident);
            this.enableBtnFilter();
        });
    }

    checkGender(types){
        this.setState({genders: types}, function(){
            this.props.checkGender(this.state.genders);
            this.enableBtnFilter();
        });
    }

    checkRegion(types){
        this.setState({regions: types}, function(){
            this.props.checkRegion(this.state.regions);
            this.enableBtnFilter();
        });
    }

    actionFilter(){
        this.props.actionFilter();
        this.enableBtnFilter();
        this.disableBtnFilter();
    }

    enableBtnFilter(){
        this.setState({btnFilter: true})
    }

    disableBtnFilter(){
        this.setState({btnFilter: false})
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
                                <Region
                                    checkRegion={this.checkRegion}
                                    tipoTerritorioSelecionado = {this.state.tipoTerritorioSelecionado}
                                    codigoTerritorioSelecionado={this.props.codigoTerritorioSelecionado}
                                />
                            </div>
                        </fieldset>
                    </div>


                    <div className="col-md-3">
                        <fieldset>
                            <legend>Sexo</legend>
                            <div style={{margin: '10px'}}>
                                <Gender checkGender={this.checkGender}/>
                            </div>
                        </fieldset>
                    </div>

                </div>
                <br/>
                <div className="row">
                    <div className="col-md-12 text-center">
                        <button className="btn btn-info" style={{width: "300px"}} disabled={!this.state.btnFilter} onClick={this.actionFilter}>Filtrar</button>
                    </div>
                </div>
            </div>
        );
    }
}



