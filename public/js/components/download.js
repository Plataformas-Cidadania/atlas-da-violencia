class Download extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            show: false,
            generating: false
        };

        this.testLink = this.testLink.bind(this);
        this.generate = this.generate.bind(this);
    }

    componentDidMount() {}

    componentWillUnmount() {
        clearInterval(this.interval);
    }

    testLink() {
        if ($("#" + this.props.btnDownload).attr("href") !== '#') {
            this.setState({ show: true, generating: false });
            clearInterval(this.interval);
        }
    }

    generate() {
        this.setState({ generating: true });
        this.interval = setInterval(() => this.testLink(), 1000);
        downloadImage($('#' + this.props.divDownload), this.props.btnDownload, this.props.arquivo);
    }

    render() {

        return React.createElement(
            "div",
            null,
            React.createElement(
                "div",
                { style: { display: this.state.show || this.state.generating ? 'none' : 'block' } },
                React.createElement(
                    "a",
                    { style: { cursor: "pointer" }, onClick: () => this.generate() },
                    React.createElement("div", { className: "icons-components icons-component-download" })
                )
            ),
            React.createElement(
                "div",
                { style: { display: this.state.generating ? 'block' : 'none' } },
                React.createElement("i", { className: "fa fa-spinner fa-spin fa-2x text-primary" })
            ),
            React.createElement(
                "a",
                { href: "#", id: this.props.btnDownload, style: { display: this.state.show ? 'block' : 'none' } },
                React.createElement("div", { className: "icons-components icons-component-download-active" })
            )
        );
    }
}