class Calcs extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            data: {},
            min: 0,
            max: 0,
            minValue: 0,
            maxValue: 0,
            media: [],
            mediaPonderada: [],
            moda: [],
            mediana: [],
            styleCalcs: { paddingTop: '55px' }
        };

        this.calcMinMax = this.calcMinMax.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.min != props.data.min || this.state.max != props.data.max) {
            this.setState({ min: props.data.min, max: props.data.max, data: props.data.values }, function () {
                this.calcMinMax(this.state.data);
                this.calcMedia(1, this.state.data);
                this.calcMediaPonderada(1, this.state.data);
                this.calcModa(1, this.state.data);
                this.calcMediana(1, this.state.data);
            });
        }
    }

    calcMinMax(data) {
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

    calcMedia(id_serie, serie) {
        let total = 0;
        for (let i in serie) {
            total += parseFloat(serie[i].total);
        }

        let media = [];
        media[id_serie] = total / serie.length;
        this.setState({ media: media });
    }

    calcModa(id_serie, serie) {
        let moda = [];
        let numeros = {};
        for (let i in serie) {
            if (!numeros[serie[i].total]) {
                numeros[serie[i].total] = 0;
            }
            numeros[serie[i].total]++;
        }
        let modaN = 0;
        let qtd_moda = 0;
        for (let i in numeros) {
            //console.log(i);
            if (numeros[i] > qtd_moda) {
                modaN = i;
                qtd_moda = numeros[i];
            }
        }
        if (qtd_moda == 1) {
            moda[id_serie] = [];
            return;
        }
        let array_moda = [modaN];
        for (let i in numeros) {
            //console.log(i);
            if (numeros[i] == qtd_moda && i != modaN) {
                array_moda.push(i);
            }
        }
        for (let i in array_moda) {
            array_moda[i] = array_moda[i];
        }

        moda[id_serie] = array_moda.join(' - ');
        this.setState({ moda: moda });
    }

    calcMediana(id_serie, serie) {
        serie.sort(function (a, b) {
            return a.total - b.total;
        });
        let qtd = serie.length;
        if (qtd % 2 != 0) {
            let pos = (qtd - 1) / 2;
            let mediana = serie[pos].total;
            let arrayMediana = [];
            arrayMediana[id_serie] = mediana;
            this.setState({ mediana: arrayMediana });
            return;
        }
        let pos = qtd / 2;
        let mediana = (serie[pos].total + serie[pos - 1].total) / 2;
        let arrayMediana = [];
        arrayMediana[id_serie] = mediana;
        this.setState({ mediana: arrayMediana });
    }

    calcMediaPonderada(id_serie, serie) {
        let total_indices = 0;
        let total_valores = 0;
        let qtd = serie.length;
        for (let i = 0; i < qtd; i++) {
            total_indices += i + 1;
            //console.log(total_indices);
            total_valores += serie[i].total * (i + 1);
        }
        let ponderada = [];
        ponderada[id_serie] = total_valores / total_indices;
        this.setState({ mediaPonderada: ponderada });
    }

    render() {
        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { className: 'row', style: { display: 'none' } },
                React.createElement(
                    'div',
                    { className: 'col-md-12' },
                    React.createElement('div', { className: 'icons-list-items icon-list-item-1' }),
                    React.createElement('div', { className: 'icons-list-items icon-list-item-1' }),
                    React.createElement('div', { className: 'icons-list-items icon-list-item-1' }),
                    React.createElement('div', { className: 'icons-list-items icon-list-item-1' })
                ),
                React.createElement('br', null)
            ),
            React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: 'col-md-12' },
                    React.createElement('div', { className: 'icons-list-items icon-list-item-1', style: { float: 'left' } }),
                    React.createElement(
                        'h4',
                        null,
                        '\xA0\xA0Hom\xEDcidios Brasil'
                    )
                )
            ),
            React.createElement('br', null),
            React.createElement(
                'div',
                { className: 'row text-center' },
                React.createElement(
                    'div',
                    { className: 'col-md-2' },
                    React.createElement(
                        'div',
                        { className: 'icons-list-140-150 icon-list-140-150-1' },
                        React.createElement(
                            'h3',
                            { className: '', style: this.state.styleCalcs },
                            this.state.minValue.total
                        )
                    ),
                    React.createElement(
                        'h3',
                        null,
                        'M\xEDnima'
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'col-md-2' },
                    React.createElement(
                        'div',
                        { className: 'icons-list-140-150 icon-list-140-150-1' },
                        React.createElement(
                            'h3',
                            { className: '', style: this.state.styleCalcs },
                            this.state.maxValue.total
                        )
                    ),
                    React.createElement(
                        'h3',
                        null,
                        'M\xE1xima'
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'col-md-2' },
                    React.createElement(
                        'div',
                        { className: 'icons-list-140-150 icon-list-140-150-1' },
                        React.createElement(
                            'h3',
                            { className: '', style: this.state.styleCalcs },
                            numeral(this.state.media[1]).format('0.00')
                        )
                    ),
                    React.createElement(
                        'h3',
                        null,
                        'M\xE9dia'
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'col-md-2' },
                    React.createElement(
                        'div',
                        { className: 'icons-list-140-150 icon-list-140-150-1' },
                        React.createElement(
                            'h3',
                            { className: '', style: this.state.styleCalcs },
                            numeral(this.state.mediaPonderada[1]).format('0.00')
                        )
                    ),
                    React.createElement(
                        'h3',
                        null,
                        'M\xE9dia Ponderada'
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'col-md-2' },
                    React.createElement(
                        'div',
                        { className: 'icons-list-140-150 icon-list-140-150-1' },
                        React.createElement(
                            'h3',
                            { className: '', style: this.state.styleCalcs },
                            numeral(this.state.moda[1]).format('0.00')
                        )
                    ),
                    React.createElement(
                        'h3',
                        null,
                        'Moda'
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'col-md-2' },
                    React.createElement(
                        'div',
                        { className: 'icons-list-140-150 icon-list-140-150-1' },
                        React.createElement(
                            'h3',
                            { className: '', style: this.state.styleCalcs },
                            numeral(this.state.mediana[1]).format('0.00')
                        )
                    ),
                    React.createElement(
                        'h3',
                        null,
                        'Mediana'
                    )
                )
            )
        );
    }
}