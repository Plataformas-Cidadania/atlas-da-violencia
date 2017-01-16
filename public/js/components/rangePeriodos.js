class RangePeriodo extends React.Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        this.loadData();
    }

    loadData() {
        let _this = this;
        $.ajax("periodos", {
            data: {},
            success: function (data) {
                //console.log(data);
                periodos = data;
                _this.loadRange();
            },
            error: function (data) {
                console.log('erro');
            }
        });
    }

    loadRange() {
        let _this = this;
        $("#range").ionRangeSlider({
            values: periodos,
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
                _this.props.changePeriodo(data.from_value, data.to_value);
                //min = data.from_value;
                //max = data.to_value;
                dataToMap(data.from_value, data.to_value);
                dataToChart(data.from_value, data.to_value);
                dataToChartRadar(data.from_value, data.to_value);
            },
            onChange: function (data) {
                //console.log('onChange');
            },
            onFinish: function (data) {
                _this.props.changePeriodo(data.from_value, data.to_value);
                //min = data.from_value;
                //max = data.to_value;
                dataToMap(data.from_value, data.to_value);
                clearCharts();
                dataToChart(data.from_value, data.to_value);
                dataToChartRadar(data.from_value, data.to_value);
            },
            onUpdate: function (data) {
                //console.log('onUpdate');
            }

        });
    }

    render() {
        return React.createElement(
            "div",
            { className: "hidden-print" },
            React.createElement(
                "h4",
                null,
                React.createElement(
                    "i",
                    { className: "fa fa-calendar", "aria-hidden": "true" },
                    " "
                ),
                " Periodicidade"
            ),
            React.createElement("input", { type: "text", id: "range", value: "", name: "range" })
        );
    }
}