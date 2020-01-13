class Page extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            data: null,
            linha: null
        };

        this.load = this.load.bind(this);
        //this.handleLinha = this.handleLinha.bind(this);
    }

    componentDidMount() {
        this.load();
    }

    /*handleLinha(e){
        this.setState({linha: e.target.value});
    }*/

    load() {
        let _this = this;
        $.ajax({
            method: 'GET',
            url: 'get-metro',
            data: {},
            cache: false,
            success: function (data) {
                console.log(data);

                _this.setState({ data: data });
            },
            error: function (xhr, status, err) {
                console.error(status, err.toString());
                _this.setState({ loading: false });
            }
        });
    }

    render() {
        return React.createElement(
            'div',
            null,
            React.createElement(Map, {
                mapId: 'mapBus',
                data: this.state.data
            })
        );
    }
}

ReactDOM.render(React.createElement(Page, null), document.getElementById('page'));