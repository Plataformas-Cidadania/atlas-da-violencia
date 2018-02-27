class List extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            items: props.items ? props.items : [],
            head: props.head ? props.head : [],
        }
    }

    componentDidMount(){

    }

    componentWillReceiveProps(){

    }

    render(){

        let head = null;
        let rows = null;

        head = this.state.head.map(function(item, index){
            return (
                <th key={'head'+index}>{item}</th>
            );
        });

        rows = this.state.items.map(function(item, index){



        });

        return (
            <table className={'table'}>
                <thead>
                    <tr>
                        {head}
                    </tr>
                </thead>
                <tbody>
                    {rows}
                </tbody>
            </table>
        );
    }
}