class Page extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            data: null
        };
        //this.load = this.load.bind(this);
        this.load();
    }

    componentDidMount() {}

    load() {
        let _this = this;
        $.ajax({
            method: 'GET',
            url: 'get-stations',
            data: {},
            cache: false,
            success: function (data) {
                //console.log(data);
                _this.setState({ data: data[0] });
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
                mapId: 'mapStations',
                data: this.state.data
            })
        );
    }
}

ReactDOM.render(React.createElement(Page, null), document.getElementById('page'));