class Filtros extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            tema: this.props.tema_id,
            indicador: null,
            abrangencia: null,
        };

        this.setIndicador = this.setIndicador.bind(this);
        this.setAbrangencia = this.setAbrangencia.bind(this);
        this.setTema = this.setTema.bind(this);
    }

    setIndicador(indicador){
        this.setState({indicador: indicador})
    }

    setAbrangencia(abrangencia){
        this.setState({abrangencia: abrangencia});
    }

    setTema(tema){
        this.setState({tema: tema});
    }

    render(){

        return (
            <div>
                <Temas
                    tema_id={this.state.tema}
                    setTema={this.setTema}
                />
                <div style={{clear: 'both'}}/>
                <br/>
                <Indicadores setIndicador={this.setIndicador} tema_id={this.state.tema}/>
                <Abrangencia setAbrangencia={this.setAbrangencia} tema_id={this.state.tema}/>
                <div style={{marginTop: '5px'}}>
                    <Series
                        url="get-series-filtros"
                        select="mark-one"
                        parameters={{tema_id: this.state.tema, indicador: this.state.indicador, abrangencia: this.state.abrangencia, serieMarked: this.state.serieMarked}}
                        serieMarked={this.serieMarked}
                    />
                </div>

            </div>
        );
    }
}