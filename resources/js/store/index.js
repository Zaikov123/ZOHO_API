import { createStore } from 'vuex';
import deals from './modules/deals.js';
import account from './modules/accounts.js';

const store = createStore({
    modules: {
        deals,
        account
    }
});

export default store;
