import React, {Component} from 'react';

class OrderCard extends Component {
    constructor(props,context) {
        super(props,context);

        this.setCountInCard = this.setCountInCard.bind(this);
        this.removeFromCard = this.removeFromCard.bind(this);
        this.handleUserInput = this.handleUserInput.bind(this);
        this.sendOrder = this.sendOrder.bind(this);

        this.state = {
            total_amount: this.props.totalAmount !== undefined ? this.props.totalAmount : 0,
            card_products: this.props.cardProducts !== undefined ? this.props.cardProducts : {},
            card_counts: this.props.cardCounts !== undefined ? this.props.cardCounts : {},
            user_name: '',
            user_email: '',
            user_comment: '',
            order_sent: false
        };
    }

    setCountInCard(count, id) {
        let t = this;
        axios.post('/set-count-in-card', {
            id: id,
            count: count !== undefined ? count : 1

        }).then(function (resp) {
            try {
                var data = resp.data;
                if (data.success == 1) {
                    window.top_card_component.setTotalAmount(data.total_amount);

                    var card_counts = t.state.card_counts;
                    card_counts[id] = count;
                    var state = t.state;
                    state.total_amount = data.total_amount;
                    state.card_counts = card_counts;
                    state.card_products[id].price = data.item_price;
                    t.setState(state);
                }

            } catch (e) {
                console.error(e);
            }
        }).catch(function (e) {
            console.error(e.response.data.message);
        });
    }

    removeFromCard(id) {
        let t = this;
        axios.post('/remove-from-card', {
            id: id

        }).then(function (resp) {
            try {
                var data = resp.data;
                if (data.success == 1) {
                    window.top_card_component.setTotalAmount(data.total_amount);

                    var card_counts = t.state.card_counts;
                    delete card_counts[id];
                    var state = t.state;
                    state.total_amount = data.total_amount;
                    state.card_counts = card_counts;
                    t.setState(state);
                }

            } catch (e) {
                console.error(e);
            }
        }).catch(function (e) {
            console.error(e.response.data.message);
        });
    }

    handleUserInput(e) {
        let name = e.target.name;
        let value = e.target.value;
        this.setState({[name]: value});
    };

    sendOrder() {
        let t = this;
        axios.post('/send-order', {
            card_counts: t.state.card_counts,
            user_name: t.state.user_name,
            user_email: t.state.user_email,
            user_comment: t.state.user_comment

        }).then(function (resp) {
            try {
                let data = resp.data;
                if (data.success == 1) {
                    window.top_card_component.setTotalAmount(0);

                    t.setState({
                        total_amount: 0,
                        card_products: {},
                        card_counts: {},
                        user_name: '',
                        user_email: '',
                        user_comment: '',
                        order_sent: true
                    });
                }

            } catch (e) {
                console.error(e);
            }
        }).catch(function (e) {
            console.error(e.response.data.message);
            if (e.response.data.errors !== undefined && Object.keys(e.response.data.errors).length) {
                let userInputs = $('#user_inputs');

                userInputs.find('.form-group').each(function () {
                    let roleGroup = $(this).attr('role');
                    let inputField = $(this).find('[name="'+roleGroup+'"]');
                    let helpBlock = $(this).find('#help_block_'+roleGroup);

                    if (roleGroup in e.response.data.errors) {
                        if (!inputField.hasClass('is-invalid')) {
                            inputField.addClass('is-invalid');
                        }
                        if (helpBlock.hasClass('d-none')) {
                            helpBlock.removeClass('d-none');
                        }
                        helpBlock.text(e.response.data.errors[roleGroup][0]);

                    } else {
                        if (inputField.hasClass('is-invalid')) {
                            inputField.removeClass('is-invalid');
                        }
                        if (!helpBlock.hasClass('d-none')) {
                            helpBlock.addClass('d-none');
                        }
                        helpBlock.text('');
                    }
                });
            }
        });
    }

    getUserInputs() {
        return (
            <div className="row mt-3">
                <div id="user_inputs" className="col-12 offset-lg-2 col-lg-8 text-center">
                    <div role="user_name" className="form-group mb-4">
                        <input type="text" name="user_name" className="form-control" placeholder="Your name" defaultValue={this.state.user_name} onChange={(e) => this.handleUserInput(e)} />
                        <div id="help_block_user_name" className="invalid-feedback d-none"></div>
                    </div>
                    <div role="user_email" className="form-group mb-4">
                        <input type="text" name="user_email" className="form-control" placeholder="Email address" defaultValue={this.state.user_email} onChange={(e) => this.handleUserInput(e)} />
                        <div id="help_block_user_email" className="invalid-feedback d-none"></div>
                    </div>
                    <div role="user_comment" className="form-group mb-4">
                        <textarea name="user_comment" className="form-control" placeholder="Comment" defaultValue={this.state.user_comment} onChange={(e) => this.handleUserInput(e)}/>
                        <div id="help_block_user_comment" className="invalid-feedback d-none"></div>
                    </div>
                    <button type="button" className="btn btn-primary" onClick={() => this.sendOrder()}>Submit</button>
                </div>
            </div>
        );
    }

    render() {
        let rows = [];
        for (let id in this.state.card_counts) {
            rows.push(
                <tr key={id}>
                    <th scope="row">
                        {id}
                    </th>
                    <td>
                        <div className="product-logo mini">
                            <a href={"/product/"+(this.state.card_products[id].alias)}>
                                <img src={"/images/product"+(this.state.card_products[id].category_id)+".jpg"} />
                            </a>
                        </div>
                    </td>
                    <td>
                        {this.state.card_products[id].title}
                    </td>
                    <td>
                        {this.state.card_products[id].price}
                    </td>
                    <td>
                        <input className="product-count" type="number" value={this.state.card_counts[id]} min="1" onChange={(event) => this.setCountInCard(event.target.value, id)} />
                    </td>
                    <td>
                        {parseFloat(this.state.card_products[id].price * this.state.card_counts[id])}
                    </td>
                    <td>
                        <button onClick={() => this.removeFromCard(id)}>X</button>
                    </td>
                </tr>
            );
        }

        return Object.keys(this.state.card_counts).length ? (
            <div>
                <div className="row m-0">
                    <div className="col-12 table-responsive">
                        <table className="table order-table">
                            <thead className="thead-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Picture</th>
                                <th scope="col">Title</th>
                                <th scope="col">Price, $</th>
                                <th scope="col">Count</th>
                                <th scope="col">Amount, $</th>
                                <th scope="col">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            {rows}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div className="row">
                    <div className="col-12 text-right">
                        <div><strong>TOTAL AMOUNT: ${this.state.total_amount}</strong></div>
                    </div>
                </div>
                {
                    this.getUserInputs()
                }
            </div>
        ) : (
            <div className={"alert " + (this.state.order_sent ? "alert-success" : "alert-danger")}>
                {this.state.order_sent ? "Your order has been sent" : "Card is empty"}
            </div>
        );
    }
}

export default OrderCard;
