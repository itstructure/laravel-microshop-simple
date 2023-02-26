import './bootstrap';
import './top_card_adapter';

import React from 'react';
import ReactDOM from 'react-dom';
import TopCard from './components/TopCard';
import OrderCard from './components/OrderCard';

import Alpine from 'alpinejs';

document.addEventListener('DOMContentLoaded', (event) => {
    var id_top_card = document.getElementById('id_top_card');
    if (id_top_card && window.init_top_card_props) {
        ReactDOM.render(<TopCard  {...window.init_top_card_props} />, id_top_card);
    }

    var id_order_card = document.getElementById('id_order_card');
    if (id_order_card) {
        ReactDOM.render(<OrderCard  {...window.init_order_card_props} />, id_order_card);
    }

    window.top_card_adapter = new TopCardAdapter(window.top_card_component);
});

window.Alpine = Alpine;

Alpine.start();
