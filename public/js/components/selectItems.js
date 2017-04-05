class SelectItems extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            todos: false,
            option: this.props.option,
            options: this.props.options,
            search: '',
            parameters: { filter: 0 },
            items: [],
            style: {
                boxOptions: {
                    border: 'solid 1px #CCCCCC',
                    borderRadius: '3px',
                    padding: '15px',
                    margin: '10px 0'

                },
                boxOptionsList: {},
                boxOptionsSelect: {
                    backgroundColor: '#FBFBFB'
                },
                boxOptionsUl: {
                    margin: '0',
                    padding: '0',
                    height: '400px',
                    overflow: 'auto'
                },
                boxOptionsLi: {
                    margin: '0',
                    padding: '3px',
                    listStyle: 'none',
                    borderBottom: 'solid 1px #EEEEEE',
                    cursor: 'pointer'
                },
                boxOptionsLiHover: {
                    backgroundColor: '#EEEEEE'
                },
                boxOptionsI: {
                    color: '#333333'
                },
                faOptionsActive: {
                    color: '#85B200'
                },
                faOptionsTimes: {
                    float: 'right',
                    color: '#8C0000',
                    fontSize: '15px',
                    marginTop: '3px'
                },

                barProg: {
                    height: '5px',
                    backgroundColor: '#EEEEEE',
                    marginTop: '10px'
                },

                faItems: {
                    margin: '0 10% 0 10%',
                    float: 'left',
                    color: '#CCCCCC'
                },
                faItemsActive: {
                    color: '#3498DB'
                }

            }
        };

        this.checkAll = this.checkAll.bind(this);
        this.loadData = this.loadData.bind(this);
        this.plural = this.plural.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.selectFilter = this.selectFilter.bind(this);
    }

    componentDidMount() {
        let parameters = this.state.parameters;
        parameters.option = this.props.option;
        this.setState({ option: this.props.option, parameters: parameters }, function () {
            this.loadData();
        });
    }

    componentWillReceiveProps(props) {
        if (this.state.option != props.option) {
            let parameters = this.state.parameters;
            parameters.option = props.option;
            this.setState({ option: props.option, parameters: parameters }, function () {
                this.loadData();
            });
        }
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

    selectFilter(e) {
        let parameters = this.state.parameters;
        parameters.filter = e.target.value;
        this.setState({ parameters: parameters }, function () {
            this.loadData();
        });
    }

    loadData() {
        this.setState({ loading: true });
        console.log(this.state);
        $.ajax({
            method: 'POST',
            url: this.props.url,
            data: {
                search: this.state.search,
                parameters: this.state.parameters
            },
            cache: false,
            success: function (data) {
                console.log('selectItems', data);
                this.setState({ items: data }, function () {
                    this.setState({ loading: false });
                });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro', err);
            }.bind(this)
        });
    }

    checkAll() {
        let todos = this.state.todos;
        this.setState({ todos: !todos });
    }

    /*joinStyles(array){
        let style = {};
        array.find(function(style){
            console.log(style);
            for(let i in style){
                console.log(i+': '+style[i]);
             }
        });
    }*/

    plural(option) {
        let string = '';
        this.state.options.find(function (op) {
            if (option) {
                if (op.id == option.id) {
                    string = op.plural;
                }
            }
        });
        return string;
    }

    render() {

        let filter = null;
        if (!this.state.option.listAll) {
            filter = this.state.option.filter.map(function (item) {
                return React.createElement(
                    'option',
                    { key: item.id, value: item.id },
                    item.title
                );
            });
        }

        let selected = false;
        let items = this.state.items.map(function (item) {

            selected = item.title === 'Acre';

            return React.createElement(
                'div',
                { key: item.id },
                React.createElement(
                    'li',
                    { style: this.state.style.boxOptionsLi },
                    React.createElement('i', { className: "fa " + (selected ? "fa-check-square" : "fa-square-o"),
                        style: selected ? this.state.style.faOptionsActive : this.state.boxOptionsI, 'aria-hidden': 'true' }),
                    ' ',
                    item.title
                )
            );
        }.bind(this));

        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: this.state.option.listAll ? "col-sm-12 col-md-12" : "col-sm-8 col-md-8" },
                    React.createElement('input', { type: 'text', className: 'form-control', placeholder: 'Pesquisa', onChange: this.handleChange })
                ),
                React.createElement(
                    'div',
                    { className: 'col-sm-4 col-md-4', style: { display: this.state.option.listAll ? "none" : "block" } },
                    React.createElement(
                        'select',
                        { className: 'form-control', onChange: this.selectFilter },
                        React.createElement(
                            'option',
                            { key: '0', value: '0' },
                            'Estados'
                        ),
                        filter
                    )
                )
            ),
            React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: 'col-sm-6 col-md-6' },
                    React.createElement(
                        'div',
                        { className: 'box-options box-options-list', style: Object.assign({}, this.state.style.boxOptions, this.state.style.boxOptionsList) },
                        React.createElement(
                            'h4',
                            null,
                            React.createElement('i', { className: 'fa fa-check', 'aria-hidden': 'true' }),
                            ' Selecione ',
                            this.plural(this.state.option)
                        ),
                        React.createElement('hr', null),
                        React.createElement(
                            'ul',
                            { style: this.state.style.boxOptionsUl },
                            React.createElement(
                                'li',
                                { style: this.state.style.boxOptionsLi },
                                React.createElement(
                                    'strong',
                                    null,
                                    React.createElement('i', { className: 'fa fa-square-o ', style: Object.assign({}, this.state.style.boxOptionsI), 'aria-hidden': 'true' }),
                                    ' Todos'
                                )
                            ),
                            items
                        )
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'col-sm-6 col-md-6' },
                    React.createElement(
                        'div',
                        { className: 'box-options', style: Object.assign({}, this.state.style.boxOptions, this.state.style.boxOptionsSelect) },
                        React.createElement(
                            'h4',
                            null,
                            React.createElement('i', { className: 'fa fa-check-square-o', style: this.state.style.boxOptionsI, 'aria-hidden': 'true' }),
                            ' items selecionados'
                        ),
                        React.createElement('hr', null),
                        React.createElement(
                            'ul',
                            { style: this.state.style.boxOptionsUl },
                            React.createElement(
                                'li',
                                { style: this.state.style.boxOptionsLi },
                                React.createElement('i', { className: 'fa fa-check-square fa-options-active', style: this.state.style.faOptionsActive, 'aria-hidden': 'true' }),
                                'Rio de Janeiro',
                                React.createElement('i', { className: 'fa fa-times fa-options-times', style: Object.assign({}, this.state.style.boxOptionsI, this.state.style.faOptionsTimes), 'aria-hidden': 'true' })
                            ),
                            React.createElement(
                                'li',
                                { style: this.state.style.boxOptionsLi },
                                React.createElement('i', { className: 'fa fa-check-square fa-options-active', style: this.state.style.faOptionsActive, 'aria-hidden': 'true' }),
                                ' Acre'
                            )
                        )
                    )
                )
            )
        );
    }
}