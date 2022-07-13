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
        this.modalDownload = this.modalDownload.bind(this);
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

    modalDownload(id) {
        this.props.modalDownload(id);
    }

    render() {

        let head = null;
        let rows = null;

        //let random = Math.floor((Math.random() * 99999) + 1);

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
                //console.log(item);

                let columnsNames = Object.getOwnPropertyNames(item);

                let buttons = [];

                //buttons[0] = (<td key={'btn-graficos'+index} className="text-right"><a className='btn btn-success' href={"consulta/"+item.id} title="Gráficos" target="_blank"><i className="fa fa-bar-chart" style={{fontSize: '1.5em'}}/></a></td>);
                //buttons[1] = (<td key={'btn-pontos'+index} className="text-right"><a className='btn btn-success' href={"pontos/"+item.id} title="Pontos" target="_blank"><i className="fa fa-map-marker" style={{fontSize: '1.5em'}}/></a></td>);
                //buttons[2] = (<td key={'btn-todos-os-territorios'+index} className="text-right"><a className='btn btn-success' href={this.state.urlDetailItem+"/"+item.id} title="Territórios" target="_blank"><i className="fa fa-globe" style={{fontSize: '1.5em'}}/></a></td>);
                //buttons[3] = (<td key={'btn-download'+index} className="text-right"><a className='btn btn-success' onClick={() => this.modalDownload(item.id)} title="Download" target="_blank"><i className="fa fa-cloud-download" style={{fontSize: '1.5em'}}/></a></td>);

                buttons[0] = React.createElement(
                    'td',
                    { key: 'btn-graficos' + index, className: 'text-right' },
                    React.createElement(
                        'a',
                        { href: "consulta/" + item.id, title: 'Gr\xE1ficos', target: '_blank' },
                        React.createElement('i', { className: 'fa fa-bar-chart', style: { fontSize: '1.5em' } })
                    )
                );
                buttons[1] = React.createElement(
                    'td',
                    { key: 'btn-pontos' + index, className: 'text-right' },
                    React.createElement(
                        'a',
                        { href: "pontos/" + item.id, title: 'Pontos no Mapa', target: '_blank' },
                        React.createElement('i', { className: 'fa fa-map-marker', style: { fontSize: '1.5em' } })
                    )
                );
                buttons[2] = React.createElement(
                    'td',
                    { key: 'btn-todos-os-territorios' + index, className: 'text-right' },
                    React.createElement(
                        'a',
                        { href: this.state.urlDetailItem + "/" + item.id, title: 'Territ\xF3rios', target: '_blank' },
                        React.createElement('i', { className: 'fa fa-line-chart', style: { fontSize: '1.5em' } })
                    )
                );
                //buttons[3] = (<td key={'btn-download'+index} className="text-right"><a style={{cursor: 'pointer'}} onClick={() => this.modalDownload(item.id)} title="Download" target="_blank"><i className="fa fa-file-excel-o" style={{fontSize: '1.5em'}}/></a></td>);
                buttons[3] = React.createElement(
                    'td',
                    { key: 'btn-download' + index, className: 'text-right' },
                    React.createElement(
                        'a',
                        { style: { cursor: 'pointer', display: item.downloads ? '' : 'none' }, onClick: () => this.modalDownload(item.id), title: 'Download', target: '_blank' },
                        React.createElement('i', { className: 'fa fa-file-excel-o', style: { fontSize: '1.5em' } })
                    )
                );
                buttons[4] = React.createElement(
                    'td',
                    { key: 'btn-metadados' + index, className: 'text-right' },
                    React.createElement(
                        'a',
                        { href: "arquivos/metadados/" + item.arquivo_metadados, download: item.arquivo_metadados, style: { cursor: 'pointer', display: item.downloads ? '' : 'none' }, title: 'Download Metadados', target: '_blank' },
                        React.createElement('i', { className: 'fa fa-file-text-o', style: { fontSize: '1.5em' } })
                    )
                );

                console.log(item.arquivo_metadados);
                if (item.arquivo_metadados === '' || item.arquivo_metadados === null) {
                    buttons[4] = React.createElement(
                        'td',
                        null,
                        '\xA0'
                    );
                    console.log(item.descricao);
                    if (item.descricao !== '' && item.descricao !== null) {
                        buttons[4] = React.createElement(
                            'td',
                            { className: 'text-right' },
                            React.createElement(
                                'a',
                                {
                                    style: { cursor: 'pointer' },
                                    title: 'Download Metadados',
                                    onClick: () => downloadTextToFile('metadados-serie-' + item.id, removeHTML(item.descricao))
                                },
                                React.createElement('i', { className: 'fa fa-file-text-o', style: { fontSize: '1.5em' } })
                            )
                        );
                    }
                }

                //coloca vazio os tds que não satisfazerem a condição
                //territorios
                if (item.tipo_dados == 0 || !item.tipo_dados) {
                    buttons[0] = React.createElement(
                        'td',
                        { key: 'td_btn0_' + index },
                        '\xA0'
                    );
                    buttons[1] = React.createElement(
                        'td',
                        { key: 'td_btn1_' + index },
                        '\xA0'
                    );
                }
                //pontos
                if (item.tipo_dados == 1) {
                    buttons[0] = React.createElement(
                        'td',
                        { key: 'td_btn0_' + index },
                        '\xA0'
                    );
                    buttons[2] = React.createElement(
                        'td',
                        { key: 'td_btn1_' + index },
                        '\xA0'
                    );
                }
                //territorios e pontos
                if (item.tipo_dados == 2) {
                    buttons[0] = React.createElement(
                        'td',
                        { key: 'td_btn0_' + index },
                        '\xA0'
                    );
                }
                //arquivo
                if (item.tipo_dados == 3) {
                    buttons[1] = React.createElement(
                        'td',
                        { key: 'td_btn0_' + index },
                        '\xA0'
                    );
                    buttons[2] = React.createElement(
                        'td',
                        { key: 'td_btn1_' + index },
                        '\xA0'
                    );
                }
                //territorios e arquivo
                if (item.tipo_dados == 4) {
                    buttons[1] = React.createElement(
                        'td',
                        { key: 'td_btn1_' + index },
                        '\xA0'
                    );
                }
                //pontos e arquivo
                if (item.tipo_dados == 5) {
                    buttons[2] = React.createElement(
                        'td',
                        { key: 'td_btn0_' + index },
                        '\xA0'
                    );
                }

                let columns = columnsNames.map(function (col, i) {
                    if (col === 'arquivo_metadados' || col === 'descricao') {
                        return;
                    }

                    if (this.state.showId == 0 && col == 'id') {
                        return;
                    }

                    if (col == 'tipo_dados') {
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
                        { key: 'colum-serie-name-' + i + '-'.index },
                        content
                    );
                }.bind(this));

                buttons.find(function (btn) {
                    columns.push(btn);
                });

                return React.createElement(
                    'tr',
                    { key: 'item-serie-' + index },
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