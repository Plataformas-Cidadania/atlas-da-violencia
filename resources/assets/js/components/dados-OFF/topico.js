function Topico(props){
    return(
        <div>
            <br/>
            <div className="row">
                <div className="col-md-12">
                    <div className={"icons-groups "+props.icon} style={{float: 'left'}}>&nbsp;</div>
                    <h4 className="icon-text">&nbsp;&nbsp;{props.text}</h4>
                </div>
            </div>
            <hr style={{borderColor: '#3498DB'}}/>
            <br/>
        </div>
    );
}