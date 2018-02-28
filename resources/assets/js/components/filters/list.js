class List extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            items: props.items ? props.items : [],
            head: props.head ? props.head : [],
            showId: props.showId ? props.showId : 1,
            perPage: props.perPage ? props.perPage : 20,
        };

        this.select = this.select.bind(this);
    }

    componentDidMount(){

    }

    componentWillReceiveProps(props){
        if(this.state.items != props.items){
            this.setState({items: props.items});
        }
    }

    setCurrentPage(page){
        this.setState({currentPage: page}, function(){
            this.props.setCurrentPageListItems(page);
        });
    }

    select(id){
        this.props.select(id);
    }


    render(){

        let head = null;
        let rows = null;

        //console.log(this.state.items);

        head = this.state.head.map(function(item, index){
            return (
                <th key={'head'+index}>{item}</th>
            );
        });
        if(this.state.items.data){
            rows = this.state.items.data.map(function(item, index){

                let columnsNames = Object.getOwnPropertyNames(item);

                //console.log(columnsNames);

                let columns = columnsNames.map(function(col, i){
                    if(this.state.showId==0 && col=='id'){
                        return;
                    }
                    return (
                        <td key={'colum-serie-name'+i}>{item[col]}</td>
                    );
                }.bind(this));

                return(
                    <tr key={'item-serie'+index} onClick={() => this.select(item['id'])}>{columns}</tr>
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