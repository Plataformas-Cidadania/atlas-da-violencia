class SeriesList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: false,
            data: [],
            search: ''
        };

        this.loadData = this.loadData.bind(this);
        this.handleChange = this.handleChange.bind(this);
    }

    componentDidMount() {
        this.loadData();
    }

    loadData() {
        this.setState({ loading: true });
        $.ajax({
            method: 'POST',
            url: this.props.url,
            data: {
                search: this.state.search,
                parameters: this.props.parameters
            },
            cache: false,
            success: function (data) {
                console.log('load', data);
                this.setState({ data: data }, function () {
                    this.setState({ loading: false });
                });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro', err);
            }.bind(this)
        });
    }

    handleChange(e) {
        e.preventDefault();
        let value = e.target.value;
        //if(value.length > 2){
        this.setState({ search: value }, function () {
            this.loadData();
        });
        //}
    }

    marked(id) {
        console.log(id);
    }

    render() {
        let select = null;
        let series = this.state.data.map(function (item) {
            if (this.props.select == 'link') {
                select = React.createElement(
                    'td',
                    null,
                    React.createElement(
                        'a',
                        { href: "map/" + item.id + "/" + item.titulo },
                        item.titulo
                    )
                );
            }
            if (this.props.select == 'mark-one') {
                select = React.createElement(
                    'td',
                    { onClick: () => this.marked(item.id), style: { cursor: 'pointer' } },
                    React.createElement(
                        'a',
                        null,
                        item.titulo
                    )
                );
            }
            /*if(this.props.select == 'mark-several'){
             }*/
            return React.createElement(
                'tr',
                { key: item.id },
                select,
                React.createElement(
                    'td',
                    null,
                    item.periodicidade
                ),
                React.createElement(
                    'td',
                    null,
                    item.min,
                    ' - ',
                    item.max
                )
            );
        }.bind(this));

        return React.createElement(
            'div',
            null,
            React.createElement('input', { type: 'text', className: 'form-control', placeholder: 'Pesquisa', onChange: this.handleChange }),
            React.createElement('br', null),
            React.createElement(
                'div',
                { className: 'text-center', style: { display: this.state.loading ? 'block' : 'none' } },
                React.createElement(
                    'i',
                    { className: 'fa fa-4x fa-spinner fa-spin' },
                    ' '
                )
            ),
            React.createElement(
                'div',
                { style: { display: this.state.loading ? 'none' : 'block' } },
                React.createElement(
                    'table',
                    { className: 'table table-bordered table-hover table-responsive' },
                    React.createElement(
                        'thead',
                        null,
                        React.createElement(
                            'tr',
                            null,
                            React.createElement(
                                'th',
                                null,
                                'S\xE9rie'
                            ),
                            React.createElement(
                                'th',
                                null,
                                'Frequ\xEAncia'
                            ),
                            React.createElement(
                                'th',
                                null,
                                'Per\xEDodo'
                            )
                        )
                    ),
                    React.createElement(
                        'tbody',
                        null,
                        series
                    )
                )
            )
        );
    }
}