class ListValoresSeries extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            valores: []
        };
        this.loadDataToList = this.loadDataToList.bind(this);
    }

    componentWillReceiveProps(props) {
        this.setState({ min: props.min, max: props.max }, function () {
            this.loadDataToList();
        });
    }

    loadDataToList() {
        $.ajax({
            method: 'GET',
            url: "valores-series/" + this.props.min + "/" + this.props.max,
            cache: false,
            success: function (data) {
                //console.log(data);
                this.setState({ valores: data });
                //loadMap(data);
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    render() {
        console.log('========================================================');
        let valores = this.state.valores.map(function (item, index) {
            return React.createElement(
                "tr",
                { key: index },
                React.createElement(
                    "th",
                    null,
                    React.createElement(
                        "i",
                        { className: "fa fa-square", style: { color: getColor(item.total) } },
                        " "
                    ),
                    " ",
                    item.uf
                ),
                React.createElement(
                    "td",
                    null,
                    item.total
                )
            );
        });
        console.log('========================================================');

        return React.createElement(
            "table",
            { className: "table table-striped table-bordered" },
            React.createElement(
                "thead",
                null,
                React.createElement(
                    "tr",
                    null,
                    React.createElement(
                        "th",
                        null,
                        "Uf"
                    ),
                    React.createElement(
                        "th",
                        null,
                        "Ocorr\xEAncias"
                    )
                )
            ),
            React.createElement(
                "tbody",
                null,
                valores
            )
        );
    }
}