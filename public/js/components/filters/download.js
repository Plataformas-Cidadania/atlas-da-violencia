class Download extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            serie: null,
            serieId: 0,
            loading: true,
            abrangencias: null,
            downloadsExtras: null
        };

        this.load = this.load.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.serieId != props.serieId) {
            this.setState({ serieId: props.serieId, loading: true }, function () {
                this.load();
            });
        }
    }

    load() {
        $.ajax({
            method: 'GET',
            url: 'get-opcoes-download-serie/' + this.state.serieId,
            cache: false,
            success: function (data) {
                console.log(data);
                this.setState({ loading: false, serie: data['serie'], abrangencias: data['abrangencias'], downloadsExtras: data['downloadsExtras'] });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log(err);
            }
        });
    }

    render() {

        let bodyModal = null;
        let abrangencias = null;
        let downloadExtras = null;
        let divDownloadAbrangencias = null;
        let divDownloadsExtras = null;

        if (this.state.abrangencias) {
            abrangencias = this.state.abrangencias.map(function (item, index) {
                return React.createElement(
                    'tr',
                    { key: 'down_abrangencia_' + index },
                    React.createElement(
                        'td',
                        null,
                        React.createElement(
                            'form',
                            { name: 'frmDownloadTotal', action: 'download-dados', target: '_blank', method: 'POST' },
                            React.createElement('input', { type: 'hidden', name: '_token', value: $('meta[name="csrf-token"]').attr('content') }),
                            React.createElement('input', { type: 'hidden', name: 'downloadType', value: 'csv' }),
                            React.createElement('input', { type: 'hidden', name: 'id', value: this.state.serieId }),
                            React.createElement('input', { type: 'hidden', name: 'serie', value: this.state.serie }),
                            React.createElement('input', { type: 'hidden', name: 'regions', value: '0' }),
                            React.createElement('input', { type: 'hidden', name: 'abrangencia', value: item.tipo_regiao }),
                            React.createElement(
                                'button',
                                { className: 'btn-download' },
                                React.createElement(
                                    'div',
                                    { style: { float: 'left' } },
                                    item.title
                                ),
                                ' ',
                                React.createElement('i', { className: 'fa fa-download', 'aria-hidden': 'true', style: { float: 'right', marginTop: '4px' } })
                            )
                        )
                    )
                );
            }.bind(this));

            if (abrangencias) {
                divDownloadAbrangencias = React.createElement(
                    'div',
                    null,
                    React.createElement(
                        'p',
                        null,
                        ' ',
                        React.createElement(
                            'strong',
                            null,
                            'Dados por Territ\xF3rios'
                        )
                    ),
                    React.createElement('hr', { style: { margin: '5px 0 5px 0' } }),
                    React.createElement(
                        'table',
                        { className: 'table' },
                        abrangencias
                    ),
                    React.createElement('br', null)
                );
            }

            downloadExtras = this.state.downloadsExtras.map(function (item, index) {
                return React.createElement(
                    'tr',
                    { key: 'down_extras_' + index },
                    React.createElement(
                        'td',
                        null,
                        React.createElement(
                            'a',
                            { href: "arquivos/downloads/" + item.arquivo, target: '_blank' },
                            React.createElement(
                                'button',
                                { className: 'btn-download' },
                                React.createElement(
                                    'div',
                                    { style: { float: 'left' } },
                                    item.titulo
                                ),
                                '  ',
                                React.createElement('i', { className: 'fa fa-cloud-download', style: { fontSize: '18px', float: 'right', marginTop: '4px' } })
                            )
                        )
                    )
                );
            });

            if (downloadExtras) {
                divDownloadsExtras = React.createElement(
                    'div',
                    null,
                    React.createElement(
                        'p',
                        null,
                        React.createElement(
                            'strong',
                            null,
                            'Outros Dados'
                        )
                    ),
                    React.createElement('hr', { style: { margin: '5px 0 5px 0' } }),
                    React.createElement(
                        'table',
                        { className: 'table' },
                        downloadExtras
                    )
                );
            }

            bodyModal = React.createElement(
                'div',
                null,
                React.createElement(
                    'div',
                    { className: 'row' },
                    React.createElement(
                        'div',
                        { className: 'col-md-6' },
                        divDownloadAbrangencias
                    ),
                    React.createElement(
                        'div',
                        { className: 'col-md-6' },
                        divDownloadsExtras
                    )
                )
            );
        }

        if (this.state.loading === true) {
            bodyModal = React.createElement(
                'div',
                { className: 'text-center' },
                React.createElement('i', { className: 'fa fa-spin fa-spinner fa-2x' })
            );
        }

        return React.createElement(Modal, {
            id: 'modalDownloads',
            title: this.state.serie,
            body: bodyModal,
            buttons: React.createElement(
                'div',
                null,
                React.createElement(
                    'div',
                    { style: { color: '#004085', backgroundColor: '#cce5ff', borderColor: '#b8daff', padding: '7px', textAlign: 'center', borderRadius: '4px' } },
                    'Download dos dados em csv, em UTF-8 e separados por ;'
                ),
                React.createElement('br', null),
                React.createElement(
                    'button',
                    { type: 'button', className: 'btn btn-default', 'data-dismiss': 'modal' },
                    'Fechar'
                )
            )
        });
    }
}