class Page extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            idTypes: [],
            idTypesAccident: [],
            idGender: [],
            tipoTerritorioSelecionado: 2,//1 - país, 2 - regiao, 3 - uf, 4 - municipio
            codigoTerritorioSelecionado: [11,12,13,14,15], //203 - Brasil 13 - SE
            tipoTerritorioAgrupamento: 2,//1 - país, 2 - regiao, 3 - uf, 4 - municipio
            filter: 0,
            year: null,
            month: null,

        };

        this.checkType = this.checkType.bind(this);
        this.checkTypeAccident = this.checkTypeAccident.bind(this);
        this.checkGender = this.checkGender.bind(this);
        this.checkRegion = this.checkRegion.bind(this);
        this.checkYear = this.checkYear.bind(this);
        this.checkMonth = this.checkMonth.bind(this);
        this.actionFilter = this.actionFilter.bind(this);

    }

    checkType(types){
        let ids = [];
        types.find(function(item){
            ids.push(item.id);
        });
        this.setState({idTypes: ids});
    }

    checkTypeAccident(types){
        let ids = [];
        types.find(function(item){
            ids.push(item.id);
        });
        this.setState({idTypesAccident: ids});
    }

    checkGender(types){
        let ids = [];
        types.find(function(item){
            ids.push(item.id);
        });
        this.setState({idGender: ids});
    }

    checkYear(year){
        this.setState({year: year});
    }

    checkMonth(month){
        this.setState({month: month});
    }

    checkRegion(types){
        let ids = [];
        let tipo_territorio = null;
        types.find(function(item){
            ids.push(parseInt(item.id));
            tipo_territorio = parseInt(item.tipo_territorio);
        });
        this.setState({codigoTerritorioSelecionado: ids, tipoTerritorioSelecionado: tipo_territorio, tipoTerritorioAgrupamento: tipo_territorio});
    }

    actionFilter(){
        this.setState({filter: 1}, function(){
            this.setState({filter: 0});
        });
    }

    render(){

        //console.log('FILTER', this.state.filter);

        return(
            <div>
                <div className="container">
                    <h1>Acidentes de Transito</h1>
                    <div className="line_title bg-pri"/>
                    <br/><br/>
                    <Filters
                        id = {this.props.id}
                        checkType={this.checkType}
                        checkTypeAccident={this.checkTypeAccident}
                        checkGender={this.checkGender}
                        checkRegion={this.checkRegion}
                        checkYear={this.checkYear}
                        checkMonth={this.checkMonth}
                        actionFilter={this.actionFilter}
                        tipoTerritorioSelecionado = {this.state.tipoTerritorioSelecionado}
                        codigoTerritorioSelecionado = {this.state.codigoTerritorioSelecionado}
                    />
                    {/*<br/><br/>
                    <Types checkType={this.checkType}/>*/}
                </div>
                <br/>
                <Map
                     id={this.props.id}
                     types={this.state.idTypes}
                     typesAccident={this.state.idTypesAccident}
                     genders={this.state.idGender}
                     tipoTerritorioSelecionado = {this.state.tipoTerritorioSelecionado}
                     codigoTerritorioSelecionado = {this.state.codigoTerritorioSelecionado}
                     tipoTerritorioAgrupamento = {this.state.tipoTerritorioAgrupamento}
                     filter={this.state.filter}
                     actionFilter={this.actionFilter}
                     year={this.state.year}
                     month={this.state.month}
                />
            </div>
        );
    }
}

ReactDOM.render(
    <Page id={serie_id}/>,
    document.getElementById('page')
);



