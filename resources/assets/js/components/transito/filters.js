class Filters extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            type: null,
        };

    }

    filterType(type){
        console.log(type);
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
                                <Type filterType={this.filterType}/>
                            </div>
                        </fieldset>
                    </div>

                    <div className="col-md-3">
                        <fieldset>
                            <legend>Tipo de Acidente</legend>
                            <div style={{margin: '10px'}}>
                                <span>Digite abaixo para filtrar</span>
                                <hr style={{margin: '10px 0'}}/>
                                <input type="text" className="form-control"/>
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
                        <button className="btn btn-info" style={{width: "300px"}}>Filtrar</button>
                    </div>
                </div>
            </div>
        );
    }
}



