class SelectItems extends React.Component{
    constructor(props){
        super(props);

        this.state = {
            todos: false,
            option: this.props.option,
            search: 'aaaaaa',
            parameters: {},
            items:[],
            style: {
                boxItems: {
                    height: '200px',
                    overflow: 'auto',
                    border: 'solid 1px #ccc'
                },
                item: {

                }
            }
        };

        this.checkAll = this.checkAll.bind(this);
        this.loadData = this.loadData.bind(this);
    }

    componentDidMount(){
        //this.loadData();
    }

    componentWillReceiveProps(props){
        if(this.state.option != props.option){
            let parameters = this.state.parameters;
            parameters.option = props.option;
            this.setState({option:  props.option}, function(){
                this.loadData();
            });
        }
    }

    loadData(){
        this.setState({loading: true});
        console.log(this.state);
        $.ajax({
            method: 'POST',
            url: this.props.url,
            data: {
                search: this.state.search,
                parameters: this.state.parameters
            },
            cache: false,
            success: function(data){
                console.log('selectItems', data);
                this.setState({items: data}, function(){
                    this.setState({loading: false});
                });
            }.bind(this),
            error: function(xhr, status, err){
                console.log('erro', err);
            }.bind(this)
        });
    }

    checkAll(){
        let todos = this.state.todos;
        this.setState({todos: !todos});
    }

    render(){

        let items = this.state.items.map(function(item){
            return(
                <div key={item.id}>
                    <div style={this.state.style.item}><i className="fa fa-square-o"/> {item.title}</div>
                    <hr style={{margin:'5px'}}/>
                </div>
            );
        }.bind(this));


        return (
            <div>
                <div style={{cursor: 'pointer'}} onClick={this.checkAll}>
                    <div style={{display: this.state.todos ? 'block' : 'none'}}><img  src="img/checkbox_on.png" alt=""/> Todos os Municípios</div>
                    <div style={{display: this.state.todos ? 'none' : 'block'}}><img  src="img/checkbox_off.png" alt=""/> Todos os Municípios</div>
                </div>
                <br/>
                <input type="text" className="form-control" placeholder="Pesquisa" onChange={this.handleChange}/>
                <br/>
                <div style={{cursor: 'pointer'}} onClick={this.checkAll}>
                    <div style={{display: this.state.todos ? 'block' : 'none'}}><img  src="img/checkbox_on.png" alt=""/> Marcar todos os listados abaixo</div>
                    <div style={{display: this.state.todos ? 'none' : 'block'}}><img  src="img/checkbox_off.png" alt=""/> Marcar todos os listados abaixo</div>
                </div>
                <br/>
                <div className="row">
                    <div className="col-md-7" style={this.state.style.boxItems}>
                        <div style={this.state.boxItems}>
                            {items}
                        </div>
                    </div>
                    <div className="col-md-5">

                    </div>
                </div>
            </div>
        );
    }
}
