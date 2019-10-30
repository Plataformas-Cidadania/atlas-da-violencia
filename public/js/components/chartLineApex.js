class ChartLineApex extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            id: this.props.id,
            serie: this.props.serie,
            loading: false,
            min: props.min,
            max: props.max,
            intervalos: this.props.intervalos,
            regions: this.props.regions,
            abrangencia: this.props.abrangencia,

            options: {
                tooltip: {
                    items: {
                        display: 'flex'
                    }
                },
                legend: {
                    position: 'top',
                    width: 9000,
                    horizontalAlign: 'left'
                },
                chart: {
                    zoom: {
                        enabled: false
                    },
                    id: props.chartId,
                    height: 550,
                    toolbar: {
                        show: true,
                        tools: {
                            download: '<div class="icons-components icons-component-download" style="margin-top: -10px;"/>',
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true

                        },
                        autoSelected: 'zoom'
                    }
                },
                colors: ['#ccc'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight',
                    width: 1.5
                },
                markers: {
                    size: 5
                },
                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    }
                },
                xaxis: {
                    categories: ['a', 'b', 'c', 'd', 'e']
                }
            },
            series: [{
                name: "1111",
                data: [2, 3, 1, 8, 5]
            }, {
                name: "2222",
                data: [2, 5, 6, 3, 4]
            }]
        };

        this.update = this.update.bind(this);
        this.loadData = this.loadData.bind(this);
        this.getColors = this.getColors.bind(this);
        this.multipleChart = this.multipleChart.bind(this);
    }

    update(data) {
        //let series = this.state.series;

        let options = this.state.options;
        let series = this.state.series;
        let colors = this.state.series;

        let dataChart = this.multipleChart(data);

        //console.log(dataChart);

        //console.log('qtd values');

        options.colors = dataChart.colors;
        options.xaxis = {
            categories: dataChart.categories
        };
        options.legend.width = dataChart.series.length * 50;
        options.tooltip.items.display = dataChart.series.length > 30 ? 'none' : 'flex';
        //options.xaxis.categories = ['1997', '1999', '2000', '2001', '2002', '2003', '2004', '2005', '2006', '2007', '2009', '2010', '2012', '2013', '2014', '2015', '2016', '2017', '2019', '1995'];
        series = dataChart.series;

        //console.log(options.xaxis.categories);
        //console.log(options);


        this.setState({ series: series, options: options }, function () {
            ApexCharts.exec(this.props.chartId, 'updateOptions', options);
            ApexCharts.exec(this.props.chartId, 'updateSeries', series);
        });
    }

    componentDidMount() {
        if (this.state.min && this.state.max) {
            this.loadData();
        }
    }

    componentWillReceiveProps(props) {
        if (this.state.min != props.min || this.state.max != props.max || this.state.intervalos != props.intervalos || this.state.regions != props.regions || this.state.abrangencia != props.abrangencia) {
            this.setState({ min: props.min, max: props.max, intervalos: props.intervalos, regions: props.regions, abrangencia: props.abrangencia }, function () {

                if (this.state.min && this.state.max) {
                    this.loadData();
                }
            });
        }
    }

    loadData() {
        this.setState({ loading: true });
        let _this = this;
        //$.ajax("periodo/"+this.state.id+"/"+this.state.min+"/"+this.state.max, {
        $.ajax("periodo/" + this.state.id + "/" + this.state.min + "/" + this.state.max + "/" + this.state.regions + "/" + this.state.abrangencia, {
            data: {},
            success: function (data) {
                //console.log('charline', data);
                _this.setState({ loading: false }, function () {
                    _this.update(data);
                });
            },
            error: function (data) {
                console.log('erro');
            }
        });
    }

    multipleChart(data) {
        let labels = [];
        let datasets = [];
        let colorsChart = [];

        let cont = 0;
        let contLabel = 0;
        let contColor = 0;

        //Preencher labels com os períodos
        for (let region in data) {
            for (let periodo in data[region]) {
                let per = formatPeriodicidade(periodo, this.props.periodicidade);
                if (!labels.includes(per)) {
                    labels[contLabel] = per;
                    contLabel++;
                }
                data[region][per] = data[region][periodo]; //grava um indice com o periodo formatado ex: 2000-01-15 para 2000
                delete data[region][periodo]; //remove o periodo não formatado
            }
        }

        //Ordenar os períodos
        labels.sort();

        for (let region in data) {
            for (let i in labels) {
                if (!data[region].hasOwnProperty(labels[i])) {
                    data[region][labels[i]] = null;
                }
            }
        }

        for (let region in data) {

            let values = [];

            for (let periodo in data[region]) {
                values.push(data[region][periodo]);
            }

            let colors = this.getColors();

            if (contColor > colors.length - 1) {
                contColor = 0;
            }

            datasets[cont] = {
                name: region,
                data: values
            };

            colorsChart[contColor] = colors[contColor];

            cont++;
            contColor++;
        }

        //console.log(labels);

        return {
            colors: colorsChart,
            categories: labels,
            series: datasets
        };
    }

    getColors() {

        let colors = [];
        for (let i in colors2) {
            colors.push(convertHex(colors2[i], 100));
        }
        return colors;
    }

    render() {

        let min = formatPeriodicidade(this.state.min, this.props.periodicidade);
        let max = formatPeriodicidade(this.state.max, this.props.periodicidade);

        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { className: 'text-center', style: { display: this.state.loading ? 'block' : 'none' } },
                React.createElement(
                    'i',
                    { className: 'fa fa-5x fa-spinner fa-spin' },
                    ' '
                )
            ),
            React.createElement(
                'div',
                { id: 'chart', style: { display: this.state.loading ? 'none' : 'block' } },
                React.createElement(
                    'div',
                    { style: { textAlign: 'center', clear: 'both' } },
                    React.createElement(
                        'button',
                        { className: 'btn btn-primary btn-lg bg-pri', style: { border: '0' } },
                        min,
                        ' - ',
                        max
                    ),
                    React.createElement(
                        'div',
                        { style: { marginTop: '-19px' } },
                        React.createElement('i', { className: 'fa fa-sort-down fa-2x ft-pri' })
                    )
                ),
                React.createElement(ReactApexChart, { options: this.state.options, series: this.state.series, type: 'line', height: '350' })
            )
        );
    }
}