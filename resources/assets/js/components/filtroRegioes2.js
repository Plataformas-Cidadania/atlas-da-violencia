class FiltroUfs extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            regions: [],
        };
    }

    render(){
        return(
            <div>...</div>
        );
    }
}


class FiltroRegions extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: this.props.id,
            regions: [],
            ufsSelected: [],
            regionsSelected: []
        };

        this.loadData = this.loadData.bind(this);
        this.loading = this.loading.bind(this);
        this.openRegion = this.openRegion.bind(this);
        this.selectUf = this.selectUf.bind(this);
        this.selectAll = this.selectAll.bind(this);
    }

    componentDidMount(){
        if(this.state.id > 0){
            this.loadData();
        }
    }

    componentWillReceiveProps(props){
        if(this.state.id!=props.id){
            this.setState({id: props.id}, function(){
                this.loadData();
            });
        }
    }

    loading(status){
        this.props.loading(status);
    }

    loadData(){
        this.loading(true);
        $.ajax("regioes/"+this.state.id, {
            data: {},
            success: function(data){
                console.log('regions', data);
                this.setState({regions: data}, function(){
                    //this.classDefault();
                });
                this.loading(false);
                //periodos = data;
            }.bind(this),
            error: function(data){
                console.log('erro');
            }.bind(this)
        })
    }

    openRegion(index){
        let regions = this.state.regions;
        regions[index].open = !regions[index].open;
        this.setState({regions: regions});
    }

    selectUf(codigo, uf, all){
        let ufsSelected = this.state.ufsSelected;

        let remove = false;
        let index = null;
        ufsSelected.find(function(item, i){
            if(item.uf==uf){
                index = i;
                remove = true;
            }
        });

        if(remove){
            ufsSelected.splice(index, 1);
        }else{
            ufsSelected.push({codigo: codigo, uf: uf});
        }

        this.setState({ufsSelected: ufsSelected});
    }

    selectAll(indexRegion){
        let ufsSelected = this.state.ufsSelected;
        let regions = this.state.regions;

        regions[indexRegion].ufs.find(function(item, index){
            let existe = false;
            for(let i in ufsSelected){
                if(ufsSelected[i].uf==item.uf){
                    existe = true
                }
            }
            if(!existe){
                ufsSelected.push({codigo: item.codigo, uf:item.uf});
            }
        }.bind(this));

        regions[indexRegion].allUfsSelected = true;
        this.setState({regions: regions});


    }

    render(){

        console.log(this.state.ufsSelected);

        let regions = this.state.regions.map(function(item, indexRegion){

            let ufs = item.ufs.map(function(item){

                let marcado = false;
                this.state.ufsSelected.find(function(i){
                    if(i.uf==item.uf){
                        marcado = true;
                    }
                });

                return(
                    <li key={item.sigla}>
                        <a >
                            <i className="fa fa-chevron-right" aria-hidden="true"/> {item.uf}
                            <div className="menu-box-btn-square-li" onClick={() => this.selectUf(item.codigo, item.uf, false)}>
                                <i className={"fa " + (marcado ? "fa-check-square-o" : "fa-square-o")} aria-hidden="true"/>
                            </div>
                        </a>
                    </li>
                );
            }.bind(this));

            return(
                <div key={item.sigla} className="col-md-r">
                    <div className="menu-box">
                        <div className="btn btn-primary menu-box-btn" onClick={() => this.openRegion(indexRegion)}>
                            <i className={"fa " + (item.open ? "fa-minus-square-o" : "fa-plus-square-o")} aria-hidden="true"/>&nbsp;
                            {item.region}
                            <div className="menu-box-btn-square">
                                <i className="fa fa-square-o" aria-hidden="true"/>
                            </div>
                        </div>
                        <ul style={{display: item.open ? 'block' : 'none'}}>
                            <li onClick={() => this.selectAll(indexRegion)}>
                                <a>
                                    <i className="fa fa-chevron-right" aria-hidden="true"/> <strong>Todos</strong>
                                    <div className="menu-box-btn-square-li">
                                        <i className={"fa "+(item.allUfsSelected ? "fa-check-square-o" : "fa-square-o")} aria-hidden="true"/>
                                    </div>
                                </a>
                            </li>
                            {ufs}
                        </ul>
                    </div>
                </div>
            );
        }.bind(this));

        return(
            <div className="row">
                {regions}
            </div>
        );
    }
}
