class ListValoresSeries extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            valores: [],
            min: this.props.min,
            max: this.props.max,
            loading: true,
            columnsTd: null,
            dataTable: null,
            abrangencia: null
        };
        //this.loadData = this.loadData.bind(this);
        this.generateTable = this.generateTable.bind(this);
    }

    componentDidMount() {
        //this.loadData();
        this.generateTable();
    }

    componentWillReceiveProps(props) {
        /*this.setState({min: props.min, max: props.max}, function(){
         this.loadData();
         });*/

        //console.log(props.data);
        if (this.state.abrangencia != props.abrangencia) {
            this.setState({ columnsTd: React.createElement(
                    'td',
                    null,
                    '\xA0'
                ), dataTable: React.createElement(
                    'tr',
                    null,
                    React.createElement(
                        'td',
                        null,
                        '\xA0'
                    )
                ) });
        }

        if (this.state.valores != props.data) {
            this.setState({ valores: props.data, loading: true }, function () {
                this.generateTable();
            });
        }
    }

    /*loadData(){
        $.ajax({
            method:'GET',
            url: "periodo/"+this.state.id+"/"+this.state.min+"/"+this.state.max+"/"+this.props.regions+"/"+this.props.abrangencia,
            cache: false,
            success: function(data) {
                console.log('listValoresSeries', data);
                 let valores = [];
                for(let i in data){
                    let region = {};
                    region[i] = data[i];
                    valores.push(region);
                }
                 this.setState({valores: valores});
            }.bind(this),
            error: function(xhr, status, err) {
              console.log('erro', err);
            }.bind(this)
        });
    }*/

    getColors() {

        let colors = [];
        for (let i in colors2) {
            colors.push(convertHex(colors2[i], 100));
        }
        return colors;
    }

    generateTable() {

        let labels = [];
        let datasets = [];
        let cont = 0;
        let contLabel = 0;
        let contColor = 0;
        let data = this.state.valores;
        let currentPer = null;

        //////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////
        let columns = [];
        columns.push(null);
        columns.push(this.props.nomeAbrangencia);
        let values = [];
        let colors = this.getColors();

        for (let region in data) {

            if (contColor > colors.length - 1) {
                contColor = 0;
            }

            let register = {};
            register['legend'] = colors[contColor];
            register['region'] = region;

            for (let periodo in data[region]) {
                //console.log('##########', periodo);
                //console.log('>>>>>>>>', columns.indexOf(periodo));
                if (columns.indexOf(periodo) == -1) {
                    columns.push(periodo);
                }
                register[periodo] = data[region][periodo];
            }

            contColor++;

            datasets.push(register);
        }

        //console.log('COLUMNS', columns);

        for (let register in datasets) {
            //console.log('--------------------------------');
            //console.log(datasets[register]);
            for (let column in columns) {
                //console.log('###', columns[column]);
                //console.log('A COLUNA EXISTE?', datasets[register].hasOwnProperty(columns[column]));
                if (!datasets[register].hasOwnProperty(columns[column])) {
                    //console.log('ADICIONAR COLUNA', columns[column], 'COM VALOR NULL');
                    datasets[register][columns[column]] = null;
                }
            }
            //console.log('DATASETS[REGISTER] DEPOIS', datasets[register]);
        }

        let columnsTd = columns.map(function (column, index) {

            if (index >= 2) {
                column = formatPeriodicidade(column, this.props.periodicidade);
            }

            return React.createElement(
                'th',
                { key: "col_list_" + index, style: { textAlign: 'right', fontWeight: 'bold' } },
                column
            );
        }.bind(this));

        let dataTable = datasets.map(function (item, index) {

            let valores = [];

            for (let i in columns) {

                let column = columns[i];

                let valor = item[column];

                //testa se é numero
                let regra = /^[0-9]+$/;
                if (item[column]) {
                    if (item[column].match(regra)) {
                        valor = formatNumber(item[column], this.props.decimais, ',', '.');
                    }
                }

                let classValor = "text-right";
                if (item[column] == 0) {
                    valor = '-';
                    classValor = "text-center";
                }

                let td = React.createElement(
                    'td',
                    { key: "valor_tabela_" + index + '_' + column, className: classValor },
                    valor
                );

                if (i == 0) {
                    td = React.createElement(
                        'th',
                        { key: "valor_tabela_" + index + '_' + column, width: '10px' },
                        React.createElement(
                            'i',
                            { className: 'fa fa-square', style: { color: item['legend'] } },
                            ' '
                        )
                    );
                }

                if (i == 1) {
                    td = React.createElement(
                        'th',
                        { key: "valor_tabela_" + index + '_' + column },
                        item['region']
                    );
                }

                valores.push(td);
            }

            return React.createElement(
                'tr',
                { key: "col_valores_" + index },
                valores
            );
        }.bind(this));

        this.setState({ columnsTd: columnsTd, dataTable: dataTable, loading: false });
    }

    render() {
        if (!this.state.valores) {
            return React.createElement(
                'h3',
                null,
                'Sem Resultados'
            );
        }

        console.log(this.state.columnsTd);

        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { style: { display: this.state.loading || !this.state.dataTable ? '' : 'none' }, className: 'text-center' },
                React.createElement('i', { className: 'fa fa-spin fa-spinner fa-4x' })
            ),
            React.createElement(
                'div',
                { style: { display: this.state.loading || !this.state.dataTable ? 'none' : '' } },
                React.createElement(
                    'div',
                    { className: 'Container' },
                    React.createElement(
                        'div',
                        { className: 'Content', style: { overflowY: 'auto', maxHeight: '600px' } },
                        React.createElement(
                            'table',
                            { className: 'table table-striped table-bordered', id: 'listValoresSeries' },
                            React.createElement(
                                'thead',
                                null,
                                React.createElement(
                                    'tr',
                                    null,
                                    this.state.columnsTd
                                )
                            ),
                            React.createElement(
                                'tbody',
                                null,
                                this.state.dataTable
                            )
                        )
                    )
                ),
                React.createElement('br', null),
                React.createElement(
                    'div',
                    { style: { float: 'right', marginLeft: '5px' } },
                    React.createElement(Download, { btnDownload: 'downloadListValoresSeries', divDownload: 'listValoresSeries', arquivo: 'tabela.png' })
                ),
                React.createElement(
                    'div',
                    { style: { float: 'right', marginLeft: '5px' } },
                    React.createElement(Print, { divPrint: 'listValoresSeries', imgPrint: 'imgPrintList' })
                ),
                React.createElement('div', { style: { clear: 'both' } })
            )
        );
    }
}