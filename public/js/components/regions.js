class Regions extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: this.props.id,
            loading: false,
            data: {},
            min: 0,
            max: 0,
            periodo: 0,
            minValue: 0,
            maxValue: 0,
            maxUp: 0,
            maxDown: 0,
            styleNumber: { fontSize: '35px', fontWeight: 'bold' },
            showMaxUpDown: true
        };

        this.minMaxValue = this.minMaxValue.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.periodo != props.data.periodo || this.state.min != props.min || this.state.max != props.max) {
            this.setState({ periodo: props.data.periodo, min: props.min, max: props.max, data: props.data.valores }, function () {
                if (this.state.data) {
                    this.minMaxValue(this.state.data);
                }
                this.loadData();
            });
        }
    }

    loadData() {
        if (this.state.min && this.state.max) {
            this.setState({ loading: true });
            $.ajax({
                method: 'GET',
                url: "valores-inicial-final-regiao/" + this.state.id + "/" + this.state.min + "/" + this.state.max + "/" + this.props.regions + "/" + this.props.abrangencia,
                cache: false,
                success: function (data) {
                    //console.log('### REGIONS.loadData ###', data);
                    this.setState({ data: data, loading: false }, function () {
                        this.calcMaxUpDown();
                    });
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log('erro');
                }.bind(this)
            });
        }
    }

    minMaxValue(data) {
        let sort = data.sort(function (a, b) {
            if (parseFloat(a.valor) < parseFloat(b.valor)) {
                return -1;
            }
            if (parseFloat(a.valor) > parseFloat(b.valor)) {
                return 1;
            }
            return 0;
        });
        this.setState({
            minValue: sort[0],
            maxValue: sort[sort.length - 1]
        });
    }

    calcMaxUpDown() {
        //console.log(this.state.data);
        let regions = [];
        let variacoes = [];
        let length = this.state.data.length;
        let cont = 0;
        for (let i = 0; i < length; i++) {
            //regions[this.state.data[i].uf] = {};
            //regions[this.state.data[i].uf].start = this.state.data[i].valor;
            //regions[this.state.data[i].uf].end = this.state.data[++i].valor;


            let j = i + 1;
            //para o fato de não haver valor em um determinado território
            if (!this.state.data[i] || !this.state.data[j]) {
                this.setState({ showMaxUpDown: false });
                return;
            }
            let start = parseFloat(this.state.data[i].valor);
            let end = parseFloat(this.state.data[++i].valor);

            let variacao = end * 100 / start - 100;
            regions[cont] = {
                sigla: this.state.data[i].sigla,
                nome: this.state.data[i].nome,
                variacao: variacao
            };
            cont++;
        }

        regions = regions.sort(function (a, b) {
            if (a.variacao < b.variacao) {
                return -1;
            }
            if (a.variacao > b.variacao) {
                return 1;
            }
            return 0;
        });

        let last = regions.length - 1;

        //console.log('### REGIONS.calcMaxUpDown ###', regions);

        this.setState({
            maxDown: regions[0],
            maxUp: regions[last],
            showMaxUpDown: true
        });
    }

    render() {

        let iconGreenUp = React.createElement('div', { className: 'icons-arrows icon-green-up' });
        let iconGreenDown = React.createElement('div', { className: 'icons-arrows icon-green-down' });
        let iconRedUp = React.createElement('div', { className: 'icons-arrows icon-red-up' });
        let iconRedDown = React.createElement('div', { className: 'icons-arrows icon-red-down' });

        //console.log('###  REGIONS.RENDER  ###',this.state.maxDown);

        let down = React.createElement(
            'p',
            null,
            this.props.lang_largest_drop
        );
        let multiplicadorDown = -1;
        let iconDown = iconRedDown;
        if (this.state.maxDown.variacao >= 0) {
            down = React.createElement(
                'p',
                null,
                this.props.lang_lower_growth
            );
            multiplicadorDown = 1;
            iconDown = iconGreenUp;
        }

        let up = React.createElement(
            'p',
            null,
            this.props.lang_increased_growth
        );
        let multiplicadorUp = 1;
        let iconUp = iconGreenUp;
        if (this.state.maxUp.variacao < 0) {
            up = React.createElement(
                'p',
                null,
                this.props.lang_lower_fall
            );
            multiplicadorUp = 1;
            iconUp = iconRedDown;
        }

        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { className: 'row', id: 'regions' },
                React.createElement(
                    'div',
                    { className: 'col-xs-6 col-sm-6 col-md-6 col-lg-6' },
                    React.createElement(
                        'div',
                        { className: 'row' },
                        React.createElement(
                            'div',
                            { className: 'col-md-12' },
                            React.createElement(
                                'div',
                                { style: { textAlign: 'center', clear: 'both' } },
                                React.createElement(
                                    'button',
                                    { className: 'btn btn-primary btn-lg bg-pri', style: { border: '0' } },
                                    formatPeriodicidade(this.state.periodo, this.props.periodicidade)
                                ),
                                React.createElement(
                                    'div',
                                    { style: { marginTop: '-19px' } },
                                    React.createElement('i', { className: 'fa fa-sort-down fa-2x', style: { color: '#3498DB' } })
                                )
                            ),
                            React.createElement('br', null)
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: 'row' },
                        React.createElement(
                            'div',
                            { className: 'col-md-6 text-center' },
                            React.createElement(
                                'h4',
                                null,
                                this.props.lang_smallest_index
                            ),
                            React.createElement('div', { className: 'line_title bg-pri' }),
                            React.createElement('br', null),
                            React.createElement('img', { src: "img/maps/png/" + this.state.minValue.sigla + ".png", alt: '' }),
                            React.createElement('br', null),
                            React.createElement(
                                'p',
                                null,
                                this.state.minValue.sigla,
                                ' - ',
                                this.state.minValue.nome
                            ),
                            React.createElement('br', null),
                            React.createElement(
                                'p',
                                { style: this.state.styleNumber },
                                formatNumber(this.state.minValue.valor, this.props.decimais, ',', '.')
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'col-md-6 text-center' },
                            React.createElement(
                                'h4',
                                null,
                                this.props.lang_higher_index
                            ),
                            React.createElement('div', { className: 'line_title bg-pri' }),
                            React.createElement('br', null),
                            React.createElement('img', { src: "img/maps/png/" + this.state.maxValue.sigla + ".png", alt: '' }),
                            React.createElement('br', null),
                            React.createElement(
                                'p',
                                null,
                                this.state.maxValue.sigla,
                                ' - ',
                                this.state.maxValue.nome
                            ),
                            React.createElement('br', null),
                            React.createElement(
                                'p',
                                { style: this.state.styleNumber },
                                formatNumber(this.state.maxValue.valor, this.props.decimais, ',', '.')
                            )
                        )
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'col-xs-6 col-sm-6 col-md-6 col-lg-6' },
                    React.createElement(
                        'div',
                        { className: 'row' },
                        React.createElement(
                            'div',
                            { className: 'col-md-12' },
                            React.createElement(
                                'div',
                                { style: { textAlign: 'center', clear: 'both' } },
                                React.createElement(
                                    'button',
                                    { className: 'btn btn-primary btn-lg bg-pri', style: { border: '0' } },
                                    formatPeriodicidade(this.state.min, this.props.periodicidade),
                                    ' - ',
                                    formatPeriodicidade(this.state.max, this.props.periodicidade)
                                ),
                                React.createElement(
                                    'div',
                                    { style: { marginTop: '-19px' } },
                                    React.createElement('i', { className: 'fa fa-sort-down fa-2x', style: { color: '#3498DB' } })
                                )
                            ),
                            React.createElement('br', null)
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: ' row text-center', style: { display: this.state.showMaxUpDown ? 'none' : 'block' } },
                        React.createElement(
                            'div',
                            { className: 'col-md-12' },
                            React.createElement('br', null),
                            React.createElement('br', null),
                            React.createElement('br', null),
                            React.createElement('br', null),
                            React.createElement(
                                'h4',
                                { style: { padding: '20px' } },
                                'Em fun\xE7\xE3o de n\xE3o existirem alguns dados n\xE3o foi poss\xEDvel calcular a queda e crescimento.'
                            )
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: 'row', style: { display: this.state.showMaxUpDown ? 'block' : 'none' } },
                        React.createElement(
                            'div',
                            { className: 'col-md-12 col-lg-12 text-center text-center', style: { display: this.state.loading ? 'block' : 'none' } },
                            React.createElement('br', null),
                            React.createElement('br', null),
                            React.createElement(
                                'i',
                                { className: 'fa fa-5x fa-spinner fa-spin' },
                                ' '
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'col-md-6 text-center', style: { display: this.state.loading ? 'none' : 'block' } },
                            React.createElement(
                                'h4',
                                null,
                                down
                            ),
                            React.createElement('div', { className: 'line_title bg-pri' }),
                            React.createElement('br', null),
                            React.createElement('img', { src: "img/maps/png/" + this.state.maxDown.sigla + ".png", alt: '' }),
                            React.createElement('br', null),
                            this.state.maxDown.sigla,
                            ' - ',
                            this.state.maxDown.nome,
                            React.createElement('br', null),
                            React.createElement(
                                'div',
                                { className: 'row', style: { paddingTop: '15px' } },
                                React.createElement(
                                    'div',
                                    { className: 'col-md-8 text-right', style: { paddingTop: '15px' } },
                                    React.createElement(
                                        'p',
                                        { style: this.state.styleNumber },
                                        formatNumber(this.state.maxDown.variacao * multiplicadorDown, 2, ',', '.'),
                                        '%'
                                    )
                                ),
                                React.createElement(
                                    'div',
                                    { className: 'col-md-4' },
                                    iconDown
                                )
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'col-md-6 text-center', style: { display: this.state.loading ? 'none' : 'block' } },
                            React.createElement(
                                'h4',
                                null,
                                up
                            ),
                            React.createElement('div', { className: 'line_title bg-pri' }),
                            React.createElement('br', null),
                            React.createElement('img', { src: "img/maps/png/" + this.state.maxUp.sigla + ".png", alt: '' }),
                            React.createElement('br', null),
                            this.state.maxUp.sigla,
                            ' - ',
                            this.state.maxUp.nome,
                            React.createElement(
                                'div',
                                { className: 'row', style: { paddingTop: '15px' } },
                                React.createElement(
                                    'div',
                                    { className: 'col-md-8 text-right', style: { paddingTop: '15px' } },
                                    React.createElement(
                                        'p',
                                        { style: this.state.styleNumber },
                                        formatNumber(this.state.maxUp.variacao * multiplicadorUp, 2, ',', '.'),
                                        '%'
                                    )
                                ),
                                React.createElement(
                                    'div',
                                    { className: 'col-md-4' },
                                    iconUp
                                )
                            )
                        )
                    )
                )
            ),
            React.createElement('br', null),
            React.createElement(
                'div',
                { style: { float: 'right', marginLeft: '5px' } },
                React.createElement(Download, { btnDownload: 'downloadRegions', divDownload: 'regions', arquivo: 'taxas.png' })
            ),
            React.createElement(
                'div',
                { style: { float: 'right', marginLeft: '5px' } },
                React.createElement(Print, { divPrint: 'regions', imgPrint: 'imgRegions' })
            ),
            React.createElement('div', { style: { clear: 'both' } })
        );
    }
}