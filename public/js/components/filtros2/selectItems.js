class SelectItems extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            all: false,
            option: this.props.option,
            options: this.props.options,
            search: '',
            parameters: { filter: 0 },
            items: [],
            itemsSelected: [],
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

        //this.checkAll = this.checkAll.bind(this);
        this.loadData = this.loadData.bind(this);
        this.plural = this.plural.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.selectFilter = this.selectFilter.bind(this);
        this.select = this.select.bind(this);
        this.remove = this.remove.bind(this);
        this.verifySelected = this.verifySelected.bind(this);
        this.selectAll = this.selectAll.bind(this);
        this.removeAll = this.removeAll.bind(this);
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
            this.removeAll();
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
        //console.log(this.state);
        $.ajax({
            method: 'POST',
            url: this.props.url,
            data: {
                search: this.state.search,
                parameters: this.state.parameters
            },
            cache: false,
            success: function (data) {
                //console.log('selectItems', data);
                let _return = this.setFalse(data);
                this.setState({ items: _return.items, all: _return.all }, function () {
                    this.setState({ loading: false });
                });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro', err);
            }.bind(this)
        });
    }

    setFalse(items) {
        items.find(function (item) {
            item.selected = false;
        });
        return this.verifySelected(items);
    }

    verifySelected(items) {
        let itemsSelected = this.state.itemsSelected;
        items.find(function (item) {
            itemsSelected.find(function (itemSelected) {
                if (item.id == itemSelected.id) {
                    item.selected = true;
                }
            });
        });

        let all = this.verifyAll(items);

        return { items: items, all: all };
    }

    select(id) {
        //console.log(id);
        let items = this.state.items;
        let itemsSelected = this.state.itemsSelected;
        items.find(function (item) {
            if (item.id == id) {
                item.selected = !item.selected;
                if (item.selected) {
                    //console.log(itemsSelected);
                    //console.log(item);
                    itemsSelected.push(item);
                    //console.log(itemsSelected);
                } else {
                    let index = this.indexObject(itemsSelected, 'id', id);
                    itemsSelected.splice(index, 1);
                }
            }
        }.bind(this));

        let all = this.verifyAll(items);

        //console.log(itemsSelected);

        this.setState({ items: items, itemsSelected: itemsSelected, all: all }, function () {
            this.props.setItems(this.state.itemsSelected);
        });
    }

    remove(id) {
        let items = this.state.items;
        let itemsSelected = this.state.itemsSelected;
        items.find(function (item) {
            if (item.id == id) {
                item.selected = false;
            }
        }.bind(this));

        let index = this.indexObject(itemsSelected, 'id', id);
        itemsSelected.splice(index, 1);

        let all = this.verifyAll(items);

        this.setState({ items: items, itemsSelected: itemsSelected, all: all }, function () {
            this.props.setItems(this.state.itemsSelected);
        });
    }

    removeAll() {
        let items = this.state.items;
        let itemsSelected = this.state.itemsSelected;
        items.find(function (item) {
            item.selected = false;
        });
        let qtd = itemsSelected.length;
        for (let i = 0; i < qtd; i++) {
            itemsSelected.pop();
        }

        let all = this.verifyAll(items);

        this.setState({ items: items, itemsSelected: itemsSelected, all: all }, function () {
            this.props.setItems(this.state.itemsSelected);
        });
    }

    indexObject(object, property, value) {
        let index = null;
        object.find(function (item, i) {
            if (item[property] == value) {
                index = i;
            }
        });
        return index;
    }

    selectAll() {
        let items = this.state.items;
        let all = this.state.all;
        let itemsSelected = this.state.itemsSelected;
        items.find(function (item) {
            item.selected = !all;
            //busca o indice do item //retorna false se não existe.
            let index = this.indexObject(itemsSelected, 'id', item.id);
            //se item foi marcado
            if (item.selected) {
                //add se ainda não foi adicionado
                if (index === null) {
                    itemsSelected.push(item);
                }
                //se item foi desmarcado
            } else {
                //remove se foi adicionado
                if (index !== null) {
                    itemsSelected.splice(index, 1);
                }
            }
        }.bind(this));

        this.setState({ items: items, all: !all, itemsSelected: itemsSelected }, function () {
            this.props.setItems(this.state.itemsSelected);
        });
    }

    verifyAll(items) {
        let qtdSelected = 0;
        items.find(function (item) {
            if (item.selected) {
                qtdSelected++;
            }
        });
        return qtdSelected === items.length && qtdSelected > 0;
    }

    /*joinStyles(array){
        let style = {};
        array.find(function(style){
           //console.log(style);
            for(let i in style){
               //console.log(i+': '+style[i]);
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

        //console.log(this.state.parameters);

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

        let items = this.state.items.map(function (item) {
            return React.createElement(
                'div',
                { key: item.id, onClick: () => this.select(item.id) },
                React.createElement(
                    'li',
                    { style: this.state.style.boxOptionsLi },
                    React.createElement('i', { className: "fa " + (item.selected ? "fa-check-square" : "fa-square-o"),
                        style: item.selected ? this.state.style.faOptionsActive : this.state.boxOptionsI, 'aria-hidden': 'true' }),
                    ' ',
                    item.title
                )
            );
        }.bind(this));

        let itemsSelected = this.state.itemsSelected.map(function (itemSelected) {

            /*for(let i in this.state.items){
                var it = {};
                //console.log(itemSelected, this.state.items[i].id);
                if(itemSelected==this.state.items[i].id){
                   //console.log(this.state.items[i].id);
                    it.id = this.state.items[i].id;
                    it.title = this.state.items[i].title;
                    break;
                }
            }*/

            return React.createElement(
                'li',
                { key: 's' + itemSelected.id, style: this.state.style.boxOptionsLi, onClick: () => this.remove(itemSelected.id) },
                React.createElement('i', { className: 'fa fa-check-square fa-options-active', style: this.state.style.faOptionsActive, 'aria-hidden': 'true' }),
                '\xA0',
                itemSelected.title,
                React.createElement('i', { className: 'fa fa-times fa-options-times', style: Object.assign({}, this.state.style.boxOptionsI, this.state.style.faOptionsTimes), 'aria-hidden': 'true' })
            );
        }.bind(this));

        let message = null;
        if (this.state.parameters.option) {
            message = React.createElement(
                'div',
                { className: 'col-md-12', style: { display: this.state.parameters.option.listAll === 0 ? 'block' : 'none' } },
                React.createElement(
                    'p',
                    { className: 'alert alert-info' },
                    'Para esta op\xE7\xE3o \xE9 necess\xE1rio selecionar um filtro abaixo para pesquisar.'
                )
            );
        }

        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { className: 'row' },
                message,
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
                ),
                React.createElement(
                    'div',
                    { className: this.state.option.listAll ? "col-sm-12 col-md-12" : "col-sm-8 col-md-8" },
                    React.createElement('input', { type: 'text', className: 'form-control', placeholder: 'Pesquisa', onChange: this.handleChange })
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
                            { style: Object.assign({}, this.state.style.boxOptionsUl, { height: this.state.option.height }) },
                            React.createElement(
                                'li',
                                { style: this.state.style.boxOptionsLi, onClick: () => this.selectAll() },
                                React.createElement(
                                    'strong',
                                    null,
                                    React.createElement('i', { className: "fa " + (this.state.all ? "fa-check-square" : "fa-square-o"),
                                        style: this.state.all ? this.state.style.faOptionsActive : this.state.boxOptionsI, 'aria-hidden': 'true' }),
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
                            ' Itens Selecionados'
                        ),
                        React.createElement('hr', null),
                        React.createElement(
                            'ul',
                            { style: Object.assign({}, this.state.style.boxOptionsUl, { height: this.state.option.height }) },
                            React.createElement(
                                'li',
                                { style: this.state.style.boxOptionsLi, onClick: () => this.removeAll() },
                                React.createElement(
                                    'strong',
                                    { style: { display: this.state.itemsSelected.length > 0 ? 'block' : 'none' } },
                                    React.createElement('i', { className: 'fa fa-remove', style: { color: '#8C0000' }, 'aria-hidden': 'true' }),
                                    ' Remover Todos'
                                )
                            ),
                            itemsSelected
                        )
                    )
                )
            )
        );
    }
}