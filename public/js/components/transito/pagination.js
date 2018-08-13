class Pagination extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            prev: props.prev ? props.prev : 'disable',
            next: props.next ? props.next : 'enable',
            currentPage: props.currentPage ? props.currentPage : 1,
            from: props.from ? props.from : 1,
            to: props.to ? props.to : 50,
            lastPage: props.lastPage ? props.lastPage : 10,
            perPage: props.perPage ? props.perPage : 10,
            total: props.total ? props.total : 50,
            countNumbers: props.countNumbers ? props.countNumbers : 10
        };
    }

    componentWillReceiveProps(props) {
        if (this.state.prev !== props.prev || this.state.next !== props.next || this.state.currentPage !== props.currentPage || this.state.from !== props.from || this.state.to !== props.to || this.state.lastPage !== props.lastPage || this.state.perPage !== props.perPage || this.state.total !== props.total) {
            this.setState({
                prev: props.prev,
                next: props.next,
                currentPage: props.currentPage,
                from: props.from,
                to: props.to,
                lastPage: props.lastPage,
                perPage: props.perPage,
                total: props.total
            });
        }
    }

    render() {

        let numbers = null;

        for (let i = 1; i < this.state.total; i++) {
            let active = null;
            if (i == this.state.currentPage) {
                active = 'active';
            }
            numbers += React.createElement(
                'li',
                { className: active },
                React.createElement(
                    'a',
                    { href: '#' },
                    i
                )
            );
        }

        return React.createElement(
            'nav',
            { 'aria-label': 'Page navigation' },
            React.createElement(
                'ul',
                { className: 'pagination' },
                React.createElement(
                    'li',
                    { className: prev },
                    React.createElement(
                        'a',
                        { href: '#', 'aria-label': 'Previous' },
                        React.createElement(
                            'span',
                            { 'aria-hidden': 'true' },
                            '\xAB'
                        )
                    )
                ),
                numbers,
                React.createElement(
                    'li',
                    { className: next },
                    React.createElement(
                        'a',
                        { href: '#', 'aria-label': 'Next' },
                        React.createElement(
                            'span',
                            { 'aria-hidden': 'true' },
                            '\xBB'
                        )
                    )
                )
            )
        );
    }
}