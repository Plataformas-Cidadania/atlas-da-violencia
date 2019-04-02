class ChartBarHtml5 extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            chart: props.chart,
            type: props.type, //1 - vertical 2 - horizontal
            values: props.values,
            valuesSelected: props.valuesSelected,
            icons: props.icons,
            title: props.title,
            show: props.show ? parseInt(props.show) : 1 //1 - valor, 2 - porcentagem, 3 - valor e porcentagem
        };
    }

    componentWillReceiveProps(props) {
        if (props.values != this.state.values) {
            this.setState({ values: props.values });
        }
        if (props.icons != this.state.icons) {
            this.setState({ icons: props.icons });
        }
    }

    existe(array1, array2) {}

    total(values) {
        let total = 0;

        values.find(function (item) {
            total += parseInt(item.value);
        });

        return total;
    }

    max(values) {
        let max = 0;

        values.find(function (item) {
            max = item.value > max ? item.value : max;
        });

        return max;
    }

    render() {

        //console.log(this.state.values);


        let total = this.total(this.state.values);
        let max = this.max(this.state.values);

        if (this.props.type == 1) {
            let bars = this.state.values.map(function (item, index) {

                let value = this.state.show === 1 || this.state.show === 3 ? item.value : null;
                let percent = this.state.show === 2 || this.state.show === 3 ? formatNumber(item.value * 100 / total, 2, ',', '.') + "%" : null;
                let parenteseAberto = this.state.show === 3 ? '(' : null;
                let parenteseFechado = this.state.show === 3 ? ')' : null;

                return React.createElement(
                    'li',
                    { key: 'itemChartBar' + this.state.chart + "_" + index, style: { height: item.value * 100 / max + '%' } },
                    React.createElement(
                        'span',
                        { style: { height: item.value * 100 / max + '%', maxWidth: '70px' }, className: 'bg-pri' },
                        React.createElement(
                            'strong',
                            { className: 'hidden-xs' },
                            value,
                            ' ',
                            parenteseAberto,
                            percent,
                            parenteseFechado
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: 'hidden-xs hidden-sm' },
                        item.type
                    )
                );
            }.bind(this));

            return React.createElement(
                'div',
                null,
                React.createElement(
                    'h2',
                    null,
                    this.state.title
                ),
                React.createElement('div', { className: 'line-title-sm bg-pri' }),
                React.createElement('hr', { className: 'line-hr-sm' }),
                React.createElement('br', null),
                React.createElement('br', null),
                React.createElement('br', null),
                React.createElement(
                    'ul',
                    { className: 'chart', style: { height: this.props.height } },
                    bars
                )
            );
        }

        if (this.props.type == 2) {
            let bars = this.state.values.map(function (item, index) {

                return React.createElement(
                    'div',
                    { className: 'row', key: 'itemChartBar' + this.state.chart + "_" + index },
                    React.createElement(
                        'div',
                        { className: 'col-xs-12' },
                        React.createElement('hr', { className: 'hr-bar' }),
                        React.createElement(
                            'div',
                            { className: 'row' },
                            React.createElement(
                                'div',
                                { className: 'col-md-6' },
                                React.createElement(
                                    'p',
                                    { className: 'txt-bar' },
                                    item.titulo
                                )
                            ),
                            React.createElement(
                                'div',
                                { className: 'col-md-6 text-right' },
                                React.createElement(
                                    'p',
                                    { className: 'txt-bar' },
                                    item.value,
                                    ' (',
                                    formatNumber(item.value * 100 / total, 2, ',', '.') + "%",
                                    ')'
                                )
                            )
                        ),
                        React.createElement(
                            'div',
                            { style: { backgroundColor: "#F4F4F4" } },
                            React.createElement(
                                'div',
                                { className: 'width-bar', style: { width: item.value * 100 / total + "%" }, title: formatNumber(item.value * 100 / total, 2, ',', '.') + "%" },
                                '\xA0'
                            )
                        )
                    )
                );
            }.bind(this));

            return React.createElement(
                'div',
                null,
                React.createElement(
                    'h4',
                    null,
                    this.state.title
                ),
                bars
            );
        }

        return React.createElement(
            'div',
            null,
            'Defina um tipo!'
        );
    }
}