class SeriesList extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            loading: false,
            data: [],
            search: ''
        };

        this.loadData = this.loadData.bind(this);
        this.handleChange = this.handleChange.bind(this);
    }

    componentDidMount(){
        this.loadData();
    }

    loadData(){
        this.setState({loading: true});
        $.ajax({
            method: 'POST',
            url: "listar-series",
            data: {
                search: this.state.search
            },
            cache: false,
            success: function(data){
                console.log('load', data);
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
        if(value.length > 2){
            this.setState({search: value}, function(){
                this.loadData();
            });
        }
    }

    render(){
        let series = this.state.data.map(function(item){
            return (
                <tr key="item.id">
                    <td>{item.titulo}</td>
                    {/*<td>&nbsp;</td>*/}
                    <td>{item.periodicidade}</td>
                    <td>{item.min} - {item.max}</td>
                </tr>
            );
        });

        return(
            <div>
                <input type="text" className="form-control" placeholder="Pesquisa" onChange={this.handleChange}/>
                <br/>
                <div className="text-center" style={{display: this.state.loading ? 'block' : 'none'}}>
                    <i className="fa fa-4x fa-spinner fa-spin"> </i>
                </div>
                <div style={{display: this.state.loading ? 'none' : 'block'}}>
                    <table className="table table-bordered table-hover table-responsive">
                        <thead>
                        <tr>
                            <th>Série</th>
                            {/*<th>Unidade</th>*/}
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

ReactDOM.render(
    <SeriesList/>,
    document.getElementById('series')
);