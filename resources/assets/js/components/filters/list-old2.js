class List extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            items: props.items ? props.items : {data: []},
            head: props.head ? props.head : [],
            showId: props.showId ? props.showId : 1,
            perPage: props.perPage ? props.perPage : 20,
            currentPage: props.currentPage ? props.currentPage : 1,
            urlDetailItem: props.urlDetailItem ? props.urlDetailItem : '#',
        };

        this.select = this.select.bind(this);
        this.getAbrangencia = this.getAbrangencia.bind(this);
        this.setCurrentPage = this.setCurrentPage.bind(this);
    }

    componentDidMount(){

    }

    componentWillReceiveProps(props){
        if(this.state.items != props.items || this.state.currentPage != props.currentPage){
            this.setState({items: props.items, currentPage: props.currentPage});
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

    render(){

        let head = null;
        let rows = null;

        let random = Math.floor((Math.random() * 99999) + 1);

        //console.log(this.state.items);

        head = this.state.head.map(function(item, index){
            return (
                <th key={'head'+index}>{item}</th>
            );
        });
        if(this.state.items.data){//this.state.items.data
            rows = this.state.items.data.map(function(item, index){//this.state.items.data

                let columnsNames = Object.getOwnPropertyNames(item);


                let buttons = [];

                //buttons[0] = <td>&nbsp;</td>;
                buttons[0] = (<td key={'btn-pontos'+index} className="text-right"><a className='btn btn-success' href={"pontos/"+item.id} title="Pontos" target="_blank"><i className="fa fa-map-marker" style={{fontSize: '1.5em'}}/></a></td>);
                buttons[1] = (<td key={'btn-todos-os-territorios'+index} className="text-right"><a className='btn btn-success' href={this.state.urlDetailItem+"/"+item.id} title="Territórios" target="_blank"><i className="fa fa-arrow-circle-right" style={{fontSize: '1.5em'}}/></a></td>);
                if(item.tipo_dados==0 || !item.tipo_dados){
                    buttons[0] = (<td>&nbsp;</td>);
                }
                if(item.tipo_dados==1){
                    buttons[1] = (<td>&nbsp;</td>);
                }


                let columns = columnsNames.map(function(col, i){
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
                        <td key={'colum-serie-name'+i+random}>{content}</td>
                    );
                }.bind(this));

                buttons.find(function(btn){
                    columns.push(btn);
                });

                return(
                    <tr key={'item-serie'+index+random} >{columns}</tr>
                );
            }.bind(this));

        }

        return (
            <div>
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
