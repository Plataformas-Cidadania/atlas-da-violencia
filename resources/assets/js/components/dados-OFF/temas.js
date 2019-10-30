class Temas extends React.Component{
    constructor(props){
        super (props);
        this.state = {
            temas: [],
            tema_id: 0,
            id: this.props.tema_id
        };

        this.select = this.select.bind(this);
        this.loadData = this.loadData.bind(this);
    }

    componentWillReceiveProps(props){
        /*if(this.state.id != props.id){
            this.setState({id: props.id});
        }*/
    }

    componentDidMount(){
        this.loadData();
    }

    select(event){
        let id = event.target.value;
        this.props.setTema(id);
        this.setState({id: id});
    }

    select2(id){
        this.setState({id: id}, function(){
            this.props.setTema(id);
        });
    }

    loadData(){
        //this.setState({loading: true});
        //console.log(this.state);
        //console.log(this.props.tema_id);
        $.ajax({
            method: 'GET',
            url: 'get-temas/'+this.state.tema_id,
            cache: false,
            success: function(data){
                //console.log('subtemas', data);
                this.setState({temas: data});
            }.bind(this),
            error: function(xhr, status, err){
                console.log('erro', err);
            }.bind(this)
        });
    }

    render(){

        let subtema = null;

        if(this.state.id){
            subtema = <Subtema setTema={this.props.setTema} tema_id={this.state.id}/>
        }

        /*let temas = this.state.temas.map(function(item){
            return (
                <option key={"subtema_"+item.id} value={item.id} selected={this.state.id==item.id}>{item.tema}</option>
            );
        }.bind(this));*/

        let temas = this.state.temas.map(function(item){
            return (
                <div key={"tema2_"+item.id}
                     style={{float: 'left', padding: '3px', cursor: 'pointer', width: '150px'}}
                     className="text-center" title={item.tema}
                     onClick={() => this.select2(item.id)}
                >
                    <img src={item.imagem ? "imagens/temas/sm-"+(item.imagem) : "img/default64.png"} className={(this.state.id==item.id ? '' : 'img-disable')}  />
                    <p style={{textTransform: 'capitalize', marginTop: '5px', height: '25px'}}>{item.tema.substr(0, 35).toLowerCase()}</p>
                </div>
            );
        }.bind(this));

        return(
            <div>
                {/*<div style={{float:'left', marginRight: '5px'}}>
                    <select className="form-control" onChange={this.select} style={{maxWidth: '300px'}}>
                        <option value="">Selecione</option>
                        {temas}
                    </select>
                </div>*/}
                {temas}
                <br/>
                <div style={{clear:'both'}}/>
                <div style={{float:'left'}}>
                    {subtema}
                </div>
            </div>
        );
    }

}
