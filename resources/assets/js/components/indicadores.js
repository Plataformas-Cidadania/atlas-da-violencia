class Indicadores extends React.Component{
    constructor(props){
        super (props);
        this.state = {
            indicadores: [
                {id: 1, title: 'Quantidade', on:true},
                {id: 2, title: 'Taxa por 100 mil Habitantes', on:false}
            ]
        }

        this.check = this.check.bind(this);
    }

    check(id){
        let indicadores = this.state.indicadores;
        indicadores.find(function(item){
            item.on = false;
            item.on = item.id === id;
        });

        this.setState({indicadores: indicadores});
    }

    render(){

        let indicadores = this.state.indicadores.map(function(item){
            return (
                <div key={item.id} style={{float: 'left', marginRight: '20px', cursor: 'pointer'}} onClick={() => this.check(item.id)}>
                    <div style={{display: item.on ? 'block' : 'none'}}><img  src="img/checkbox_on.png" alt=""/> {item.title}</div>
                    <div style={{display: item.on ? 'none' : 'block'}}><img  src="img/checkbox_off.png" alt=""/> {item.title}</div>
                </div>
            );
        }.bind(this));

        return(
            <div>
                <h4>Escolha o indicador</h4>
                <hr/>
                {indicadores}
                <div style={{clear:'left'}}></div>
                <br/>
            </div>
        );
    }

}
