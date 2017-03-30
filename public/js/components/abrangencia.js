class Abrangencia extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            options: [{ id: 1, title: 'País', on: false }, { id: 2, title: 'Região', on: false }, { id: 3, title: 'UF', on: true }, { id: 4, title: 'Município', on: false }]
        };

        this.check = this.check.bind(this);
    }

    check(id) {
        let options = this.state.options;
        options.find(function (item) {
            item.on = false;
            item.on = item.id === id;
        });

        this.setState({ options: options });
    }

    render() {

        let options = this.state.options.map(function (item) {
            return React.createElement(
                'div',
                { key: item.id, style: { float: 'left', marginRight: '20px', cursor: 'pointer' }, onClick: () => this.check(item.id) },
                React.createElement(
                    'div',
                    { style: { display: item.on ? 'block' : 'none' } },
                    React.createElement('img', { src: 'img/checkbox_on.png', alt: '' }),
                    ' ',
                    item.title
                ),
                React.createElement(
                    'div',
                    { style: { display: item.on ? 'none' : 'block' } },
                    React.createElement('img', { src: 'img/checkbox_off.png', alt: '' }),
                    ' ',
                    item.title
                )
            );
        }.bind(this));

        return React.createElement(
            'div',
            null,
            React.createElement(
                'h4',
                null,
                'Escolha a abrangencia'
            ),
            React.createElement('hr', null),
            options,
            React.createElement('div', { style: { clear: 'left' } }),
            React.createElement('br', null)
        );
    }

}