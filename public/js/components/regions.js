class Regions extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
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
            });
        }
    }

    minMaxValue(data) {
        console.log(data);
        let sort = data.sort(function (a, b) {
            if (parseFloat(a.total) < parseFloat(b.total)) {
                return -1;
            }
            if (parseFloat(a.total) > parseFloat(b.total)) {
                return 1;
            }
            return 0;
        });
        this.setState({ minValue: sort[0], maxValue: sort[sort.length - 1] });
    }

    calcMaxUp() {}

    render() {
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
                    '\xC9 a regi\xE3o com menor \xEDndice'
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
            )
        );
    }
}