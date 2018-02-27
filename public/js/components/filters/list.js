class List extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            items: props.items ? props.items : [],
            head: props.head ? props.head : []
        };
    }

    componentDidMount() {}

    componentWillReceiveProps() {}

    render() {

        let head = null;
        let rows = null;

        head = this.state.head.map(function (item, index) {
            return React.createElement(
                'th',
                { key: 'head' + index },
                item
            );
        });

        rows = this.state.items.map(function (item, index) {});

        return React.createElement(
            'table',
            { className: 'table' },
            React.createElement(
                'thead',
                null,
                React.createElement(
                    'tr',
                    null,
                    head
                )
            ),
            React.createElement(
                'tbody',
                null,
                rows
            )
        );
    }
}