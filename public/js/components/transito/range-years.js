class RangeYear extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: props.id,
            options: ['2010', '2011', '2012', '2013', '2014'],
            mySlider: null
        };

        this.loadRange = this.loadRange.bind(this);
    }

    componentDidMount() {
        this.loadRange();
    }

    loadRange() {
        let mySlider = new rSlider({
            target: '#range-years',
            values: this.state.options,
            range: false,
            tooltip: true,
            scale: true,
            labels: false,
            set: [this.state.options[this.state.options.length - 1]]
        });

        this.setState({ mySlider: mySlider });
    }

    render() {

        return React.createElement(
            'div',
            null,
            React.createElement('input', { type: 'text', id: 'range-years' })
        );
    }
}