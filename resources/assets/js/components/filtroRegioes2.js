

class FiltroRegions extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            id: this.props.id,
            regions: [],
            ufsSelected: [],
            regionsSelected: [],
            typeSelected: '',
            itemSelected: ''
        };

        this.loadData = this.loadData.bind(this);
        this.loading = this.loading.bind(this);
        this.openRegion = this.openRegion.bind(this);
        this.selectUf = this.selectUf.bind(this);
        this.selectAllUf = this.selectAllUf.bind(this);
        this.selectRegion = this.selectRegion.bind(this);
        this.verifyTypeSelected = this.verifyTypeSelected.bind(this);
        this.callSelected = this.callSelected.bind(this);
        this.clearRegionsSelected = this.clearRegionsSelected.bind(this);
        this.clearUfsSelected = this.clearUfsSelected.bind(this);
        this.verifyExistTypeSelected = this.verifyExistTypeSelected.bind(this);
        this.selectRegions = this.selectRegions.bind(this);
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

    selectRegion(region){

        let regions = this.state.regions;

        regions.find(function (item){
            if(item.region==region){
                //console.log(item);
                item.selected = !item.selected;
            }

        });
        regions = this.clearUfsSelected(regions);

        let typeSelected = 'region';
        let regionsSelected = this.selectRegions(regions, typeSelected);

        if(this.verifyExistTypeSelected(regions)){
            typeSelected = '';
        }

        this.props.setRegions(regionsSelected, typeSelected);
        this.setState({regions: regions, typeSelected: typeSelected, regionsSelected: regionsSelected});
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
        regions = this.clearRegionsSelected(regions);

        let typeSelected = 'uf';
        let regionsSelected = this.selectRegions(regions, typeSelected);

        if(this.verifyExistTypeSelected(regions)){
            typeSelected = '';
        }

        this.props.setRegions(regionsSelected, typeSelected);
        this.setState({regions: regions, typeSelected: typeSelected, regionsSelected: regionsSelected});

    }

    selectAllUf(region){
        let regions = this.state.regions;

        regions.find(function(item){
            item.selected = false; //para desmarcar as regiões selecionadas que são to tipo region e não uf
            if(item.region == region){
                let all = !item.allUfsSelected;
                item.ufs.find(function(uf){
                        item.allUfsSelected = all;
                        uf.selected = all;
                });
            }
        });

        regions = this.clearRegionsSelected(regions);

        let typeSelected = 'uf';
        let regionsSelected = this.selectRegions(regions, typeSelected);

        if(this.verifyExistTypeSelected(regions)){
            typeSelected = '';
        }

        this.props.setRegions(regionsSelected, typeSelected);
        this.setState({regions: regions, typeSelected: typeSelected, regionsSelected: regionsSelected});
    }

    verifyTypeSelected(type, item){
        //console.log(type,item);
        if(this.state.typeSelected!=type && this.state.typeSelected!=''){
            //itemSelected será usado no "Continuar" do modal
            this.setState({typeSelected: type, itemSelected: item}, function(){
                $('#modalInfo').modal('show');
            });
        }else{
            //this.setState({typeSelected: type, itemSelected: item}, function(){
                this.callSelected(type, item);
            //});
        }
    }


    callSelected(type, item){
        //console.log(type, item);
        if(type=='region'){
            this.selectRegion(item);
            return;
        }
        if(type=='uf'){
            this.selectUf(item);
        }
    }

    clearRegionsSelected(regions){
        //let regions = this.state.regions;

        regions.find(function(region){
            region.selected = false;
        });

        return regions;

        //this.setState({regions: regions});
    }

    clearUfsSelected(regions){
        //let regions = this.state.regions;

        regions.find(function(region){
            region.allUfsSelected = false;
            region.ufs.find(function(uf){
                uf.selected = false;
            });
        });

        return regions;

        //this.setState({regions: regions});
    }

    //verifica se existe algum item marcado para resetar o type selected
    verifyExistTypeSelected(regions){
        let qtd = 0;
        regions.find(function(region){
            if(region.selected){
                qtd++;
                return;
            }
            region.ufs.find(function(uf){
                if(uf.selected){
                    qtd++;
                    return;
                }
            });
        });

        return qtd==0;
    }

    selectRegions(regions, typeSelected){
        let regionsSelected = [];

        regions.find(function(region){
            if(typeSelected=="uf"){
                region.ufs.find(function(uf){
                    if(uf.selected){
                        regionsSelected.push(uf.codigo);
                    }
                });
            }
            if(typeSelected=="region"){
                if(region.selected){
                    regionsSelected.push(region.codigo);
                }
            }
        });

        return regionsSelected;
    }


    render(){

        //console.log(this.state.typeSelected);
        console.log(this.state.regionsSelected);
        //console.log(this.state.regions);

        let regions = this.state.regions.map(function(region, indexRegion){

            let ufs = region.ufs.map(function(item){

                return(
                    <li key={item.sigla}>
                        <a >
                            <i className="fa fa-chevron-right" aria-hidden="true"/> {item.uf}
                            <div className="menu-box-btn-square-li" onClick={() => this.verifyTypeSelected('uf', item.uf)}>
                                <i className={"fa " + (item.selected ? "fa-check-square-o" : "fa-square-o")} aria-hidden="true"/>
                            </div>
                        </a>
                    </li>
                );
            }.bind(this));

            return(
                <div key={region.sigla} className="col-md-r">
                    <div className="menu-box">
                        <div className="btn btn-primary menu-box-btn" >
                            <div style={{float:'left', width:'80%'}} onClick={() => this.openRegion(indexRegion)}>
                                <i className={"fa " + (region.open ? "fa-minus-square-o" : "fa-plus-square-o")} aria-hidden="true"/>&nbsp;
                                {region.region}
                            </div>

                            <div className="menu-box-btn-square" style={{width:'20%'}}>
                                <i onClick={() => this.verifyTypeSelected('region', region.region)}
                                   className={"fa "+(region.selected ? "fa-check-square-o" : "fa-square-o")} aria-hidden="true"/>
                            </div>
                        </div>
                        <ul style={{display: region.open ? 'block' : 'none'}}>
                            <li onClick={() => this.selectAllUf(region.region)}>
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

                {/*Modal Info*/}
                <div className="modal fade" id="modalInfo" role="dialog" style={{zIndex:'99999999999999999999'}}>
                    <div className="modal-dialog">
                        {/*Modal content*/}
                        <div className="modal-content">
                            <div className="modal-header">
                                <button type="button" className="close" data-dismiss="modal">&times;</button>
                                <h4 className="modal-title">
                                    <i className="fa fa-exclamation-triangle" style={{color: '#860000'}}/> Aviso
                                </h4>
                            </div>
                            <div className="modal-body">
                                Não é permitido marcações de tipos diferentes. Se continuar irá desmarcar todos os tipos anteriores.
                            </div>
                            <div  className="modal-footer">
                                <button className="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button className="btn btn-default" data-dismiss="modal" onClick={() => this.callSelected(this.state.typeSelected, this.state.itemSelected)}>Continuar</button>
                            </div>
                        </div>
                    </div>
                </div>
                {/*Fim Modal Info*/}
            </div>
        );
    }
}
