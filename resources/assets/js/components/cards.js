class Cards extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            cards: this.props.cards.length > 0 ? this.props.cards : [],
        }
    }

    componentWillReceiveProps(props){
        if(this.state.cards != props.cards){
            this.setState({cards: props.cards});
        }
    }


    render(){

        let cards = this.state.cards.map(function(item){
            return (
                <div className="card">
                    <div className="card-body">
                        {item}
                    </div>
                </div>
            );
        });


        return(
            <div className="container">
                {/*<h2>{this.state.title}</h2>
                <div className="line-title-sm bg-pri"/><hr className="line-hr-sm"/>*/}
                <div className="card-columns">
                    {cards}
                </div>
            </div>
        );

    }
}