class List extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            items: props.items ? props.items : { data: [] },
            head: props.head ? props.head : [],
            showId: props.showId ? props.showId : 1,
            perPage: props.perPage ? props.perPage : 20,
            currentPage: props.currentPage ? props.currentPage : 1,
            urlDetailItem: props.urlDetailItem ? props.urlDetailItem : '#'
        };

        this.select = this.select.bind(this);
        this.getAbrangencia = this.getAbrangencia.bind(this);
        this.setCurrentPage = this.setCurrentPage.bind(this);
    }

    componentDidMount() {}

    componentWillReceiveProps(props) {
        if (this.state.items != props.items || this.state.currentPage != props.currentPage) {
            this.setState({ items: props.items, currentPage: props.currentPage });
        }
    }

    setCurrentPage(page) {
        this.setState({ currentPage: page }, function () {
            this.props.setCurrentPageListItems(page);
        });
    }

    select(id, all) {
        this.props.select(id, all);
    }

    getAbrangencia(codAbrangencia) {
        let abrangencia = null;
        this.props.abrangencias.find(function (item) {
            if (item.id === codAbrangencia) {
                abrangencia = item.title;
            }
        });

        return abrangencia;
    }

    render() {

        let head = null;
        let rows = null;

        let random = Math.floor(Math.random() * 99999 + 1);

        //console.log(this.state.items);

        head = this.state.head.map(function (item, index) {
            return React.createElement(
                'th',
                { key: 'head' + index },
                item
            );
        });
        if (this.state.items.data) {
            //this.state.items.data
            rows = this.state.items.data.map(function (item, index) {
                //this.state.items.data

                let columnsNames = Object.getOwnPropertyNames(item);

                let buttons = [];

                //buttons[0] = <td>&nbsp;</td>;
                buttons[0] = React.createElement(
                    'td',
                    { key: 'btn-pontos' + index, className: 'text-right' },
                    React.createElement(
                        'a',
                        { className: 'btn btn-success', href: "pontos/" + item.id, title: 'Pontos', target: '_blank' },
                        React.createElement('i', { className: 'fa fa-map-marker', style: { fontSize: '1.5em' } })
                    )
                );
                buttons[1] = React.createElement(
                    'td',
                    { key: 'btn-todos-os-territorios' + index, className: 'text-right' },
                    React.createElement(
                        'a',
                        { className: 'btn btn-success', href: this.state.urlDetailItem + "/" + item.id, title: 'Territ\xF3rios', target: '_blank' },
                        React.createElement('i', { className: 'fa fa-arrow-circle-right', style: { fontSize: '1.5em' } })
                    )
                );
                if (item.tipo_dados == 0 || !item.tipo_dados) {
                    buttons[0] = React.createElement(
                        'td',
                        null,
                        '\xA0'
                    );
                }
                if (item.tipo_dados == 1) {
                    buttons[1] = React.createElement(
                        'td',
                        null,
                        '\xA0'
                    );
                }

                let columns = columnsNames.map(function (col, i) {
                    if (this.state.showId == 0 && col == 'id') {
                        return;
                    }

                    let content = item[col];

                    if (col === 'min' || col === 'max') {
                        content = content.substr(0, 4);
                    }

                    if (col === 'tipo_regiao') {
                        content = this.getAbrangencia(content);
                    }

                    return React.createElement(
                        'td',
                        { key: 'colum-serie-name' + i + random },
                        content
                    );
                }.bind(this));

                buttons.find(function (btn) {
                    columns.push(btn);
                });

                return React.createElement(
                    'tr',
                    { key: 'item-serie' + index + random },
                    columns
                );
            }.bind(this));
        }

        return React.createElement(
            'div',
            null,
            React.createElement(
                'table',
                { className: 'table' },
                React.createElement(
                    'thead',
                    null,
                    React.createElement(
                        'tr',
                        null,
                        head
                    )
                ),
                React.createElement(
                    'tbody',
                    null,
                    rows
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