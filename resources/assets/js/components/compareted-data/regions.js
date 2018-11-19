class Regions extends React.Component{
	constructor(props){
		super(props);
		this.state = {
			regionsIds: props.regions ? props.regions : [],
			regions: [],
			abrangencia: props.abrangencia,
			region: props.region,
			loading: false,
		};

		this.loadRegions = this.loadRegions.bind(this);
		this.selectRegion = this.selectRegion.bind(this);
	}


	componentDidMount(){
		this.loadRegions();
	}

	componentWillReceiveProps(props){
		if(this.state.region != props.region){
			this.setState({region: props.region});
		}
	}

	loadRegions(){
		let _this = this;
		this.setState({loading: true});
		$.ajax("get-regions-by-ids", {
            data: {
            	ids: _this.state.regionsIds,
            	abrangencia: _this.state.abrangencia
            },
            success: function(data){
                //console.log('regions', data);
                _this.setState({loading: false, regions: data});
            },
            error: function(data){
              console.log('erro');
            }
        })
	}

	selectRegion(id){
		this.props.selectRegion(id);
		this.setState({region: id});
	}

	render(){

		console.log(this.state.region);

		let regions = this.state.regions.map(function(item, index){

			let classBtn = item.id == this.state.region ? 'btn-info' : 'btn-default';

			return(
				<button key={'region_'+index} 
				className={"btn "+classBtn} 
				onClick={() => this.selectRegion(item.id)} 
				style={{margin: '0 5px 5px 0'}}>
					{item.sigla}
				</button>
			);
		}.bind(this));


		return(

			<div>
				{regions}
			</div>

		);
	}
}