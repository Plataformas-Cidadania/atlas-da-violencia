class PgSerie extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            min: 0,
            max: 0,
            periodos: [],
            showMap: true,
            showCharts: true,
            showRates: true,
            showTable: true,
            showCalcs: true,
            chartLine: true,
            chartRadar: false,
            chartBar: false
        };
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
        this.showHide = this.showHide.bind(this);
    }

    changePeriodo(min, max) {
        this.setState({ min: min, max: max });
    }

    setPeriodos(periodos) {
        this.setState({ periodos: periodos });
    }

    changeChart(chart) {
        this.setState({ chartLine: false, chartRadar: false, chartBar: false }, function () {
            let chartLine = {};
            chartLine[chart] = true;
            this.setState(chartLine);
        });
    }

    showHide(target) {
        let value = this.state['show' + target];
        let inverseValue = !value;
        this.setState({ ['show' + target]: inverseValue });
    }

    render() {
        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: 'col-md-6 h3', style: { margin: 0 } },
                    React.createElement('img', { style: { marginLeft: '5px' }, src: 'imagens/links/8516-01.png', width: '52', alt: '', title: '' }),
                    '\xA0Homic\xEDdios no Brasil'
                ),
                React.createElement(
                    'div',
                    { className: 'col-md-6 text-right' },
                    React.createElement('div', { className: 'icons-groups icon-group-print', style: { marginLeft: '5px' }, title: '' }),
                    React.createElement('div', { className: "icons-groups" + (this.state.showCalcs ? " icon-group-calc" : " icon-group-calc-disable"), style: { marginLeft: '5px' }, onClick: () => this.showHide('Calcs'), title: '' }),
                    React.createElement('div', { className: "icons-groups" + (this.state.showTable ? " icon-group-table" : " icon-group-table-disable"), style: { marginLeft: '5px' }, onClick: () => this.showHide('Table'), title: '' }),
                    React.createElement('div', { className: "icons-groups" + (this.state.showRates ? " icon-group-rate" : " icon-group-rate-disable"), style: { marginLeft: '5px' }, onClick: () => this.showHide('Rates'), title: '' }),
                    React.createElement('div', { className: "icons-groups" + (this.state.showCharts ? " icon-group-chart" : " icon-group-chart-disable"), style: { marginLeft: '5px' }, onClick: () => this.showHide('Charts'), title: '' }),
                    React.createElement('div', { className: "icons-groups" + (this.state.showMap ? " icon-group-map" : " icon-group-map-disable"), style: { marginLeft: '5px' }, onClick: () => this.showHide('Map'), title: '' })
                )
            ),
            React.createElement('br', null),
            React.createElement('div', { className: 'line_title bg-pri' }),
            React.createElement('br', null),
            React.createElement(RangePeriodo, { changePeriodo: this.changePeriodo, setPeriodos: this.setPeriodos }),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement('hr', null),
            React.createElement('br', null),
            React.createElement(
                'div',
                { style: { display: this.state.showMap ? 'block' : 'none' } },
                React.createElement(Map, { min: this.state.min, max: this.state.max }),
                React.createElement('br', null),
                React.createElement('hr', null),
                React.createElement('br', null)
            ),
            React.createElement(
                'div',
                { style: { display: this.state.showCharts ? 'block' : 'none' } },
                React.createElement(
                    'div',
                    null,
                    React.createElement(
                        'div',
                        { style: { textAlign: 'right' } },
                        React.createElement(
                            'i',
                            { className: 'fa fa-3x fa-line-chart', onClick: () => this.changeChart('chartLine'),
                                style: { color: this.state.chartLine ? '#337ab7' : '', cursor: 'pointer' } },
                            ' '
                        ),
                        '\xA0\xA0\xA0\xA0\xA0',
                        React.createElement(
                            'i',
                            { className: 'fa fa-3x fa-bar-chart', onClick: () => this.changeChart('chartBar'),
                                style: { color: this.state.chartBar ? '#337ab7' : '', cursor: 'pointer' } },
                            ' '
                        ),
                        '\xA0\xA0\xA0\xA0\xA0',
                        React.createElement(
                            'i',
                            { className: 'fa fa-3x fa-star', onClick: () => this.changeChart('chartRadar'),
                                style: { color: this.state.chartRadar ? '#337ab7' : '', cursor: 'pointer' } },
                            ' '
                        ),
                        '\xA0'
                    ),
                    React.createElement(
                        'div',
                        { style: { display: this.state.chartLine ? 'block' : 'none' } },
                        React.createElement(ChartLine, { min: this.state.min, max: this.state.max, periodos: this.state.periodos })
                    ),
                    React.createElement(
                        'div',
                        { style: { display: this.state.chartBar ? 'block' : 'none' } },
                        React.createElement(ChartBar, { min: this.state.min, max: this.state.max })
                    ),
                    React.createElement(
                        'div',
                        { style: { display: this.state.chartRadar ? 'block' : 'none' } },
                        React.createElement(ChartRadar, { min: this.state.min, max: this.state.max })
                    )
                ),
                React.createElement('br', null),
                React.createElement('hr', null),
                React.createElement('br', null)
            ),
            React.createElement(
                'div',
                { style: { display: this.state.showRates ? 'block' : 'none' } },
                'Taxas',
                React.createElement('br', null),
                React.createElement('hr', null),
                React.createElement('br', null)
            ),
            React.createElement(
                'div',
                { style: { display: this.state.showTable ? 'block' : 'none' } },
                React.createElement(ListValoresSeries, { min: this.state.min, max: this.state.max }),
                React.createElement('br', null),
                React.createElement('hr', null),
                React.createElement('br', null)
            ),
            React.createElement(
                'div',
                { style: { display: this.state.showCalcs ? 'block' : 'none' } },
                'C\xE1lculos'
            )
        );
    }
}

ReactDOM.render(React.createElement(PgSerie, null), document.getElementById('pgSerie'));