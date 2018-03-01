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
            serieMarked: null,
            abrangencia: null,
            regions: [],
            periodos: [],
            from: null,
            to: null,
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
        this.setRegions = this.setRegions.bind(this);
        this.loadPeriodos = this.loadPeriodos.bind(this);
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

    selectSerie(item, all){

        let optionsAbrangencia = this.state.optionsAbrangencia;

        optionsAbrangencia.find(function(option){
            option.on = option.id === item.tipo_regiao;
        });

        console.log('ITEM CLICADO', item);
        console.log('OPTIONS ABRANGÊNCIAS', optionsAbrangencia);

        this.setState({serieMarked: item.id, abrangencia: item.tipo_regiao}, function(){
            this.loadPeriodos();
            if(all){
                this.submit();
                return;
            }
            $("#modalAbrangencias").modal();
        });
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

    setRegions(regions){

        let regionsId = [];
        for(let i in regions){
            regionsId.push(regions[i].id)
        }

        console.log(regionsId);

        this.setState({regions: regionsId});
    }

    loadPeriodos(){
        $.ajax("periodos/"+this.state.serieMarked+"/"+this.state.abrangencia, {
            data: {},
            success: function(data){
                //console.log('range', data);
                this.setState({periodos: data, from:data[0], to:data[data.length-1]}, function(){

                });
            }.bind(this),
            error: function(data){
                console.log('erro');
            }.bind(this)
        })
    }

    submit(){
        $('#formFiltros').submit();
    }


    render(){

        let selectItems = (
            <SelectItems
                url="territorios"
                conditions={{id: this.state.serieMarked}}
                option={this.selectedAbrangencia()}
                options={this.state.optionsAbrangencia}
                setItems={this.setRegions}
            />
        );

        let btnContinuar = <button type="button" className="btn btn-primary" onClick={() => this.submit()} disabled >Continuar</button>;
        if(this.state.regions.length > 0 && this.state.periodos.length > 0 && this.state.from && this.state.to && this.state.abrangencia && this.state.serieMarked){
            btnContinuar = <button type="button" className="btn btn-primary" onClick={() => this.submit()}  >Continuar</button>
        }


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
                            head={['Série', 'Abrangência', 'Unidade', 'Periodicidade', 'Inicial', 'Final', 'Territórios', '']}
                            showId='0'
                            setCurrentPageListItems = {this.setCurrentPageListItems}
                            perPage='20'
                            select={this.selectSerie}
                            abrangencias={this.state.optionsAbrangencia}
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
                            {btnContinuar}
                        </div>
                    )}
                />

                <form id="formFiltros" style={{display:'none'}} action="dados-series" method="POST">
                    <input type="hidden" name="_token" value={$('meta[name="csrf-token"]').attr('content')}/>
                    <input type="hidden" name="id" value={this.state.serieMarked}/>
                    <input type="hidden" name="from" value={this.state.from}/>
                    <input type="hidden" name="to" value={this.state.to}/>
                    <input type="hidden" name="periodos" value={this.state.periodos}/>
                    <input type="hidden" name="regions" value={this.state.regions}/>
                    <input type="hidden" name="abrangencia" value={this.state.abrangencia}/>
                    {/*<input type="hidden" name="typeRegion" value={this.state.typeRegion}/>
                    <input type="hidden" name="typeRegionSerie" value={this.state.typeRegionSerie}/>*/}
                </form>

            </div>
        );
    }
}

ReactDOM.render(
    <PageFilters tema_id={tema_id}/>,
    document.getElementById('filtros')
);