class Cards extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            cards: this.props.cards.length > 0 ? this.props.cards : []
        };
    }

    componentWillReceiveProps(props) {
        if (this.state.cards != props.cards) {
            this.setState({ cards: props.cards });
        }
    }

    render() {

        let cards = this.state.cards.map(function (item, index) {
            return React.createElement(
                "div",
                { key: "cart" + index, className: "card" },
                React.createElement(
                    "div",
                    { className: "card-body" },
                    item
                )
            );
        });

        return React.createElement(
            "div",
            { className: "container" },
            React.createElement(
                "div",
                { className: "card-columns" },
                cards
            )
        );
    }
}