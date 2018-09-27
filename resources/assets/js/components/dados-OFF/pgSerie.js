class PgSerie extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            tema_id: this.props.tema_id,
        }
    }



    render(){
        return(
            <div>
                <Filtros tema_id={this.state.tema_id}/>
            </div>
        );
    }
}

ReactDOM.render(
    <PgSerie tema_id={tema_id} />,
    document.getElementById('pgSerie')
);