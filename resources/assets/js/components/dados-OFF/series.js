class Series extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            loading: false,
            data: [],
            search: '',
            parameters: this.props.parameters,
            style: {
                marked:{
                    backgroundColor: '#E9F4E3'
                },
                unmarked:{
                    backgroundColor: '#fff'
                }
            },
            markedId: '',
            typerRegionSerie: '',
            tipo_valores: '',
            tema_id: this.props.tema_id,
        };

        this.loadData = this.loadData.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.marked = this.marked.bind(this);
    }

    componentDidMount(){
        this.loadData();
    }

    componentWillReceiveProps(props){
        if(this.state.parameters.indicador != props.parameters.indicador || this.state.parameters.abrangencia != props.parameters.abrangencia  || this.state.parameters.tema_id != props.parameters.tema_id){
            this.setState({parameters: props.parameters}, function(){
                this.loadData();
            });
        }
    }

    loadData(){
        this.setState({loading: true});
        //console.log(this.state.parameters);
        $.ajax({
            method: 'POST',
            url: this.props.url,
            data: {
                search: this.state.search,
                parameters: this.state.parameters
            },
            cache: false,
            success: function(data){
                console.log('seriesList', data);
                this.setState({data: data}, function(){
                    this.setState({loading: false});
                });
            }.bind(this),
            error: function(xhr, status, err){
                console.log('erro', err);
            }.bind(this)
        });
    }

    handleChange(e){
        e.preventDefault();
        let value = e.target.value;
        //if(value.length > 2){
        this.setState({search: value}, function(){
            this.loadData();
        });
        //}
    }

    marked(id, typeRegionSerie, tipoValores){
        //console.log('marked', id, typeRegionSerie);
        this.setState({markedId: id, typeRegionSerie: typeRegionSerie, tipoValores: tipoValores}, function(){
            this.props.serieMarked(this.state.markedId, this.state.typeRegionSerie, this.state.tipoValores);
        });
    }

    render(){
        let select1 = null;
        let select2 = null;
        let thCheck = null;
        let series = this.state.data.map(function(item){
            //console.log('Titulo', item.titulo);

            if(this.props.select == 'link'){
                select1 = <td><a href={"filtros/"+item.id+"/"+item.titulo}>{item.titulo}</a></td>;
            }
            if(this.props.select == 'mark-one'){
                thCheck = <th>&nbsp;</th>;
                select1 = (
                    <td onClick={() => this.marked(item.id, item.tipo_regiao, item.tipo_valores)} style={{cursor:'pointer'}} width={20}><a>
                        <img  src={"img/checkbox_" + (item.id==this.state.markedId ? 'on' : 'off') + ".png"} alt=""/>
                    </a></td>
                );
                select2 = (
                    <td onClick={() => this.marked(item.id, item.tipo_regiao, item.tipo_valores)} style={{cursor:'pointer'}}><a>{item.titulo}</a></td>
                );

            }
            /*if(this.props.select == 'mark-several'){

            }*/
            return (
                <tr key={"serie_"+item.id} style={item.id==this.state.markedId ? this.state.style.marked : this.state.style.unmarked}>
                    {select1}
                    {select2}
                    {/*<td>&nbsp;</td>*/}
                    <td>{item.abrangencia}</td>
                    <td>{item.periodicidade}</td>
                    <td>{item.min} - {item.max}</td>
                </tr>
            );
        }.bind(this));

        return(
            <div style={{clear:'both'}}>
                <br/>
                <input type="text" className="form-control" placeholder="Pesquisa" onChange={this.handleChange}/>
                <br/>
                <div className="text-center" style={{display: this.state.loading ? 'block' : 'none'}}>
                    <i className="fa fa-4x fa-spinner fa-spin"> </i>
                </div>
                <div style={{display: this.state.loading ? 'none' : 'block'}}>
                    <table className="table table-bordered table-hover table-responsive">
                        <thead>
                        <tr>
                            {thCheck}
                            <th>Série</th>
                            {/*<th>Unidade</th>*/}
                            <th>Abrangência</th>
                            <th>Frequência</th>
                            <th>Período</th>
                        </tr>
                        </thead>
                        <tbody>
                        {series}
                        </tbody>
                    </table>
                </div>

            </div>
        );
    }
}

