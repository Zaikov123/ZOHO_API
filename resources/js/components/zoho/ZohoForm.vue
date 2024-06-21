<template>
    <div class="container">
        <h1>Create Deal and Account in Zoho CRM</h1>
        <form @submit.prevent="submitForm">
            <div class="form-group">
                <label for="deal_name">Deal Name:</label>
                <input type="text" class="form-control" v-model="deal.deal_name" :class="{ 'is-invalid': !validateDealName() && submitted }" required/>
                <div class="invalid-feedback" v-if="submitted && !validateDealName()">Deal Name is required</div>
            </div>
            <div class="form-group">
                <label for="deal_stage">Deal Stage:</label>
                <input type="text" class="form-control" v-model="deal.deal_stage" :class="{ 'is-invalid': !validateDealStage() && submitted }" required/>
                <div class="invalid-feedback" v-if="submitted && !validateDealStage()">Deal Stage is required</div>
            </div>
            <div class="form-group">
                <label for="account_name">Account Name:</label>
                <input type="text" class="form-control" v-model="account.account_name" :class="{ 'is-invalid': !validateAccountName() && submitted }" required/>
                <div class="invalid-feedback" v-if="submitted && !validateAccountName()">Account Name is required</div>
            </div>
            <div class="form-group">
                <label for="account_website">Account Website:</label>
                <input type="url" class="form-control" v-model="account.account_website" :class="{ 'is-invalid': !validateAccountWebsite() && submitted }" required/>
                <div class="invalid-feedback" v-if="submitted && !validateAccountWebsite()">Valid Account Website URL is required</div>
            </div>
            <div class="form-group">
                <label for="account_phone">Account Phone:</label>
                <input type="tel" class="form-control" v-model="account.account_phone" :class="{ 'is-invalid': !validateAccountPhone() && submitted }" required/>
                <div class="invalid-feedback" v-if="submitted && !validateAccountPhone()">Valid Account Phone number is required</div>
            </div>
            <button type="submit" class="btn btn-primary" @click="submitted = true">Submit</button>
        </form>
        <div v-if="message" class="mt-3">{{ message }}</div>
    </div>

</template>

<script setup>
import axios from 'axios';
import { ref } from "vue";

axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const deal = ref({
    deal_name: '',
    deal_stage: ''
});

const account = ref({
    account_name: '',
    account_website: '',
    account_phone: ''
});

const message = ref('');
const submitted = ref(false);

const submitForm = async () => {
    console.log('Submitting form with data:', {
        deal: deal.value,
        account: account.value
    });

    try {
        submitted.value = true;

        if (!validateForm()) {
            console.log('Form validation failed');
            return;
        }

        console.log('Sending deal data:', deal.value);
        let dealResponse = await axios.post('/api/zoho/deal', deal.value);

        console.log('Sending account data:', account.value);
        let accountResponse = await axios.post('/api/zoho/account', account.value);

        console.log('Deal response:', dealResponse.data);
        console.log('Account response:', accountResponse.data);

        if (dealResponse.data && accountResponse.data) {
            message.value = 'Records successfully created';
            deal.value.deal_name = null;
            deal.value.deal_stage = null;
            account.value.account_name = null;
            account.value.account_website = null;
            account.value.account_phone = null;
        } else {
            message.value = 'Error creating records';
        }
    } catch (error) {
        console.error('Error creating records:', error);
        console.log('Error response:', error.response); // Log the response for detailed error information
        message.value = 'Error creating records';
    }
};


// Validation functions
const validateDealName = () => deal.value.deal_name && deal.value.deal_name.trim() !== '';
const validateDealStage = () => deal.value.deal_stage && deal.value.deal_stage.trim() !== '';
const validateAccountName = () => account.value.account_name && account.value.account_name.trim() !== '';
const validateAccountWebsite = () => {
    if (!account.value.account_website) return false;
    const urlPattern = /^https?:\/\/\S+$/;
    return urlPattern.test(account.value.account_website.trim());
};
const validateAccountPhone = () => {
    if (!account.value.account_phone) return false;
    const phonePattern = /^\+?[0-9]{8,}$/; // Adjust this pattern based on your phone number format
    return phonePattern.test(account.value.account_phone.trim());
};


// Overall form validation
const validateForm = () => {
    return (
        validateDealName() &&
        validateDealStage() &&
        validateAccountName() &&
        validateAccountWebsite() &&
        validateAccountPhone()
    );
};
</script>

<style scoped>

</style>
