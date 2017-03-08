class RangePeriodo extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: this.props.id,
            firstLoad: true,
            slider: {},
            periodos: []
        };

        this.loadData = this.loadData.bind(this);
        this.updateRange = this.updateRange.bind(this);
        this.loadRange = this.loadRange.bind(this);
    }

    componentDidMount() {
        if (this.state.id > 0) {
            this.loadData();
        }
    }

    componentWillReceiveProps(props) {
        if (this.state.id != props.id) {
            this.setState({ id: props.id }, function () {
                this.loadData();
            });
        }
    }

    loading(status) {
        this.props.loading(status);
    }

    loadData() {
        this.loading(true);
        $.ajax("periodos/" + this.state.id, {
            data: {},
            success: function (data) {
                console.log('range', data);
                this.setState({ periodos: data }, function () {
                    this.loadRange();
                    if (!this.state.firstLoad) {
                        this.updateRange();
                    }
                    this.setState({ firstLoad: false });
                    this.props.setPeriodos(data);
                });
                this.loading(false);
                //periodos = data;
            }.bind(this),
            error: function (data) {
                console.log('erro');
            }.bind(this)
        });
    }

    updateRange() {
        this.state.slider.update({
            values: this.state.periodos,
            from: this.state.periodos[0],
            to: this.state.periodos.length - 1
        });
    }

    loadRange() {
        let _this = this;
        console.log(_this.state.periodos);
        $("#range").ionRangeSlider({
            values: _this.state.periodos,
            hide_min_max: true,
            keyboard: true,
            /*min: 0,
             max: 5000,
             from: 1000,
             to: 4000,*/
            type: 'double',
            step: 1,
            prefix: "",
            //postfix: " million pounds",
            grid: true,
            prettify_enabled: false,
            onStart: function (data) {
                //console.log('range onStart', data);
                _this.props.changePeriodo(data.from_value, data.to_value);
                //min = data.from_value;
                //max = data.to_value;
                //dataToMap(data.from_value, data.to_value);
                //dataToChart(data.from_value, data.to_value);
                //dataToChartRadar(data.from_value, data.to_value);
            },
            onChange: function (data) {
                //console.log('onChange');
            },
            onFinish: function (data) {
                //console.log('range onFinish', data);
                _this.props.changePeriodo(data.from_value, data.to_value);
                //min = data.from_value;
                //max = data.to_value;
                //dataToMap(data.from_value, data.to_value);
                //clearCharts();
                //dataToChart(data.from_value, data.to_value);
                //dataToChartRadar(data.from_value, data.to_value);
            },
            onUpdate: function (data) {
                //console.log('onUpdate');
            }

        });

        let slider = $("#range").data("ionRangeSlider");

        this.setState({ slider: slider });
    }

    render() {
        return React.createElement(
            'div',
            { className: 'hidden-print', style: { display: this.state.periodos.length > 0 ? 'block' : 'none' } },
            React.createElement(
                'h4',
                null,
                'Selecione o per\xEDodo desejado'
            ),
            React.createElement('input', { type: 'text', id: 'range', value: '', name: 'range' })
        );
    }
}