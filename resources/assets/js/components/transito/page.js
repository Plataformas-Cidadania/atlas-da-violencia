class Page extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            type: null,
        };

        this.checkType = this.checkType.bind(this);
    }

    checkType(type){
        this.setState({type: type});
    }

    render(){

        return(
            <div>
                <div className="container">
                    <h1>Acidentes de Transito</h1>
                    <div className="line_title bg-pri"/>
                    <br/><br/>
                    <Types checkType={this.checkType}/>
                </div>
                <br/>
                <Map id="1" type={this.state.type} />
            </div>
        );
    }
}

ReactDOM.render(
    <Page />,
    document.getElementById('page')
);



