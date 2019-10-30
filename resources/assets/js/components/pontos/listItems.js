class ListItems extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            type: props.type,
            items: props.items,
            filters: this.props.filters,
            types: [],
            typesAccident: [],
            genders: [],
            currentPage: 1,
            perPage: props.perPage ? props.perPage : 20,
        };

        //this.loadArrays = this.loadArrays.bind(this);
        this.setCurrentPage = this.setCurrentPage.bind(this);
    }

    componentDidMount(){
        //this.loadArrays();
    }

    componentWillReceiveProps(props){
        if(props.items != this.state.items){
            this.setState({items: props.items});
        }
        /*if(props.tipo != this.state.type){
            this.setState({type: props.type});
        }*/
        if(props.filters != this.state.filters){
            this.setState({filters: props.filters});
        }
    }

    setCurrentPage(page){
        this.setState({currentPage: page}, function(){
            this.props.setCurrentPageListItems(page);
        });
    }

    /*loadArrays(){
        $.ajax({
            method:'POST',
            url: "arrays-transito",
            data:{

            },
            cache: false,
            success: function(data) {
                //console.log('values-for-types', data);
                this.setState({types: data.types, typesAccident: data.typesAccident, genders: data.genders});
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }*/

    render(){

        let head = null;
        let items = null;

        //console.log('ITEMS ########', this.state.items);

        let colsFilters = this.state.filters.map(function (item, index){
            //console.log(item);
            return (<th key={'col-filter-'+index}>{item.titulo}</th>);
        });

        if(this.state.type == 1){
            head = (
                <tr>
                    <th>Local</th>
                    {colsFilters}
                    <th>Data</th>
                    <th>Hora</th>
                </tr>
            );
            if(this.state.items.data != undefined){
                items = this.state.items.data.map(function(item){

                    //console.log(item);

                    let tdsFilters = this.state.filters.map(function(filter, index){
                        return (
                            <td key={'value-filter-'+index}>{item[filter.slug]}</td>
                        );
                    });

                    /*let type = null;
                    this.state.types.find(function(it){
                        if(it.id==item.tipo){
                            type = it.title;
                        }
                    });
                    let typeAccident = null;
                    this.state.typesAccident.find(function(it){
                        if(it.id==item.tipo_acidente){
                            typeAccident = it.title;
                        }
                    });
                    let gender = null;
                    this.state.genders.find(function(it){
                        if(it.id==item.sexo){
                            gender = it.title;
                        }
                    });*/


                    return (
                        <tr key={"item_"+item.id}>
                            <td>{item.endereco}</td>
                            {tdsFilters}
                            <td><i className="fa fa-calendar"/> {item.data}</td>
                            <td><i className="fa fa-clock-o"/> {item.hora}</td>
                        </tr>
                    );
                }.bind(this));
            }

        }

        //console.log('ITEMS >>>>>>>>>>>>', items);

        if(this.state.type == 2){
            head = (
                <tr>
                    <th>Território</th>
                    <th>Total</th>
                </tr>
            );
            items = this.state.items.map(function(item){
                return (
                    <tr>
                        <td>{item.territorio}</td>
                        <td>{item.total}</td>
                    </tr>
                );
            });
        }

        let prev = 'disabled';
        let next = '';

        return(
            <div>
                <h2>Dados</h2>
                <div className="line-title-sm bg-pri"/><hr className="line-hr-sm"/>
                <br/>
                <table className="table">
                    <thead>
                        {head}
                    </thead>
                    <tbody>
                        {items}
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