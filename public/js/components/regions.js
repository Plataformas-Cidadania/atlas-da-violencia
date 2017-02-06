class Regions extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: this.props.id,
            loading: false,
            data: {},
            min: 0,
            max: 0,
            minValue: 0,
            maxValue: 0,
            maxUp: 0,
            maxDown: 0,
            styleNumber: { fontSize: '50px', fontWeight: 'bold' }
        };

        this.minMaxValue = this.minMaxValue.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.min != props.data.min || this.state.max != props.data.max) {
            this.setState({ min: props.data.min, max: props.data.max, data: props.data.values }, function () {
                this.minMaxValue(this.state.data);
                this.loadData();
            });
        }
    }

    loadData() {
        this.setState({ loading: true });
        $.ajax({
            method: 'GET',
            url: "valores-inicial-final-regiao/" + this.state.id + "/" + this.state.min + "/" + this.state.max,
            cache: false,
            success: function (data) {
                console.log('region.js, loaddata', data);
                this.setState({ data: data, loading: false }, function () {
                    this.calcMaxUpDown();
                });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    minMaxValue(data) {
        //console.log(data);
        let sort = data.sort(function (a, b) {
            if (parseFloat(a.total) < parseFloat(b.total)) {
                return -1;
            }
            if (parseFloat(a.total) > parseFloat(b.total)) {
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

            let start = parseFloat(this.state.data[i].valor);
            let end = parseFloat(this.state.data[++i].valor);

            let variacao = end * 100 / start - 100;
            regions[cont] = {
                uf: this.state.data[i].uf,
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

        this.setState({
            maxDown: regions[0],
            maxUp: regions[last]
        });
    }

    render() {

        let iconGreenUp = React.createElement('div', { className: 'icons-arrows icon-green-up' });
        let iconGreenDown = React.createElement('div', { className: 'icons-arrows icon-green-down' });
        let iconRedUp = React.createElement('div', { className: 'icons-arrows icon-red-up' });
        let iconRedDown = React.createElement('div', { className: 'icons-arrows icon-red-down' });

        //console.log(this.state.maxDown);

        let down = React.createElement(
            'p',
            null,
            'Maior queda'
        );
        let multiplicadorDown = -1;
        let iconDown = iconRedDown;
        if (this.state.maxDown.variacao >= 0) {
            down = React.createElement(
                'p',
                null,
                'Menor crescimento'
            );
            multiplicadorDown = 1;
            iconDown = iconGreenUp;
        }

        let up = React.createElement(
            'p',
            null,
            'Maior crescimento'
        );
        let multiplicadorUp = 1;
        let iconUp = iconGreenUp;
        if (this.state.maxUp.variacao < 0) {
            up = React.createElement(
                'p',
                null,
                'Menor queda'
            );
            multiplicadorUp = 1;
            iconUp = iconRedDown;
        }

        return React.createElement(
            'div',
            { className: 'row' },
            React.createElement(
                'div',
                { className: 'col-md-3 col-lg-3 text-center' },
                React.createElement(
                    'h4',
                    null,
                    this.state.minValue.uf,
                    ' - ',
                    this.state.minValue.nome
                ),
                React.createElement('div', { className: 'line_title bg-pri' }),
                React.createElement('br', null),
                React.createElement('img', { src: "img/maps/png/" + this.state.minValue.uf + ".png", alt: '' }),
                React.createElement('br', null),
                React.createElement(
                    'p',
                    null,
                    '\xC9 a regi\xE3o com menor \xEDndice'
                ),
                React.createElement('br', null),
                React.createElement(
                    'p',
                    { style: this.state.styleNumber },
                    this.state.minValue.total
                )
            ),
            React.createElement(
                'div',
                { className: 'col-md-3 col-lg-3 text-center' },
                React.createElement(
                    'h4',
                    null,
                    this.state.maxValue.uf,
                    ' - ',
                    this.state.maxValue.nome
                ),
                React.createElement('div', { className: 'line_title bg-pri' }),
                React.createElement('br', null),
                React.createElement('img', { src: "img/maps/png/" + this.state.maxValue.uf + ".png", alt: '' }),
                React.createElement('br', null),
                React.createElement(
                    'p',
                    null,
                    '\xC9 a regi\xE3o com maior \xEDndice'
                ),
                React.createElement('br', null),
                React.createElement(
                    'p',
                    { style: this.state.styleNumber },
                    this.state.maxValue.total
                )
            ),
            React.createElement(
                'div',
                { className: 'col-md-6 col-lg-6 text-center text-center', style: { display: this.state.loading ? 'block' : 'none' } },
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
                { className: 'col-md-3 col-lg-3 text-center', style: { display: this.state.loading ? 'none' : 'block' } },
                React.createElement(
                    'h4',
                    null,
                    this.state.maxDown.uf,
                    ' - ',
                    this.state.maxDown.nome
                ),
                React.createElement('div', { className: 'line_title bg-pri' }),
                React.createElement('br', null),
                React.createElement('img', { src: "img/maps/png/" + this.state.maxDown.uf + ".png", alt: '' }),
                React.createElement('br', null),
                down,
                ' ',
                iconDown,
                React.createElement('br', null),
                React.createElement(
                    'p',
                    { style: this.state.styleNumber },
                    numeral(this.state.maxDown.variacao * multiplicadorDown).format('0,0.00'),
                    '%'
                )
            ),
            React.createElement(
                'div',
                { className: 'col-md-3 col-lg-3 text-center', style: { display: this.state.loading ? 'none' : 'block' } },
                React.createElement(
                    'h4',
                    null,
                    this.state.maxUp.uf,
                    ' - ',
                    this.state.maxUp.nome
                ),
                React.createElement('div', { className: 'line_title bg-pri' }),
                React.createElement('br', null),
                React.createElement('img', { src: "img/maps/png/" + this.state.maxUp.uf + ".png", alt: '' }),
                React.createElement('br', null),
                up,
                ' ',
                iconUp,
                React.createElement('br', null),
                React.createElement(
                    'p',
                    { style: this.state.styleNumber },
                    numeral(this.state.maxUp.variacao * multiplicadorUp).format('0,0.00'),
                    '%'
                )
            )
        );
    }
}