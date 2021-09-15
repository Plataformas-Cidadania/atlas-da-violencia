class Download extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            serie: null,
            serieId: 0,
            loading: true,
            abrangencias: null,
            downloadsExtras: null,
        };

        this.load = this.load.bind(this);
    }

    componentWillReceiveProps(props){
        if(this.state.serieId != props.serieId){
            this.setState({serieId: props.serieId, loading: true}, function(){
                this.load();
            });
        }

    }

    load(){
        $.ajax({
            method: 'GET',
            url: 'get-opcoes-download-serie/'+this.state.serieId,
            cache: false,
            success: function(data){
                console.log(data);
                this.setState({loading:false, serie: data['serie'], abrangencias: data['abrangencias'], downloadsExtras: data['downloadsExtras']});
            }.bind(this),
            error: function(xhr, status, err){
                console.log(err);
            }
        });
    }

    render(){

        let bodyModal = null;
        let abrangencias = null;
        let downloadExtras = null;
        let divDownloadAbrangencias = null;
        let divDownloadsExtras = null;

        if(this.state.abrangencias){
            abrangencias = this.state.abrangencias.map(function(item, index){
                return (
                    <tr key={'down_abrangencia_'+index}>
                        <td>
                            <form name="frmDownloadTotal" action="download-dados" target="_blank" method="POST">
                                <input type="hidden" name="_token" value={$('meta[name="csrf-token"]').attr('content')}/>
                                <input type="hidden" name="downloadType" value='csv'/>
                                <input type="hidden" name="id" value={this.state.serieId}/>
                                <input type="hidden" name="serie" value={this.state.serie}/>
                                <input type="hidden" name="regions" value="0"/>
                                <input type="hidden" name="abrangencia" value={item.tipo_regiao}/>
                                <button className="btn-download"><div style={{float: 'left'}}>{item.title}</div> <i className="fa fa-download" aria-hidden="true"  style={{float: 'right', marginTop: '4px'}}/></button>
                            </form>
                        </td>
                    </tr>
                );
            }.bind(this));

            if(abrangencias){
                divDownloadAbrangencias = (
                    <div>
                        <p> <strong>Dados por Territórios</strong></p>
                        <hr style={{margin: '5px 0 5px 0'}}/>
                        <table className="table">
                            {abrangencias}
                        </table>
                        <br/>
                    </div>
                );
            }

            downloadExtras = this.state.downloadsExtras.map(function (item, index){
                return (
                    <tr key={'down_extras_'+index}>
                        <td>
                            <a href={"arquivos/downloads/"+item.arquivo} target="_blank" >
                            <button className="btn-download">
                                <div style={{float: 'left'}}>{item.titulo}</div>  <i className="fa fa-cloud-download" style={{fontSize: '18px', float: 'right', marginTop: '4px'}}/>
                            </button>
                            </a>
                        </td>
                    </tr>
                );
            });

            if(downloadExtras){
                divDownloadsExtras = (
                    <div>
                        <p><strong>Outros Dados</strong></p>
                        <hr style={{margin: '5px 0 5px 0'}}/>
                        <table className="table">
                            {downloadExtras}
                        </table>
                    </div>
                );
            }

            bodyModal = (
                <div>
                    {/*<h3 style={{marginTop: '0', marginBottom: '15px'}}>{this.state.serie}</h3>*/}
                    <div className="row">
                        <div className="col-md-6">
                            {divDownloadAbrangencias}
                        </div>
                        <div className="col-md-6">
                            {divDownloadsExtras}
                        </div>
                    </div>
                </div>
            );

        }

        if(this.state.loading === true){
            bodyModal = (
                <div className="text-center">
                    <i className="fa fa-spin fa-spinner fa-2x"/>
                </div>
            );
        }

        return (
            <Modal
                id="modalDownloads"
                title={this.state.serie}
                body={bodyModal}
                buttons={(
                    <div>
                        <div style={{color: '#004085', backgroundColor: '#cce5ff', borderColor: '#b8daff', padding: '7px', textAlign: 'center', borderRadius: '4px'}}>Download dos dados em csv, em UTF-8 e separados por ;</div>
                        <br/>
                        <button type="button" className="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                )}
            />
        );
    }
}
