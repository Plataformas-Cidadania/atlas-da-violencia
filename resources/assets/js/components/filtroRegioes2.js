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

    selectUf(uf){
        let regions = this.state.regions;


        regions.find(function (region){
            region.allUfsSelected = false;
            let qtdSelected = 0;
            region.ufs.find(function(item){
                if(item.uf == uf){
                    item.selected = !item.selected;
                }
                if(item.selected){
                    qtdSelected++;
                }
            });
            if(qtdSelected==region.ufs.length){
                region.allUfsSelected = true;
            }
        });

        this.setState({regions: regions});
    }

    selectAll(region){
        let regions = this.state.regions;

        regions.find(function(item){
            if(item.region == region){
                let all = !item.allUfsSelected;
                item.ufs.find(function(uf){
                        item.allUfsSelected = all;
                        uf.selected = all;
                });
            }
        });

        this.setState({regions: regions});
    }

    render(){

        //console.log(this.state.ufsSelected);

        let regions = this.state.regions.map(function(region, indexRegion){

            let ufs = region.ufs.map(function(item){

                return(
                    <li key={item.sigla}>
                        <a >
                            <i className="fa fa-chevron-right" aria-hidden="true"/> {item.uf}
                            <div className="menu-box-btn-square-li" onClick={() => this.selectUf(item.uf)}>
                                <i className={"fa " + (item.selected ? "fa-check-square-o" : "fa-square-o")} aria-hidden="true"/>
                            </div>
                        </a>
                    </li>
                );
            }.bind(this));

            return(
                <div key={region.sigla} className="col-md-r">
                    <div className="menu-box">
                        <div className="btn btn-primary menu-box-btn" onClick={() => this.openRegion(indexRegion)}>
                            <i className={"fa " + (region.open ? "fa-minus-square-o" : "fa-plus-square-o")} aria-hidden="true"/>&nbsp;
                            {region.region}
                            <div className="menu-box-btn-square">
                                <i className="fa fa-square-o" aria-hidden="true"/>
                            </div>
                        </div>
                        <ul style={{display: region.open ? 'block' : 'none'}}>
                            <li onClick={() => this.selectAll(region.region)}>
                                <a>
                                    <i className="fa fa-chevron-right" aria-hidden="true"/> <strong>Todos</strong>
                                    <div className="menu-box-btn-square-li">
                                        <i className={"fa "+(region.allUfsSelected ? "fa-check-square-o" : "fa-square-o")} aria-hidden="true"/>
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
