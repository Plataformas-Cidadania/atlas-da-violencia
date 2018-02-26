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
            typeIcons: ['outros.png', 'automovel.png', 'motocicleta.png', 'pedestre.png', 'onibus.png', 'caminhao.png', 'bicicleta.png', 'outros.png'],
            iconsType: [],
            filter: 0,
            year: null,
            month: null,
            start: null,
            end: null,
            months: {'Jan': '01', 'Fev': '02', 'Mar':'03', 'Abr':'04', 'Mai':'05', 'Jun':'06', 'Jul':'07', 'Ago':'08', 'Set':'09', 'Out':'10', 'Nov':'11', 'Dez':'12'},
            valuesForTypes: [],
            selectedTypes: [],
            valuesForGender: [],
            valuesForRegions: [],
            valuesForUfs: [],
            values: [],
            currentPageListItems: 1,

        };

        this.checkType = this.checkType.bind(this);
        this.checkTypeAccident = this.checkTypeAccident.bind(this);
        this.checkGender = this.checkGender.bind(this);
        this.checkRegion = this.checkRegion.bind(this);
        this.checkYear = this.checkYear.bind(this);
        this.checkMonth = this.checkMonth.bind(this);
        this.actionFilter = this.actionFilter.bind(this);
        this.mountPer = this.mountPer.bind(this);
        this.lastDayMonth = this.lastDayMonth.bind(this);
        this.loadValuesForTypes = this.loadValuesForTypes.bind(this);
        this.loadValuesForGender = this.loadValuesForGender.bind(this);
        this.iconsType = this.iconsType.bind(this);
        this.setCurrentPageListItems = this.setCurrentPageListItems.bind(this);
        this.loadValues = this.loadValues.bind(this);
        this.loadValuesForRegions = this.loadValuesForRegions.bind(this);
        this.loadValuesForUfs = this.loadValuesForUfs.bind(this);
    }


    mountPer(){
        let start = this.state.year+'-'+this.state.months[this.state.month]+'-01';
        let end = this.state.year+'-'+this.state.months[this.state.month]+'-'+this.lastDayMonth(this.state.month);
        this.setState({start: start, end: end}, function(){
            //this.loadMap();
            //this.loadDataTotalPorTerritorio();
            this.loadValuesForTypes();
            this.loadValuesForGender();
            this.loadValuesForRegions();
            this.loadValuesForUfs();
            this.loadValues();
        });
    }

    lastDayMonth(month){
        let arrayLastDay = {'01':'31','02':'29','03':'31','04':'30','05':'31','06':'30','07':'31','08':'31','09':'30','10':'31','11':'30','12':'31'};
        let months = this.state.months;
        //console.log(month);
        return arrayLastDay[months[month]];
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
        this.setState({year: year}, function(){
            if(this.state.year != null && this.state.month != null){
                this.mountPer();
            }
        });
    }

    checkMonth(month){
        this.setState({month: month}, function(){
            if(this.state.year != null && this.state.month != null){
                this.mountPer();
            }
        });
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
            this.loadValuesForTypes();
            this.loadValuesForGender();
            this.loadValuesForRegions();
            this.loadValuesForUfs();
            this.loadValues();
        });
    }

    iconsType(icons){
        //console.log(icons);
        this.setState({iconsType: icons});
    }

    loadValuesForTypes(){


        if(!this.state.start || !this.state.end){
            return;
        }

        $.ajax({
            method:'POST',
            url: "values-for-types",
            data:{
                id: this.props.id,
                start: this.state.start,
                end: this.state.end,
                typesAccident: this.state.idTypesAccident,
                genders: this.state.idGender,
                tipoTerritorioSelecionado: this.state.tipoTerritorioSelecionado, // tipo de territorio selecionado
                codigoTerritorioSelecionado: this.state.codigoTerritorioSelecionado, //codigo do territorio, que pode ser codigo do país, regiao, uf, etc...
            },
            cache: false,
            success: function(data) {
                //console.log('values-for-types', data);
                this.setState({valuesForTypes: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    loadValuesForGender(){

        if(!this.state.start || !this.state.end){
            return;
        }

        $.ajax({
            method:'POST',
            url: "values-for-gender",
            data:{
                id: this.props.id,
                start: this.state.start,
                end: this.state.end,
                types: this.state.idTypes,
                typesAccident: this.state.idTypesAccident,
                tipoTerritorioSelecionado: this.state.tipoTerritorioSelecionado, // tipo de territorio selecionado
                codigoTerritorioSelecionado: this.state.codigoTerritorioSelecionado, //codigo do territorio, que pode ser codigo do país, regiao, uf, etc...
            },
            cache: false,
            success: function(data) {
                //console.log('values-for-gender', data);
                this.setState({valuesForGender: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    loadValuesForRegions(){

        if(!this.state.start || !this.state.end){
            return;
        }

        $.ajax({
            method:'POST',
            url: "values-for-regions",
            data:{
                id: this.props.id,
                start: this.state.start,
                end: this.state.end,
                types: this.state.idTypes,
                typesAccident: this.state.idTypesAccident,
                genders: this.state.idGender,
                tipoTerritorioSelecionado: 2,
                codigoTerritorioSelecionado: [11,12,13,14,15],
                tipoTerritorioAgrupamento: 2,
            },
            cache: false,
            success: function(data) {
                console.log('values-for-regions', data);
                this.setState({valuesForRegions: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    loadValuesForUfs(){

        if(!this.state.start || !this.state.end){
            return;
        }

        $.ajax({
            method:'POST',
            url: "values-for-regions",
            data:{
                id: this.props.id,
                start: this.state.start,
                end: this.state.end,
                types: this.state.idTypes,
                typesAccident: this.state.idTypesAccident,
                genders: this.state.idGender,
                tipoTerritorioSelecionado: 3,
                codigoTerritorioSelecionado: [11,12,13,14,15,16,17,23,27,29,33,35,53,21,22,24,25,26,28,31,32,41,42,43,50,51,52],
                tipoTerritorioAgrupamento: 3,
            },
            cache: false,
            success: function(data) {
                console.log('values-for-regions', data);
                this.setState({valuesForUfs: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    setCurrentPageListItems(page){
        this.setState({currentPageListItems: page}, function(){
            this.loadValues();
        });
    }

    loadValues(){
        if(!this.state.start || !this.state.end){
            return;
        }

        $.ajax({
            method:'POST',
            url: "pontos-transito-territorio",
            data:{
                start: this.state.start,
                end: this.state.end,
                types: this.state.idTypes,
                typesAccident: this.state.idTypesAccident,
                genders: this.state.idGender,
                tipoTerritorioSelecionado: this.state.tipoTerritorioSelecionado, // tipo de territorio selecionado
                codigoTerritorioSelecionado: this.state.codigoTerritorioSelecionado, //codigo do territorio, que pode ser codigo do país, regiao, uf, etc...
                paginate: true,
                page: this.state.currentPageListItems
            },
            cache: false,
            success: function(data) {
                //console.log('load-values', data);
                this.setState({values: data.valores});
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        })
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
                        iconsType={this.iconsType}
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
                     typeIcons={this.state.typeIcons}
                     filter={this.state.filter}
                     actionFilter={this.actionFilter}
                     start={this.state.start}
                     end={this.state.end}
                     //year={this.state.year}
                     //month={this.state.month}
                />
                <br/><br/><br/><br/>
                <ChartBarHtml5
                    chart='1'
                    type='2'
                    values={this.state.valuesForTypes}
                    valuesSelected={this.state.typesSelected}
                    icons={this.state.iconsType}
                />
                <br/><br/><br/><br/>
                <div className="container">
                    <div className="row" style={{paddingBottom: 0}}>
                        <div className="col-md-6">
                            <ChartGender
                                values={this.state.valuesForGender}
                            />
                        </div>
                        <div className="col-md-6">
                            <ChartBarHtml5
                                chart='2'
                                type='1'
                                height='350px'
                                show="3"
                                values={this.state.valuesForRegions}
                            />
                        </div>
                    </div>
                </div>
                <br/><br/><br/><br/>
                <div className="container">
                    <ChartBarHtml5
                        chart='3'
                        type='1'
                        height='350px'
                        show='2'
                        values={this.state.valuesForUfs}
                    />
                </div>
                <br/><br/><br/><br/>
                <div className="container">
                    <ListItems
                        type="1"
                        items={this.state.values}
                        setCurrentPageListItems = {this.setCurrentPageListItems}
                    />
                </div>
            </div>
        );
    }
}

ReactDOM.render(
    <Page id={serie_id}/>,
    document.getElementById('page')
);



