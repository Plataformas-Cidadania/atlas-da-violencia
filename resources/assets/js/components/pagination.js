class Pagination extends React.Component{
    constructor(props){
        super(props);

        //console.log('PROPS', props);

        this.state = {
            prev: props.prev ? props.prev : 'disable',
            next: props.next ? props.next : 'enable',
            currentPage: props.currentPage ? props.currentPage : 1,
            from: props.from ? props.from : 1,
            to: props.to ? props.to : 50,
            lastPage: props.lastPage ? props.lastPage : 10,
            perPage: props.perPage ? props.perPage : 10,
            total: props.total ? props.total : 50,
            countNumbers: props.countNumbers ? props.countNumbers : 10,
        };

        //console.log('STATE', this.state);

        this.setCurrentPage = this.setCurrentPage.bind(this);
    }

    componentWillReceiveProps(props){
        if(
            (this.state.prev !== props.prev && props.prev !== undefined) ||
            (this.state.next !== props.next && props.next !== undefined) ||
            (this.state.currentPage !== props.currentPage && props.currentPage !== undefined) ||
            (this.state.from !== props.from && props.from !== undefined) ||
            (this.state.to !== props.to && props.to !== undefined) ||
            (this.state.lastPage !== props.lastPage && props.lastPage !== undefined) ||
            (this.state.perPage !== props.perPage && props.perPage !== undefined) ||
            (this.state.total !== props.total && props.total !== undefined) ||
            (this.state.countNumbers !== props.countNumbers && props.countNumbers !== undefined)
        ){
            this.setState(
                {
                    prev: props.prev,
                    next: props.next,
                    currentPage: props.currentPage,
                    from: props.from,
                    to: props.to,
                    lastPage: props.lastPage,
                    perPage: props.perPage,
                    total: props.total,
                    countNumbers: props.countNumbers,
                }
            );
        }
    }

    setCurrentPage(page){


        this.setState({currentPage: page}, function(){
            this.props.setCurrentPage(page);
        });
    }

    render(){

        let numbers = [];
        let firstNumber = 1;
        let half = Math.floor(this.state.countNumbers  / 2);

        console.log(half);

        if(this.state.currentPage > half){
            firstNumber = this.state.currentPage - half;
        }

        if(firstNumber > 1){
            numbers.push(<li key='pg...start'><a href="">...</a></li>);
        }

        for(let i = firstNumber; i <= this.state.total; i++){
            let active = null;

            if(i==this.state.currentPage){
                active = 'active';
            }

            if(i==firstNumber+this.state.countNumbers){
                numbers.push(<li key='pg...end'><a href="">...</a></li>);
                break;
            }

            numbers.push(<li key={'pg'+i} className={active}><a href="" onClick={() => this.setCurrentPage(i)}>{i}</a></li>);
        }



        return(
            <nav aria-label="Page navigation">
                <ul className="pagination">
                    <li className={this.state.prev}>
                        <a href="" aria-label="Previous" onClick={() => this.setCurrentPage(this.state.currentPage-1)}>
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    {/*<li className="active"><a href="#">1</a></li>*/}
                    {numbers}
                    <li className={this.state.next} onClick={() => this.setCurrentPage(this.state.currentPage+1)}>
                        <a href="" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        );
    }
}