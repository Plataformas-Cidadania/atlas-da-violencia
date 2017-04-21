class Print extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            show: false,
            generating: false
        };

        this.testLink = this.testLink.bind(this);
        this.print = this.print.bind(this);
        this.printHtml2Canvas = this.printHtml2Canvas.bind(this);
    }

    componentDidMount() {}

    componentWillUnmount() {
        clearInterval(this.interval);
    }

    testLink() {
        if ($("#" + this.props.imgPrint).attr("src") !== '') {
            this.setState({ show: true, generating: false });
            clearInterval(this.interval);
        }
    }

    print(elementId, imgPrint) {
        this.setState({ generating: true });
        this.interval = setInterval(() => this.testLink(), 1000);

        let element = document.getElementById(elementId);

        html2canvas(element, {
            onrendered: function (canvas) {
                let imageData = canvas.toDataURL("image/png");
                let img = document.getElementById(imgPrint);
                img.src = imageData;
            }
        });
    }

    printHtml2Canvas(imgPrint) {
        let tela_impressao = window.open('');
        tela_impressao.document.open();
        tela_impressao.document.write("<img src='" + document.getElementById(imgPrint).src + "'/>");
        tela_impressao.document.close();
        tela_impressao.focus();
        tela_impressao.print();
        tela_impressao.close();
    }

    render() {

        return React.createElement(
            "div",
            null,
            React.createElement(
                "div",
                { style: { display: this.state.show || this.state.generating ? 'none' : 'block' } },
                React.createElement("div", { className: "icons-components icons-component-print", onClick: () => this.print(this.props.divPrint, this.props.imgPrint) })
            ),
            React.createElement(
                "div",
                { style: { display: this.state.generating ? 'block' : 'none' } },
                React.createElement("i", { className: "fa fa-spinner fa-spin fa-2x text-primary" })
            ),
            React.createElement(
                "div",
                { style: { display: this.state.show ? 'block' : 'none' } },
                React.createElement("div", { className: "icons-components icons-component-print-active", onClick: () => this.printHtml2Canvas(this.props.imgPrint) })
            ),
            React.createElement("img", { id: this.props.imgPrint, src: "", alt: "", style: { position: 'absolute', top: '-500px' } })
        );
    }
}