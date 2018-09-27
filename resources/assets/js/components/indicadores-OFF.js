class Indicadores extends React.Component{
    constructor(props){
        super (props);
        this.state = {
            indicadores: [
                {id: 1, title: 'Quantidade', on:false, enable:true},
                {id: 2, title: 'Taxa por 100 mil Habitantes', on:false, enable:true}
            ]
        };

        this.check = this.check.bind(this);
        this.loadData = this.loadData.bind(this);
    }

    componentDidMount(){
        this.loadData();
    }

    check(id){

        let indicadores = this.state.indicadores;
        let ok = true;
        indicadores.find(function(item){
            //se o item clicado não estiver habilidado o ok será false para não alterar nada.
            if(item.id === id && !item.enable){
                ok = false;
            }
        });

        if(ok) {
            indicadores.find(function(item){
                //se o item clicado não estiver habilidado o ok será false para não alterar nada.
                item.on = false;
                item.on = item.id === id;
            });

            this.props.setIndicador(id);
            this.setState({indicadores: indicadores});
        }
    }

    loadData(){
        //this.setState({loading: true});
        //console.log(this.state);
        $.ajax({
            method: 'GET',
            url: 'get-indicadores-series/'+this.props.serie_id,
            cache: false,
            success: function(data){
                //console.log('indicadores', data);
                this.setState({indicadores: data});
            }.bind(this),
            error: function(xhr, status, err){
                console.log('erro', err);
            }.bind(this)
        });
    }

    render(){

        let indicadores = this.state.indicadores.map(function(item){
            return (
                <div key={item.id} style={{float: 'left', marginRight: '20px', cursor: 'pointer', color: item.enable ? '' : '#ccc'}} onClick={() => this.check(item.id)}>
                    <div style={{display: item.on ? 'block' : 'none'}}><img  src="img/checkbox_on.png" alt=""/> {item.title}</div>
                    <div style={{display: item.on ? 'none' : 'block'}}><img  src="img/checkbox_off.png" alt=""/> {item.title}</div>
                </div>
            );
        }.bind(this));

        return(
            <div>
                <h4>Selecione o indicador</h4>
                <hr/>
                {indicadores}
                <div style={{clear:'left'}}></div>
                <br/>
            </div>
        );
    }

}
