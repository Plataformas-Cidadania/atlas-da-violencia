class ChartGender extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            values: []
        };
    }

    componentWillReceiveProps(props) {
        if (this.state.values != props.values) {
            this.setState({ values: props.values });
        }
    }

    total(values) {
        let total = 0;

        values.find(function (item) {
            total += parseInt(item.value);
        });

        return total;
    }

    render() {

        let total = this.total(this.state.values);

        console.log(total);

        let male = 0;
        let female = 0;

        this.state.values.find(function (item) {
            if (item.type === 1) {
                male = item.value;
            }
            if (item.type === 2) {
                female = item.value;
            }
        });

        console.log(male);
        console.log(female);

        return React.createElement(
            "div",
            null,
            React.createElement(
                "div",
                { className: "row" },
                React.createElement(
                    "div",
                    { className: "col-md-6 text-center" },
                    React.createElement("i", { className: "fa fa-male", style: { fontSize: '150px' } })
                ),
                React.createElement(
                    "div",
                    { className: "col-md-6 text-center" },
                    React.createElement("i", { className: "fa fa-female", style: { fontSize: '150px' } })
                )
            ),
            React.createElement(
                "div",
                { className: "row" },
                React.createElement(
                    "div",
                    { className: "col-md-6 text-center" },
                    React.createElement(ChartDonutHtml5, {
                        width: "80%",
                        height: "80%",
                        strokeWidth: "4",
                        strokeRing: "#d2d3d4",
                        strokeSegment: "#3498DB",
                        percent: male * 100 / total
                    })
                ),
                React.createElement(
                    "div",
                    { className: "col-md-6 text-center" },
                    React.createElement(ChartDonutHtml5, {
                        width: "80%",
                        height: "80%",
                        strokeWidth: "4",
                        strokeRing: "#d2d3d4",
                        strokeSegment: "#ce4b99",
                        percent: female * 100 / total
                    })
                )
            )
        );
    }
}