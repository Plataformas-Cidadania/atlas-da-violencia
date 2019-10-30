class Abrangencia extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            ufDefault: null,
            options: [
            /*{id: 1, title: 'País', plural: ' os Países', on:false, listAll:1, height: '250px'},
            {id: 2, title: 'Região', plural: 'as Regiões', on:false, listAll:1, height: '250px'},*/
            { id: 3, title: 'Piauí', plural: 'os Estados', on: false, listAll: 1, height: '400px' }, { id: 7, title: 'Territorios', plural: 'os Territorios', on: false, listAll: 1, height: '400px' }, { id: 4, title: 'Municípios', plural: 'os Municípios', on: false, listAll: 0, height: '400px',
                filter: [{ id: 12, title: 'Acre' }, { id: 27, title: 'Alagoas' }, { id: 13, title: 'Amazonas' }, { id: 16, title: 'Amapá' }, { id: 29, title: 'Bahia' }, { id: 23, title: 'Ceará' }, { id: 53, title: 'Distrito Federal' }, { id: 32, title: 'Espirito Santo' }, { id: 52, title: 'Goiás' }, { id: 21, title: 'Maranhão' }, { id: 50, title: 'Mato Grosso do Sul' }, { id: 51, title: 'Mato Grosso' }, { id: 31, title: 'Minas Gerais' }, { id: 15, title: 'Pará' }, { id: 41, title: 'Paraná' }, { id: 25, title: 'Paraíba' }, { id: 26, title: 'Pernambuco' }, { id: 22, title: 'Piauí' }, { id: 33, title: 'Rio de Janeiro' }, { id: 24, title: 'Rio Grande do Norte' }, { id: 43, title: 'Rio Grande do Sul' }, { id: 11, title: 'Rondônia' }, { id: 14, title: 'Roraima' }, { id: 42, title: 'Santa Catarina' }, { id: 35, title: 'São Paulo' }, { id: 28, title: 'Sergipe' }, { id: 17, title: 'Tocantins' }]

            }]
        };

        this.check = this.check.bind(this);
        this.loadData = this.loadData.bind(this);
        this.selected = this.selected.bind(this);
        this.setRegions = this.setRegions.bind(this);
    }

    componentDidMount() {
        this.loadData();
    }

    componentWillReceiveProps(props) {
        if (this.state.tema_id != props.tema_id) {
            this.setState({ tema_id: props.tema_id }, function () {
                this.loadData();
            });
        }
    }

    check(event) {

        let id = event.target.value;

        //console.log(id);

        this.setState({ ufDefault: null });
        if (id == 3) {
            //uf
            this.setState({ ufDefault: 22 }); //Piauí
        }

        let options = this.state.options;
        let ok = true;
        options.find(function (item) {
            //se o item clicado não estiver habilidado o ok será false para não alterar nada.
            if (item.id === id && !item.enable) {
                ok = false;
            }
        });

        if (ok) {
            options.find(function (item) {
                //se o item clicado não estiver habilidado o ok será false para não alterar nada.
                item.on = false;
                item.on = item.id === id;
            });

            this.props.setAbrangencia(id);
            this.setState({ options: options });
        }
    }

    loadData() {
        //this.setState({loading: true});
        //console.log(this.state);
        $.ajax({
            method: 'GET',
            url: 'get-abrangencias-filtros/' + this.props.tema_id,
            cache: false,
            success: function (data) {
                //console.log('abrangencias', data);
                this.setState({ options: data });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro', err);
            }.bind(this)
        });
    }

    selected() {
        let option = null;
        return this.state.options.find(function (op) {
            if (op.on) {
                option = op.id;
                return op;
            }
        });
        //return option;
    }

    setRegions(regions) {
        this.props.setRegions(regions);
    }

    render() {

        let options = this.state.options.map(function (item) {
            return React.createElement(
                'option',
                { key: "abr_" + item.id, value: item.id, disabled: !item.enable },
                item.title
            );
        }.bind(this));

        let selectItems = null;
        if (this.selected()) {
            selectItems = React.createElement(SelectItems, {
                url: 'territorios',
                option: this.selected(),
                options: this.state.options,
                setItems: this.setRegions,
                itemDefault: this.state.ufDefault
            });
        }

        return React.createElement(
            'div',
            { className: 'div-select', style: { float: 'left', marginRight: '5px' } },
            React.createElement(
                'select',
                { className: 'form-control', onClick: this.check, style: { display: 'inline' } },
                React.createElement(
                    'option',
                    { value: '0' },
                    'Todas as Abrang\xEAncias'
                ),
                options
            )
        );
    }

}