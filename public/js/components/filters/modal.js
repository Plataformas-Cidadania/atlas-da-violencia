class Modal extends React.Component {
    constructor(props) {
        super(props);
        this.state = {};
    }

    render() {

        return React.createElement(
            "div",
            { id: this.props.id, className: "modal fade", tabindex: "-1", role: "dialog", style: { zIndex: '9999999999999999999' } },
            React.createElement(
                "div",
                { className: "modal-dialog", role: "document" },
                React.createElement(
                    "div",
                    { className: "modal-content" },
                    React.createElement(
                        "div",
                        { className: "modal-header" },
                        React.createElement(
                            "button",
                            { type: "button", className: "close", "data-dismiss": "modal", "aria-label": "Close" },
                            React.createElement(
                                "span",
                                { "aria-hidden": "true" },
                                "\xD7"
                            )
                        ),
                        React.createElement(
                            "h4",
                            { className: "modal-title" },
                            this.props.title
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "modal-body" },
                        this.props.body
                    ),
                    React.createElement(
                        "div",
                        { className: "modal-footer" },
                        this.props.buttons
                    )
                )
            )
        );
    }
}