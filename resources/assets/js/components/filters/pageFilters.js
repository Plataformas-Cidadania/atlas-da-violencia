class PageFilters extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            items: [],
            tema: props.tema_id,
            search: '',
            indicadores: [],
            abrangencias: [],
            currentPageListItems: 1,
            serieSelected: null,
            abrangenciaSelected: null,
            regionsSelected: [],
            optionsAbrangencia: [
                {id: 1, title: 'País', plural: ' os Países', on:false, listAll:1, height: '250px'},
                {id: 2, title: 'Região', plural: 'as Regiões', on:false, listAll:1, height: '250px'},
                {id: 3, title: 'UF', plural: 'os Estados', on:false, listAll:1, height: '400px'},
                {id: 4, title: 'Município', plural: 'os Municípios', on:false, listAll:0, height: '400px',
                    filter:[
                        {id: 12, title: 'Acre'},
                        {id: 27, title: 'Alagoas'},
                        {id: 13, title: 'Amazonas'},
                        {id: 16, title: 'Amapá'},
                        {id: 29, title: 'Bahia'},
                        {id: 23, title: 'Ceará'},
                        {id: 53, title: 'Distrito Federal'},
                        {id: 32, title: 'Espirito Santo'},
                        {id: 52, title: 'Goiás'},
                        {id: 21, title: 'Maranhão'},
                        {id: 50, title: 'Mato Grosso do Sul'},
                        {id: 51, title: 'Mato Grosso'},
                        {id: 31, title: 'Minas Gerais'},
                        {id: 15, title: 'Pará'},
                        {id: 41, title: 'Paraná'},
                        {id: 25, title: 'Paraíba'},
                        {id: 26, title: 'Pernambuco'},
                        {id: 22, title: 'Piauí'},
                        {id: 33, title: 'Rio de Janeiro'},
                        {id: 24, title: 'Rio Grande do Norte'},
                        {id: 43, title: 'Rio Grande do Sul'},
                        {id: 11, title: 'Rondônia'},
                        {id: 14, title: 'Roraima'},
                        {id: 42, title: 'Santa Catarina'},
                        {id: 35, title: 'São Paulo'},
                        {id: 28, title: 'Sergipe'},
                        {id: 17, title: 'Tocantins'},
                    ]

                }
            ],
        };

        this.setTema = this.setTema.bind(this);
        this.handleSearch = this.handleSearch.bind(this);
        this.checkIndicadores = this.checkIndicadores.bind(this);
        this.checkAbrangencias = this.checkAbrangencias.bind(this);
        this.selectSerie = this.selectSerie.bind(this);
    }

    componentDidMount(){
        this.loadItems();
    }

    setTema(tema){
        this.setState({tema: tema});
    }

    setCurrentPageListItems(page){
        this.setState({currentPageListItems: page}, function(){
            this.loadItems();
        });
    }

    handleSearch(e){
        e.preventDefault();
        this.setState({search: e.target.value}, function(){
            this.loadItems();
        });
    }

    checkIndicadores(types){
        let ids = [];
        types.find(function(item){
            ids.push(item.id);
        });
        this.setState({indicadores: ids}, function(){
            this.loadItems();
        });
    }

    checkAbrangencias(types){
        let ids = [];
        types.find(function(item){
            ids.push(item.id);
        });
        this.setState({abrangencias: ids}, function(){
            this.loadItems();
        });
    }

    loadItems(){
        console.log(this.state);
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

    selectSerie(id){
        this.setState({serieSelected: id}, function(){
            $("#modalAbrangencias").modal();
        });
    }

    enviar(e){
        e.preventDefault();
        console.log('enviar');
    }

    selectedAbrangencia(){
        let option = null;
        return this.state.optionsAbrangencia.find(function(op){
            if(op.on){
                option = op.id;
                return op;
            }
        });
        //return option;
    }

    setRegions(){

    }

    render(){

        let selectItems = (
            <SelectItems
                url="territorios"
                option={this.selectedAbrangencia()}
                options={this.state.optionsAbrangencia}
                setItems={this.setRegions}
            />
        );

        selectItems = null;


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
                                <input className='form-control' onChange={this.handleSearch} type="text"/>
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
                                    checkType={this.checkIndicadores}
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
                                    checkType={this.checkAbrangencias}
                                />
                            </div>
                        </fieldset>
                    </div>
                    <div className="col-md-9">
                        <List
                            items={this.state.items}
                            head={['Série', 'Abrangência', 'Unidade', 'Frequência', 'Inicial', 'Final']}
                            showId='0'
                            setCurrentPageListItems = {this.setCurrentPageListItems}
                            perPage='20'
                            select={this.selectSerie}
                        />
                    </div>
                </div>

                <Modal
                    id="modalAbrangencias"
                    title="Selecione os Territórios"
                    body={selectItems}
                    buttons={(
                        <div>
                            <button type="button" className="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="button" className="btn btn-primary" onClick={this.enviar}>Continuar</button>
                        </div>
                    )}
                />

            </div>
        );
    }
}

ReactDOM.render(
    <PageFilters tema_id={tema_id}/>,
    document.getElementById('filtros')
);