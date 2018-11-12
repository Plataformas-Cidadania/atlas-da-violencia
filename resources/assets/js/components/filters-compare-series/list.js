class List extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            items: props.items ? props.items : {data: []},
            head: props.head ? props.head : [],
            showId: props.showId ? props.showId : 1,
            perPage: props.perPage ? props.perPage : 20,
            currentPage: props.currentPage ? props.currentPage : 1,
            selectedItems: [],
        };

        this.select = this.select.bind(this);
        this.getAbrangencia = this.getAbrangencia.bind(this);
        this.setCurrentPage = this.setCurrentPage.bind(this);
        this.setAllItemsSelectedFalse = this.setAllItemsSelectedFalse.bind(this);
        this.selectedItem = this.selectedItem.bind(this);
    }

    componentDidMount(){

    }

    componentWillReceiveProps(props){
        if(this.state.items != props.items || this.state.currentPage != props.currentPage){
            this.setState({items: props.items, currentPage: props.currentPage}, function(){

            });
        }
    }

    setCurrentPage(page){
        this.setState({currentPage: page}, function(){
            this.props.setCurrentPageListItems(page);
        });
    }

    select(id, all){
        this.props.select(id, all);
    }

    getAbrangencia(codAbrangencia){
        let abrangencia = null;
        this.props.abrangencias.find(function(item){
            if(item.id===codAbrangencia){
               abrangencia = item.title;
            }
        });

        return abrangencia;
    }

    setAllItemsSelectedFalse(){
        let items = this.state.items.data;
        items.find(function(item){
            item.selected = false;
        });
        this.setState({items: items});
    }


    selectedItem(id){
        let selectedItems = this.state.selectedItems;
        let items = this.state.items;
        items.data.find(function(item){
            if(item.id==id){
                item.selected = !item.selected;
                if(item.selected){
                    selectedItems.push(item);
                }else{
                    selectedItems.find(function(it, index){
                        if(it.id==id){
                            selectedItems.splice(index, 1);
                        }
                    });
                }

            }
        });
        this.setState({items: items, selectedItems: selectedItems}, function(){
            this.props.selectedItems(this.state.selectedItems);
        });
    }

    render(){

        let head = [<th key="thCheckbox">&nbsp;</th>];
        let rows = null;

        console.log('items', this.state.items);
        console.log('selectedItems', this.state.selectedItems);

        head.push(this.state.head.map(function(item, index){

            return (
                <th key={'head'+index}>{item}</th>
            );
        }));

        if(this.state.items.data){//this.state.items.data
            rows = this.state.items.data.map(function(item, index){//this.state.items.data

                let columnsNames = Object.getOwnPropertyNames(item);

                //console.log(columnsNames);

                /*let buttons = [
                    <td key='btn-selecionar-territorios'>
                        <button className='btn btn-primary' style={{float: 'right'}} onClick={() => this.select(item, false)} title="selecionar territórios"><i className="fa fa-edit" style={{fontSize: '1.5em'}}/></button>
                    </td>,
                    <td key='btn-todos-os-territorios'><button className='btn btn-primary' onClick={() => this.select(item, true)} title="todos os territórios"><i className="fa fa-arrow-circle-right" style={{fontSize: '1.5em'}}/></button></td>

                ];*/

                let buttons = [];
                /*buttons[0] = (
                    <td key='btn-selecionar-territorios'>
                        <button className='btn btn-primary' style={{float: 'right'}} onClick={() => this.select(item, false)} title="selecionar territórios"><i className="fa fa-edit" style={{fontSize: '1.5em'}}/></button>
                    </td>
                );
                buttons[1] = <td>&nbsp;</td>;
                if(item.tipo_regiao != 4){
                    buttons[1] = (<td key='btn-todos-os-territorios'><a className='btn btn-success' href="dados-series/serie_id" title="todos os territórios"><i className="fa fa-arrow-circle-right" style={{fontSize: '1.5em'}}/></a></td>);
                }*/

                buttons[0] = <td key={'empty'+index}>&nbsp;</td>;
                buttons[1] = (<td key={'btn-todos-os-territorios'+index}><a className='btn btn-success' href={"dados-series/"+item.id} title="todos os territórios" target="_blank"><i className="fa fa-arrow-circle-right" style={{fontSize: '1.5em'}}/></a></td>);

                /*let buttons = [
                    <td>
                        <button className='btn btn-primary' onClick={() => this.select(item, false)}><i className="fa fa-edit" style={{fontSize: '1.5em'}}/></button>&nbsp;&nbsp;
                        <button className='btn btn-primary' onClick={() => this.select(item, true)}><i className="fa fa-arrow-circle-right" style={{fontSize: '1.5em'}}/></button>
                    </td>
                ];*/
                let columns = [<td key={'checkboxList'+index}>
                    <img src={"img/checkbox_"+(item.selected ? 'on' : 'off' )+".png"} alt="" onClick={() => this.selectedItem(item.id)}/>
                </td>];
                columns.push(columnsNames.map(function(col, i){
                    if(this.state.showId==0 && col=='id'){
                        return;
                    }

                    let content = item[col];

                    if(col==='min' || col==='max'){
                        content = content.substr(0, 4);
                    }

                    if(col==='tipo_regiao'){
                        content = this.getAbrangencia(content);
                    }


                    return (
                        <td key={'colum-serie-name'+i}>{content}</td>
                    );
                }.bind(this)));

                buttons.find(function(btn){
                    columns.push(btn);
                });

                return(
                    <tr key={'item-serie'+index} >{columns}</tr>
                );
            }.bind(this));

        }

        return (
            <div>
                <div style={{textAlign: 'right'}}>
                    <button className="btn btnPrimary"
                            disabled={this.state.selectedItems.length>1 && this.state.selectedItems.length>10}
                            title={this.state.selectedItems.length < 2 ? 'Selecione pelo menos dois items' :
                                this.state.selectedItems.length > 10 ? 'Permitido no máximo 10 items' : ''}
                    >
                        Comparar
                    </button>
                </div>

                <br/>
                <table className={'table'}>
                    <thead>
                    <tr>
                        {head}
                    </tr>
                    </thead>
                    <tbody>
                    {rows}
                    </tbody>
                </table>
                <Pagination
                    currentPage = {this.state.currentPage}
                    setCurrentPage = {this.setCurrentPage}
                    total = {this.state.items.total}
                    perPage = {this.state.perPage}
                    lastPage = {this.state.items.last_page}
                />
            </div>
        );
    }
}