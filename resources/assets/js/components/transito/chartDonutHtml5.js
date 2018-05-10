class ChartDonutHtml5 extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            percent: props.percent,
        }
    }

    componentWillReceiveProps(props){
        if(props.percent != this.state.percent){
            this.setState({percent: props.percent});
        }
    }

    render(){

        return(
            <div>
                <svg width={this.props.width} height={this.props.height} viewBox="0 0 42 42" className="donut">
                    <circle className="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#fff"/>
                    <circle className="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke={this.props.strokeRing} strokeWidth={this.props.strokeWidth}/>

                    <circle className="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke={this.props.strokeSegment} strokeWidth={this.props.strokeWidth} strokeDasharray={this.state.percent+" "+(100-this.state.percent)} strokeDashoffset="25"/>>
                </svg>
                <div style={{fontSize: "25px", marginTop: "-120px", marginBottom: "120px"}}>{formatNumber(this.state.percent, 2, ',', '.')}%</div>
            </div>
        );
    }
}