class Modal extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            
        }
    }
    
    
    render(){
        
        
        return(
            <div id={this.props.id} className="modal fade" tabindex="-1" role="dialog">
                <div className="modal-dialog" role="document">
                    <div className="modal-content">
                        <div className="modal-header">
                            <button type="button" className="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 className="modal-title">{this.props.title}</h4>
                        </div>
                        <div className="modal-body">
                            <p>{this.props.body}</p>
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