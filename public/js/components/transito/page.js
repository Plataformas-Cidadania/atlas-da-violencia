class Page extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            idTypes: [],
            idTypesAccident: [],
            idGender: [],

            filter: 0

        };

        this.checkType = this.checkType.bind(this);
        this.checkTypeAccident = this.checkTypeAccident.bind(this);
        this.checkGender = this.checkGender.bind(this);
        this.actionFilter = this.actionFilter.bind(this);
    }

    checkType(types) {
        let ids = [];
        types.find(function (item) {
            ids.push(item.id);
        });
        this.setState({ idTypes: ids });
    }

    checkTypeAccident(types) {
        let ids = [];
        types.find(function (item) {
            ids.push(item.id);
        });
        this.setState({ idTypesAccident: ids });
    }

    checkGender(types) {
        let ids = [];
        types.find(function (item) {
            ids.push(item.id);
        });
        this.setState({ idGender: ids });
    }

    actionFilter() {
        this.setState({ filter: 1 }, function () {
            this.setState({ filter: 0 });
        });
    }

    render() {

        return React.createElement(
            "div",
            null,
            React.createElement(
                "div",
                { className: "container" },
                React.createElement(
                    "h1",
                    null,
                    "Acidentes de Transito"
                ),
                React.createElement("div", { className: "line_title bg-pri" }),
                React.createElement("br", null),
                React.createElement("br", null),
                React.createElement(Filters, { checkType: this.checkType, checkTypeAccident: this.checkTypeAccident, checkGender: this.checkGender, actionFilter: this.actionFilter })
            ),
            React.createElement("br", null),
            React.createElement(Map, { id: "1", types: this.state.idTypes, typesAccident: this.state.idTypesAccident, genders: this.state.idGender, filter: this.state.filter, actionFilter: this.actionFilter })
        );
    }
}

ReactDOM.render(React.createElement(Page, null), document.getElementById('page'));