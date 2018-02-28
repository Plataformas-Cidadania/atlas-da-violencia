class Pagination extends React.Component {
    constructor(props) {
        super(props);

        //console.log('PROPS', props);

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

        //console.log('STATE', this.state);

        this.setCurrentPage = this.setCurrentPage.bind(this);
    }

    componentWillReceiveProps(props) {
        //console.log('PROPS', props);
        if (this.state.prev !== props.prev && props.prev !== undefined || this.state.next !== props.next && props.next !== undefined || this.state.currentPage !== props.currentPage && props.currentPage !== undefined || this.state.from !== props.from && props.from !== undefined || this.state.to !== props.to && props.to !== undefined || this.state.lastPage !== props.lastPage && props.lastPage !== undefined || this.state.perPage !== props.perPage && props.perPage !== undefined || this.state.total !== props.total && props.total !== undefined || this.state.countNumbers !== props.countNumbers && props.countNumbers !== undefined) {
            this.setState({
                prev: props.prev,
                next: props.next,
                currentPage: props.currentPage,
                from: props.from,
                to: props.to,
                lastPage: props.lastPage,
                perPage: props.perPage,
                total: props.total,
                countNumbers: props.countNumbers ? props.countNumbers : this.state.countNumbers
            });
        }
    }

    setCurrentPage(page) {

        this.setState({ currentPage: page }, function () {
            this.props.setCurrentPage(page);
        });
    }

    render() {

        let numbers = [];
        let firstNumber = 1;
        let half = Math.floor(this.state.countNumbers / 2);

        //console.log('HALF', half);

        let lastPage = this.state.lastPage ? this.state.lastPage : 1;

        if (this.state.currentPage > half) {
            firstNumber = this.state.currentPage - half;
        }

        if (firstNumber > 1) {
            numbers.push(React.createElement(
                'li',
                { key: 'pg...start' },
                React.createElement(
                    'a',
                    { href: '' },
                    '...'
                )
            ));
        }

        //console.log(lastPage);

        for (let i = firstNumber; i <= lastPage; i++) {
            let active = null;

            if (i == this.state.currentPage) {
                active = 'active';
            }
            //console.log('PAGINATION', i, firstNumber, this.state.countNumbers);

            if (i == firstNumber + this.state.countNumbers) {
                numbers.push(React.createElement(
                    'li',
                    { key: 'pg...end' },
                    React.createElement(
                        'a',
                        { href: 'javascript:;' },
                        '...'
                    )
                ));
                break;
            }

            numbers.push(React.createElement(
                'li',
                { key: 'pg' + i, className: active },
                React.createElement(
                    'a',
                    { href: 'javascript:;', onClick: () => this.setCurrentPage(i) },
                    i
                )
            ));
        }

        return React.createElement(
            'nav',
            { 'aria-label': 'Page navigation' },
            React.createElement(
                'ul',
                { className: 'pagination' },
                React.createElement(
                    'li',
                    { className: this.state.prev },
                    React.createElement(
                        'a',
                        { href: 'javascript:;', 'aria-label': 'Previous', onClick: () => this.setCurrentPage(this.state.currentPage - 1) },
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
                    { className: this.state.next, onClick: () => this.setCurrentPage(this.state.currentPage + 1) },
                    React.createElement(
                        'a',
                        { href: 'javascript:;', 'aria-label': 'Next' },
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