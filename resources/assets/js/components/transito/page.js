class Page extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            idTypes: [],
            idTypesAccident: [],
            filter: 0
        };

        this.checkType = this.checkType.bind(this);
        this.checkTypeAccident = this.checkTypeAccident.bind(this);
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

    actionFilter(){
        this.setState({filter: 1}, function(){
            this.setState({filter: 0});
        });
    }

    render(){

        return(
            <div>
                <div className="container">
                    <h1>Acidentes de Transito</h1>
                    <div className="line_title bg-pri"/>
                    <br/><br/>
                    <Filters checkType={this.checkType} checkTypeAccident={this.checkTypeAccident} actionFilter={this.actionFilter}/>
                    {/*<br/><br/>
                    <Types checkType={this.checkType}/>*/}
                </div>
                <br/>
                <Map id="1" types={this.state.idTypes} typesAccident={this.state.idTypesAccident} filter={this.state.filter} actionFilter={this.actionFilter} />
            </div>
        );
    }
}

ReactDOM.render(
    <Page />,
    document.getElementById('page')
);



