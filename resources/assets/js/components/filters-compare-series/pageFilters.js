class PageFilters extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            items: {data: []},
            tema: props.tema_id,
            search: '',
            indicadores: [],
            abrangencias: [],
            currentPageListItems: 1,
            serieMarked: null,//não parece ser mais utilizado
            abrangencia: null,
            selectedItems:[], //seleção de multiplos items vindos do component list
            processingSelectedItems: false,
            regions: [],
            periodos: [],
            from: null,
            to: null,
            loadingDefaultValues: false,
            loadingItems: false,
            limitItems: 20,
            optionsAbrangencia: [
                {id: 1, title: 'País', plural: ' os Países', on:false, listAll:1, height: '250px'},
                {id: 2, title: 'Região', plural: 'as Regiões', on:false, listAll:1, height: '250px'},
                {id: 3, title: 'UF', plural: 'os Estados', on:false, listAll:1, height: '400px'},
                {id: 7, title: 'Território', plural: 'os Estados', on:false, listAll:1, height: '400px'},
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
        this.setCurrentPageListItems = this.setCurrentPageListItems.bind(this);
        this.handleSearch = this.handleSearch.bind(this);
        this.checkIndicadores = this.checkIndicadores.bind(this);
        this.checkAbrangencias = this.checkAbrangencias.bind(this);
        this.selectSerie = this.selectSerie.bind(this);
        this.setRegions = this.setRegions.bind(this);
        this.loadPeriodos = this.loadPeriodos.bind(this);
        this.loadDefaultValues = this.loadDefaultValues.bind(this);
        this.loadRegions = this.loadRegions.bind(this);
        this.selectedItems = this.selectedItems.bind(this);
        this.processingSelectedItems = this.processingSelectedItems.bind(this);
    }

    componentDidMount(){
        this.loadItems();
    }

    selectedItems(items){
        this.setState({selectedItems: items});
    }

    processingSelectedItems(){
        let ids = "";
        console.log(this.state.selectedItems);
        this.setState({processingSelectedItems: true});
        this.state.selectedItems.find(function(item){
            ids += item.id+',';
        });
        ids = ids.substr(0,ids.length-1);
        $.ajax({
            method:'POST',
            url: "validar-comparar-series",
            data: {
              ids: ids,
            },
            cache: false,
            success: function(data) {
                console.log(data);
                if(data==1){
                    location.href = "dados-series-comparadas/"+ids;
                    return;
                }
                console.log('As séries selecionadas não possuem abrangência em comum');

                this.setState({items: items, processingSelectedItems: false});

            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
                this.setState({processingSelectedItems: false});
            }.bind(this, ids)
        });
    }

    setTema(tema){
        this.setState({
            tema: tema,
            items: [],
            indicadores:[],
            abrangencias: [],
            serieMarked: null,
            abrangencia:null,
            regions: [],
            periodos: [],
            from: null,
            to:null
        }, function(){
            this.loadItems();
        });
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
        //console.log(this.state);
        console.log(this.state.currentPageListItems);
        let emptyItems = {data: []};
        this.setState({items: emptyItems, loadingItems: true});
        $.ajax({
            method:'POST',
            url: "list-series",
            data:{
                parameters:{
                    tema_id: this.state.tema,
                    search: this.state.search,
                    indicadores: this.state.indicadores,
                    abrangencias: this.state.abrangencias,
                    limit: this.state.limitItems,
                },
                page: this.state.currentPageListItems,
            },
            cache: false,
            success: function(data) {
                //console.log('PAGEFILTER - ITEMS', data);
                //let items = {data: data};
                let items = data;

                this.setState({items: items, loadingItems: false});
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
            if(all){
                this.setState({loadingDefaultValues: true});
                this.loadDefaultValues();
                //this.submit();
                return;
            }

            this.loadPeriodos();
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

    loadDefaultValues(){
        this.loadPeriodos().then(function(){
            //console.log(this.state.periodos);
            this.loadRegions().then(function(){
                //console.log(this.state.regions);
                this.submit();
            }.bind(this));
        }.bind(this));
    }

    loadPeriodos(){
        return $.ajax("periodos/"+this.state.serieMarked+"/"+this.state.abrangencia, {
            data: {},
            success: function(data){
                //console.log('range', data);
                this.setState({periodos: data, from:data[0], to:data[data.length-1]}, function(){

                });
            }.bind(this),
            error: function(data){
                console.log('erro');
            }.bind(this)
        });
    }

    loadRegions(){
        return $.ajax({
            method: 'POST',
            url: 'territorios-serie-abrangencia',
            data: {
                id: this.state.serieMarked,
                abrangencia: this.state.abrangencia,
            },
            cache: false,
            success: function(data){
                //console.log('regions default', data);
                this.setState({regions: data});
            }.bind(this),
            error: function(xhr, status, err){
                console.log('erro', err);
            }.bind(this)
        });
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

        console.log('ITEMS', this.state.items);
        let items = this.state.items;
        if(!this.state.items.data){
            items = {data: []};
        }

        return(

            <div className="container">
                <h1>{this.props.lang_inquiries}</h1>
                <br/>
                <div className="row">
                    <div className="col-md-3">
                        <fieldset style={{marginTop: '-15px'}}>
                            <legend>{this.props.lang_themes}</legend>
                            <div style={{margin: '10px'}}>
                                <Temas
                                    tema_id={this.state.tema}
                                    setTema={this.setTema}
                                    lang_select_themes={this.props.lang_select_themes}
                                />
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>{this.props.lang_documents}</legend>
                            <div style={{margin: '10px'}}>
                                <Filter
                                    url='get-indicadores'
                                    text={this.props.lang_search_indicators}
                                    conditions={{
                                        tema_id:this.state.tema,
                                    }}
                                    checkType={this.checkIndicadores}
                                />
                            </div>
                        </fieldset>
                        {/*<fieldset>
                            <legend>Abrangências</legend>
                            <div style={{margin: '10px'}}>
                                <Filter
                                    url='get-abrangencias'
                                    text='Pesquise pelas abrangências'
                                    conditions={{
                                        tema_id:this.state.tema,
                                    }}
                                    checkType={this.checkAbrangencias}
                                />
                            </div>
                        </fieldset>*/}
                    </div>
                    <div className="col-md-9">
                        <input className='form-control' onChange={this.handleSearch} type="text" placeholder={this.props.lang_search_name}/>
                        <br/>
                        <div className="text-center" style={{display: this.state.loadingItems ? '' : 'none'}}><i className="fa fa-spin fa-spinner fa-3x"/></div>
                        <div style={{display: items.data.length > 0 || this.state.loadingItems ? 'none' : ''}}><h4>{this.props.lang_no_results}</h4></div>
                        <div style={{display: items.data.length > 0 ? '' : 'none'}}>
                            <List
                                items={items}
                                head={[this.props.lang_series, this.props.lang_unity, this.props.lang_frequency, '', '']}
                                showId='0'
                                setCurrentPageListItems = {this.setCurrentPageListItems}
                                currentPage = {this.state.currentPageListItems}
                                perPage='20'
                                select={this.selectSerie}
                                abrangencias={this.state.optionsAbrangencia}
                                selectedItems={this.selectedItems}
                                processingSelectedItems={this.processingSelectedItems}
                            />
                        </div>
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

                <div style={{
                    position: 'fixed',
                    top:0, right:0, bottom:0, left:0,
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    width: '100%',
                    height: '100%',
                    zIndex: '99999999999999999',
                    display: this.state.loadingDefaultValues ? '' : 'none',
                }}>
                    <div style={{
                        position: 'absolute',
                        top:0,
                        right:0,
                        bottom:0, left:0,
                        width:'400px',
                        height:'200px',
                        backgroundColor: '#fff',
                        border: 'solid 1px #ccc',
                        paddingTop: '60px',
                        margin:'auto',
                        textAlign: 'center',
                        borderRadius: '5px',
                    }}
                    >
                        <h1>
                            <i className="fa fa-spinner fa-spin"/> {this.props.lang_wait} ...
                        </h1>
                    </div>
                </div>



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
    <PageFilters
        tema_id={tema_id}
        lang_inquiries={lang_inquiries}
        lang_themes={lang_themes}
        lang_series={lang_series}
        lang_documents={lang_documents}
        lang_search_indicators={lang_search_indicators}
        lang_search_name={lang_search_name}
        lang_unity={lang_unity}
        lang_frequency={lang_frequency}
        lang_no_results={lang_no_results}
        lang_wait={lang_wait}
        lang_select_themes={lang_select_themes}
    />,
    document.getElementById('filtros')
);

