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

        /*let temas = this.state.temas.map(function(item){
            return (
                <div key={"tema2_"+item.id}
                     style={{float: 'left', padding: '3px', cursor: 'pointer', width: '120px'}}
                     className="" title={item.tema}
                     onClick={() => this.select2(item.id)}
                >
                        <img src={item.imagem ? "imagens/temas/sm-"+(item.imagem) : "img/default64.png"} className={(this.state.id==item.id ? '' : 'img-disable')}  />
                        <p style={{textTransform: 'capitalize', marginTop: '5px', height: '25px'}}>{item.tema.substr(0, 35).toLowerCase()}</p>
                </div>
            );
        }.bind(this));*/

        //let temaSelected = "Selecione um Tema";
        let temaSelected = this.props.lang_select_themes;

        let temas = this.state.temas.map(function(item){

            //let tema = item.titulo.substr(0, 25);
            let tema = item.titulo;

            if(item.id==this.state.id){
                temaSelected = (
                    <div>
                        <img src={(item.imagem)} className={(this.state.id==item.id ? '' : 'img-disable')} width="16px"  />&nbsp;&nbsp;
                        {tema} <i className="fa fa-sort-down"/>
                    </div>
                );
            }

            return (
                <div key={'tema_'+item.id} style={{cursor:'pointer', padding:'5px'}} onClick={() => this.select2(item.id)}>
                    <img src={(item.imagem)} className={(this.state.id==item.id ? '' : 'img-disable')} width="16px" />&nbsp;&nbsp;
                    {item.titulo}
                </div>
            );
        }.bind(this));

        return(
            <div>
                <div style={{clear:'left'}}/>
                <div className="div-options" onClick={() => this.showHideItems()}>{temaSelected}</div>
                <div className="div-info" style={{border: "solid 1px #CCC", display: this.state.showItems ? 'block' : 'none'}}>
                    {temas}
                </div>
                <div style={{clear: 'both'}}/>
                {subtema}
            </div>
        );
    }

}
