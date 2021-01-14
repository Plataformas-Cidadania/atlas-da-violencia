class ListItems extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            type: props.type,
            items: props.items,
            filters: this.props.filters,
            types: [],
            typesAccident: [],
            genders: [],
            currentPage: 1,
            perPage: props.perPage ? props.perPage : 20
        };

        //this.loadArrays = this.loadArrays.bind(this);
        this.setCurrentPage = this.setCurrentPage.bind(this);
    }

    componentDidMount() {
        //this.loadArrays();
    }

    componentWillReceiveProps(props) {
        if (props.items != this.state.items) {
            this.setState({ items: props.items });
        }
        /*if(props.tipo != this.state.type){
            this.setState({type: props.type});
        }*/
        if (props.filters != this.state.filters) {
            this.setState({ filters: props.filters });
        }
    }

    setCurrentPage(page) {
        this.setState({ currentPage: page }, function () {
            this.props.setCurrentPageListItems(page);
        });
    }

    /*loadArrays(){
        $.ajax({
            method:'POST',
            url: "arrays-transito",
            data:{
             },
            cache: false,
            success: function(data) {
                //console.log('values-for-types', data);
                this.setState({types: data.types, typesAccident: data.typesAccident, genders: data.genders});
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }*/

    render() {

        let head = null;
        let items = null;

        //console.log('ITEMS ########', this.state.items);

        let colsFilters = this.state.filters.map(function (item, index) {
            //console.log(item);
            return React.createElement(
                'th',
                { key: 'col-filter-' + index },
                item.titulo
            );
        });

        if (this.state.type == 1) {
            head = React.createElement(
                'tr',
                null,
                React.createElement(
                    'th',
                    null,
                    'Local'
                ),
                colsFilters,
                React.createElement(
                    'th',
                    null,
                    'Data'
                ),
                React.createElement(
                    'th',
                    null,
                    'Hora'
                )
            );
            if (this.state.items.data != undefined) {
                items = this.state.items.data.map(function (item) {

                    //console.log(item);

                    let tdsFilters = this.state.filters.map(function (filter, index) {
                        return React.createElement(
                            'td',
                            { key: 'value-filter-' + index },
                            item[filter.slug]
                        );
                    });

                    /*let type = null;
                    this.state.types.find(function(it){
                        if(it.id==item.tipo){
                            type = it.title;
                        }
                    });
                    let typeAccident = null;
                    this.state.typesAccident.find(function(it){
                        if(it.id==item.tipo_acidente){
                            typeAccident = it.title;
                        }
                    });
                    let gender = null;
                    this.state.genders.find(function(it){
                        if(it.id==item.sexo){
                            gender = it.title;
                        }
                    });*/

                    return React.createElement(
                        'tr',
                        { key: "item_" + item.id },
                        React.createElement(
                            'td',
                            null,
                            item.endereco
                        ),
                        tdsFilters,
                        React.createElement(
                            'td',
                            null,
                            React.createElement('i', { className: 'fa fa-calendar' }),
                            ' ',
                            item.data
                        ),
                        React.createElement(
                            'td',
                            null,
                            React.createElement('i', { className: 'fa fa-clock-o' }),
                            ' ',
                            item.hora
                        )
                    );
                }.bind(this));
            }
        }

        //console.log('ITEMS >>>>>>>>>>>>', items);

        if (this.state.type == 2) {
            head = React.createElement(
                'tr',
                null,
                React.createElement(
                    'th',
                    null,
                    'Territ\xF3rio'
                ),
                React.createElement(
                    'th',
                    null,
                    'Total'
                )
            );
            items = this.state.items.map(function (item) {
                return React.createElement(
                    'tr',
                    null,
                    React.createElement(
                        'td',
                        null,
                        item.territorio
                    ),
                    React.createElement(
                        'td',
                        null,
                        item.total
                    )
                );
            });
        }

        let prev = 'disabled';
        let next = '';

        return React.createElement(
            'div',
            null,
            React.createElement(
                'h2',
                null,
                'Dados'
            ),
            React.createElement('div', { className: 'line-title-sm bg-pri' }),
            React.createElement('hr', { className: 'line-hr-sm' }),
            React.createElement('br', null),
            React.createElement(
                'table',
                { className: 'table' },
                React.createElement(
                    'thead',
                    null,
                    head
                ),
                React.createElement(
                    'tbody',
                    null,
                    items
                )
            ),
            React.createElement(Pagination, {
                currentPage: this.state.currentPage,
                setCurrentPage: this.setCurrentPage,
                total: this.state.items.total,
                perPage: this.state.perPage,
                lastPage: this.state.items.last_page
            })
        );
    }
}