class PageFilters extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            items: { data: [] },
            tema: props.tema_id,
            search: '',
            currentPageListItems: 1,
            loadingItems: false,
            limitItems: 20,
        };

        this.loadItems = this.loadItems.bind(this);
        this.setTema = this.setTema.bind(this);
        this.setCurrentPageListItems = this.setCurrentPageListItems.bind(this);
        this.handleSearch = this.handleSearch.bind(this);
    }

    componentDidMount() {
        this.loadItems();
    }

    setTema(tema) {
        this.setState({
            tema: tema,
            items: [],
            indicadores: [],
            abrangencias: [],
            serieMarked: null,
            abrangencia: null,
            regions: [],
            periodos: [],
            from: null,
            to: null
        }, function () {
            this.loadItems();
        });
    }

    setCurrentPageListItems(page) {
        this.setState({ currentPageListItems: page }, function () {
            this.loadItems();
        });
    }

    handleSearch(e) {
        e.preventDefault();
        this.setState({ search: e.target.value }, function () {
            this.loadItems();
        });
    }


    loadItems(){
        //console.log(this.state);
        //console.log(this.state.currentPageListItems);
        let emptyItems = {data: []};
        this.setState({items: emptyItems, loadingItems: true});
        $.ajax({
            method:'POST',
            url: "list-consultas",
            data:{
                parameters:{
                    tipo: tipo,
                    tema_id: this.state.tema,
                    search: this.state.search,
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

    render(){

        console.log('ITEMS', this.state.items);
        let items = this.state.items;
        if (!this.state.items.data) {
            items = { data: [] };
        }

        return (
            <div className="container">
                <h1>{this.props.title}</h1>
                <div dangerouslySetInnerHTML={{__html: this.props.description}} />
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

                    </div>
                    <div className="col-md-9">
                        <br className="hidden-md hidden-lg"/>
                        <input className='form-control' onChange={this.handleSearch} type="text" placeholder={this.props.lang_search_name}/>
                        <br/>
                        <div className="text-center" style={{display: this.state.loadingItems ? '' : 'none'}}><i className="fa fa-spin fa-spinner fa-3x"/></div>
                        <div style={{display: items.data.length > 0 || this.state.loadingItems ? 'none' : ''}}><h4>{this.props.lang_no_results}</h4></div>
                        <div style={{display: items.data.length > 0 ? '' : 'none'}}>
                            <List
                                items={items}
                                head={[this.props.lang_series, '']}
                                showId='0'
                                setCurrentPageListItems = {this.setCurrentPageListItems}
                                currentPage = {this.state.currentPageListItems}
                                perPage='20'
                                select={this.selectSerie}
                                abrangencias={this.state.optionsAbrangencia}
                                urlDetailItem={'consulta'}
                            />
                        </div>
                    </div>
                </div>






            </div>
        )
    }
}

ReactDOM.render(
    <PageFilters
        tipo={tipo}
        tema_id={tema_id}
        title={title}
        description={description}
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
    document.getElementById('consultas')
);

