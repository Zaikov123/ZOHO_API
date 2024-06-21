import axios from "axios";

const state = {
    account: {
        account_name: '',
        account_website: '',
        account_phone: ''
    },
    submitted: false,
    message: ''
};

const getters = {
    account: state => state.account,
    message: state => state.message,
    submitted: state => state.submitted,
    validateAccountName(state){
      return state.account.account_name && state.account.account_name.trim() !== ''
    },
    validateAccountWebsite(state){
        if (!state.account.account_website) return false;
        const urlPattern = /^https?:\/\/\S+$/;
        return urlPattern.test(state.account.account_website.trim());
    },
    validateAccountPhone(state){
        if (!state.account.account_phone) return false;
        const phonePattern = /^\+?[0-9]{8,}$/;
        return phonePattern.test(state.account.account_phone.trim());
    },
};

const actions = {
    async submitForm({commit, dispatch, state}) {
        console.log('Submitting form with data:', {
            account: state.account
        });

        try {
            commit('setSubmitted', true);

            if (!dispatch('validateForm')) {
                console.log('Form validation failed');
                return;
            }

            console.log('Sending account data:', state.account);
            let accountResponse = await axios.post('/api/zoho/account', state.account);

            console.log('Account response:', accountResponse.data);

            if (accountResponse.data) {
                commit('setMessage', 'Records successfully created')
                commit('setAccount', {account_name: '', account_website: '', account_phone: ''})
            } else {
                commit('setMessage', 'Error creating records');
            }
        } catch (error) {
            console.error('Error creating records:', error);
            console.log('Error response:', error.response); // Log the response for detailed error information
            commit('setMessage', 'Error creating records');
        }
    },
    validateForm(getters){
        return (
            getters['account/validateAccountName'] && getters['account/validateAccountPhone'] && getters['account/validateAccountWebsite']
        );
    }

}

const mutations = {
    setAccount(state, account){
        state.account = account
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
