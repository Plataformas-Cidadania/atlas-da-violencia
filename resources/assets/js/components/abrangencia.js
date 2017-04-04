class Abrangencia extends React.Component{
    constructor(props){
        super (props);
        this.state = {
            options: [
                {id: 1, title: 'País', on:false},
                {id: 2, title: 'Região', on:false},
                {id: 3, title: 'UF', on:false},
                {id: 4, title: 'Município', on:false}
            ]
        };

        this.check = this.check.bind(this);
        this.selected = this.selected.bind(this);
    }

    check(id){
        let options = this.state.options;
        options.find(function(item){
            item.on = false;
            item.on = item.id === id;
        });

        this.setState({options: options}, function(){

        });
    }

    selected(){
        let option = null;
        this.state.options.find(function(op){
            if(op.on){
                option = op.id;
            }
        });
        return option;
    }

    render(){

        let options = this.state.options.map(function(item){
            return (
                <div key={item.id} style={{float: 'left', marginRight: '20px', cursor: 'pointer'}} onClick={() => this.check(item.id)}>
                    <div style={{display: item.on ? 'block' : 'none'}}><img  src="img/checkbox_on.png" alt=""/> {item.title}</div>
                    <div style={{display: item.on ? 'none' : 'block'}}><img  src="img/checkbox_off.png" alt=""/> {item.title}</div>
                </div>
            );
        }.bind(this));

        return(
            <div>
                <h4>Escolha a abrangencia</h4>
                <hr/>
                {options}
                <div style={{clear:'left'}}></div>
                <br/>

                <SelectItems url="territorios" option={this.selected()}/>

                <br/>
            </div>
        );
    }

}

