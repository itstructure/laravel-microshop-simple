export function TopCardAdapter (top_card_component) {
    let t = this;

    t.top_card_component = top_card_component;

    t.putToCard = function(id, count) {
        axios.post('/put-to-card', {
            id: id,
            count: count !== undefined ? count : 1

        }).then(function (resp) {
            try {
                let data = resp.data;
                if (data.success == 1) {
                    t.setTotalAmount(data.total_amount);
                }

            } catch (e) {
                console.error(e);
            }
        }).catch(function (e) {
            console.error(e.response.data.message);
        });
    };

    t.setTotalAmount = function (total_amount) {
        t.top_card_component.setTotalAmount(total_amount);
    };
};
