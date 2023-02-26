import React, {Component} from 'react';

class TopCard extends Component {
    constructor(props,context) {
        super(props,context);

        this.setTotalAmount = this.setTotalAmount.bind(this);

        this.state = {
            total_amount: this.props.totalAmount !== undefined ? this.props.totalAmount : 0
        };

        window.top_card_component = this;
    }

    setTotalAmount(total_amount) {
        let state = this.state;
        state.total_amount = total_amount;
        this.setState(state);
    }

    render() {
        return (
            <a href="/card" className="top-card-link">
                <div className="top-card-block">
                    <div className="top-card-icon" />
                    <div className="top-card-amount">
                        $ {this.state.total_amount}
                    </div>
                </div>
            </a>
        );
    }
}

export default TopCard;
