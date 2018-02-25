class ListItems extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            type: props.type,
            items: props.items,
            types: [],
            typesAccident: [],
            genders: [],
        }

        this.loadArrays = this.loadArrays.bind(this);
    }

    componentDidMount(){
        this.loadArrays();
    }

    componentWillReceiveProps(props){
        if(props.items != this.state.items){
            this.setState({items: props.items});
        }
        if(props.tipo != this.state.type){
            this.setState({type: props.type});
        }
    }

    loadArrays(){
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
    }

    render(){

        let head = null;
        let items = null;

        //console.log('ITEMS ########', this.state.items);

        if(this.state.type == 1){
            head = (
                <tr>
                    <th>Local</th>
                    <th>Locomoção</th>
                    <th>Tipo de Acidente</th>
                    <th>Sexo</th>
                    <th>Data</th>
                    <th>Hora</th>
                </tr>
            );
            if(this.state.items.data != undefined){
                items = this.state.items.data.map(function(item){
                    let type = null;
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
                    });


                    return (
                        <tr key={"item_"+item.id}>
                            <td>{item.endereco}</td>
                            <td>{type}</td>
                            <td>{typeAccident}</td>
                            <td>{gender}</td>
                            <td>{item.data}</td>
                            <td>{item.hora}</td>
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

        return(
            <div>
                <table className="table">
                    <thead>
                        {head}
                    </thead>
                    <tbody>
                        {items}
                    </tbody>
                </table>

            </div>
        );
    }
}