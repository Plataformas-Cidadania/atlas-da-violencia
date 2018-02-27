class PageFilters extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            items: [],
            tema: props.tema_id,
            search: '',
            indicadores: [],
            abrangencias: [],
        };

        this.setTema = this.setTema.bind(this);
    }

    componentDidMount(){
        this.loadItems();
    }

    setTema(tema){
        this.setState({tema: tema});
    }

    loadItems(){
        $.ajax({
            method:'POST',
            url: "list-series",
            data:{
                parameters:{
                    tema_id: this.state.tema,
                    search: this.state.search,
                    indicadores: this.state.indicadores,
                    abrangencias: this.state.abrangencias,
                }
            },
            cache: false,
            success: function(data) {
                //console.log('values-for-types', data);
                this.setState({items: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    render(){
        return(
            <div className="container">
                <Temas
                    tema_id={this.state.tema}
                    setTema={this.setTema}
                />
                <br/><br/><br/>
                <hr style={{width:'95%'}}/>
                <br/><br/>

                <div className="row">
                    <div className="col-md-3">
                        <fieldset>
                            <legend>Pesquisa</legend>
                            <div style={{margin: '10px'}}>
                                <input className='form-control' type="text"/>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Indicadores</legend>
                            <div style={{margin: '10px'}}>
                                <Filter
                                    url='get-indicadores'
                                    text='pesquise pelos indicadores'
                                    conditions={{
                                        tema_id:this.state.tema,
                                    }}
                                />
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Abrangências</legend>
                            <div style={{margin: '10px'}}>
                                <Filter
                                    url='get-abrangencias'
                                    text='pesquise pelas abrangencias'
                                    conditions={{
                                        tema_id:this.state.tema,
                                    }}
                                />
                            </div>
                        </fieldset>
                    </div>
                    <div className="col-md-9">
                        <List
                            items={this.state.items}
                            head={['Série', 'Unidade', 'Frequência', 'Período']}
                        />
                    </div>
                </div>
            </div>
        );
    }
}

ReactDOM.render(
    <PageFilters tema_id={tema_id}/>,
    document.getElementById('filtros')
);