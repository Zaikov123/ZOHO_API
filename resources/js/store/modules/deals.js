import {ref} from "vue";
import axios from "axios";
import router from "../../router.js";
const state = {
    deal: {
        deal_name: '',
        deal_stage: ''
    },
    submitted: false,
    message: ''
};

const getters = {
    deal: state => state.deal,
    message: state => state.message,
    submitted: state => state.submitted,
    validateDealName(state){
        return state.deal.deal_name && state.deal.deal_name.trim() !== ''
    },
    validateDealStage(state){
        return state.deal.deal_stage && state.deal.deal_stage.trim() !== ''
    },
};

const actions = {
    async submitForm({commit, dispatch, state}) {
        console.log('Submitting form with data:', {
            deal: state.deal,
        });

        try {
            commit('setSubmitted', true);

            if (!dispatch('validateForm')) {
                console.log('Form validation failed');
                return;
            }

            console.log('Sending deal data:', state.deal);
            let dealResponse = await axios.post('/api/zoho/deal', state.deal);

            console.log('Deal response:', dealResponse.data);

            if (dealResponse.data) {
                commit('setMessage', 'Records successfully created')
                commit('setDeal', { deal_name: '', deal_stage: '' })
                // await router.push({name: 'deal.index'})
            } else {
                commit('setMessage', 'Error creating records');
            }
        } catch (error) {
            console.error('Error creating records:', error);
            console.log('Error response:', error.response);
            commit('setMessage', 'Error creating records');
        }
    },
    async getDeals(){
        try {

            console.log('Collecting deal data:', state.deal);
            let getDealsResponse = await axios.get('/api/zoho/deal')
            console.log('Get deal response:', getDealsResponse.data);

            if (getDealsResponse.data) {
                return getDealsResponse.data;
            } else {
                console.error('Error getting deals');
            }
        }catch (error){
            console.error('Error getting records:', error);
            console.log('Error response:', error.response);
        }
    },
    validateForm(getters){
        return (
            getters["deals/validateDealName"] && getters["deals/validateDealStage"]
        );
    }
};

const mutations = {
    setDeal(state, deal){
        state.deal = deal
    },
    setSubmitted(state, submitted) {
        state.submitted = submitted;
    },
    setMessage(state, message) {
        state.message = message;
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
