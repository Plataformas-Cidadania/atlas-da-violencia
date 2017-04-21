class Download extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            show: false,
            generating: false
        };

        this.testLink = this.testLink.bind(this);
        this.generate = this.generate.bind(this);
    }

    componentDidMount(){

    }

    componentWillUnmount() {
        clearInterval(this.interval);
    }

    testLink(){
        if($("#"+this.props.btnDownload).attr("href")!=='#'){
            this.setState({show:true, generating:false});
            clearInterval(this.interval);
        }
    }

    generate(){
        this.setState({generating:true});
        this.interval = setInterval(() => this.testLink(), 1000);
        downloadImage($('#'+this.props.divDownload), this.props.btnDownload, this.props.arquivo);
    }

    render(){

        return(
            <div>
                <div style={{display: this.state.show || this.state.generating ? 'none' : 'block'}}>
                    <a style={{cursor: "pointer"}} onClick={() => this.generate()}>
                        <div className="icons-components icons-component-download"/>
                    </a>
                </div>
                <div style={{display: this.state.generating ? 'block' : 'none'}}>
                    <i className="fa fa-spinner fa-spin fa-2x text-primary"/>
                </div>
                <a href="#" id={this.props.btnDownload} style={{display: this.state.show ? 'block' : 'none'}}>
                    <div className="icons-components icons-component-download-active"/>
                </a>
                {/*<div className="icons-components icons-component-print"/>
                <div className="icons-components icons-component-download"/>
                <div className="icons-components icons-component-csv"/>
                <div className="icons-components icons-component-pdf"/>
                <div className="icons-components icons-component-btn"/>*/}

            </div>
        );
    }
}

