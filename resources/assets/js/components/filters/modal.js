class Modal extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            
        }
    }
    
    
    render(){
        
        
        return(
            <div id={this.props.id} className="modal fade" tabIndex="-1" role="dialog" style={{zIndex: '9999999999999999999'}}>
                <div className="modal-dialog" role="document">
                    <div className="modal-content">
                        <div className="modal-header">
                            <button type="button" className="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 className="modal-title">{this.props.title}</h4>
                        </div>
                        <div className="modal-body">
                            {this.props.body}
                        </div>
                        <div className="modal-footer">
                            {this.props.buttons}
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}