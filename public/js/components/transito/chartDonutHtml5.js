class ChartDonutHtml5 extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            percent: props.percent
        };
    }

    componentWillReceiveProps(props) {
        if (props.percent != this.state.percent) {
            this.setState({ percent: props.percent });
        }
    }

    render() {

        return React.createElement(
            "div",
            null,
            React.createElement(
                "svg",
                { width: this.props.width, height: this.props.height, viewBox: "0 0 42 42", className: "donut" },
                React.createElement("circle", { className: "donut-hole", cx: "21", cy: "21", r: "15.91549430918954", fill: "#fff" }),
                React.createElement("circle", { className: "donut-ring", cx: "21", cy: "21", r: "15.91549430918954", fill: "transparent", stroke: this.props.strokeRing, strokeWidth: this.props.strokeWidth }),
                React.createElement("circle", { className: "donut-segment", cx: "21", cy: "21", r: "15.91549430918954", fill: "transparent", stroke: this.props.strokeSegment, strokeWidth: this.props.strokeWidth, strokeDasharray: this.state.percent + " " + (100 - this.state.percent), strokeDashoffset: "25" }),
                ">"
            ),
            React.createElement(
                "div",
                { style: { fontSize: "35px", marginTop: "-120px", marginBottom: "120px" } },
                formatNumber(this.state.percent, 2, ',', '.'),
                "%"
            )
        );
    }
}