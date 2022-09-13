class Temas extends React.Component{
    constructor(props){
        super (props);
        this.state = {
            temas: [],
            tema_id: 0,
            id: this.props.tema_id,
            showItems: false,
            tipo: props.tipo,
        };

        this.select = this.select.bind(this);
        this.loadData = this.loadData.bind(this);
        this.showHideItems = this.showHideItems.bind(this);
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
            this.showHideItems();
            this.props.setTema(id);
        });
    }

    loadData(){
        //this.setState({loading: true});
        //console.log(this.state);
        //console.log(this.props.tema_id);
        $.ajax({
            method: 'GET',
            url: 'get-temas/'+this.state.tema_id+'/'+this.state.tipo,
            cache: false,
            success: function(data){
                //console.log('temas', data);
                this.setState({temas: data});
            }.bind(this),
            error: function(xhr, status, err){
                console.log('erro', err);
            }.bind(this)
        });
    }

    showHideItems(){
        this.setState({showItems: !this.state.showItems});
    }

    render(){

        let subtema = null;

        if(this.state.id){
            subtema = <Subtema setTema={this.props.setTema} tema_id={this.state.id} tipo={this.state.tipo}/>
        }

        //let temaSelected = "Selecione um Tema";
        let temaSelected = this.props.lang_select_themes;

        let temas = this.state.temas.map(function(item){


            if(item.id==this.state.id){
                /*temaSelected = (
                    <div className="col-md-2" style={{height: '200px', textAlign: 'center'}}>
                        <img src={(item.imagem)} className={"imgLinks" + (this.state.id==item.id ? '' : 'img-disable')} width="127px" style={{marginBottom: '5px'}}  />
                        <div>
                            <h2 className="titulo-itens">{item.titulo}</h2>
                        </div>
                    </div>
                );*/
                temaSelected = (
                    <div>
                        <h1>
                            <img className="imgLinks" src={(item.imagem)}/> {item.titulo}
                        </h1>
                        <br/>
                        <h4>{item.resumida}</h4>
                        <br/>
                        <a style={{cursor: 'pointer', fontWeight: 'bold', fontSize: '18px'}} onClick={() => this.select2(0)}><i className="fa fa-arrow-circle-left"/> Trocar tema</a>
                    </div>
                );
            }


            /*return (
                <div className="col-md-2" key={'tema_'+item.id} style={{cursor:'pointer', height: '200px', textAlign: 'center'}} onClick={() => this.select2(item.id)}>
                    <img src={(item.imagem)} className={(this.state.id==item.id ? '' : 'img-disable')} width="127px" style={{marginBottom: '20px'}} />
                    <div>
                        <h2 className="titulo-itens">{item.titulo}</h2>
                    </div>
                </div>
            );*/
            return (
                <div className="filtros box-itens block" key={'tema_'+item.id} style={{cursor:'pointer'}} onClick={() => this.select2(item.id)}>
                    <div>
                        <img className="imgLinks" src={(item.imagem)} alt={item.resumida} title={item.resumida}/>
                        <div>
                            <h2 className="titulo-itens" onClick={() => this.select2(item.id)}>{item.titulo}</h2>
                        </div>
                        <div className="box-itens-filete"/>
                    </div>
                </div>
            );
        }.bind(this));

        return(
            <div>
                <div>
                    {(this.state.id > 0 ? temaSelected : temas)}
                </div>
                {subtema}
            </div>
        );
    }


}
