function Topico(props) {
    return React.createElement(
        "div",
        null,
        React.createElement("br", null),
        React.createElement(
            "div",
            { className: "row" },
            React.createElement(
                "div",
                { className: "col-md-12" },
                React.createElement(
                    "div",
                    { className: "icons-groups " + props.icon, style: { float: 'left' } },
                    "\xA0"
                ),
                React.createElement(
                    "h4",
                    { className: "icon-text" },
                    "\xA0\xA0",
                    props.text
                )
            )
        ),
        React.createElement("hr", { style: { borderColor: '#3498DB' } }),
        React.createElement("br", null)
    );
}