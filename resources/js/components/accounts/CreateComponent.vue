<template>
    <div class="container">
        <h2>Create Account</h2>
        <form @submit.prevent="store.dispatch('account/submitForm')">
            <div class="form-group">
                <label for="account_name">Account Name:</label>
                <input type="text" class="form-control" v-model="account.account_name" :class="{ 'is-invalid': !validateAccountName && submitted }" required/>
                <div class="invalid-feedback" v-if="submitted && !validateAccountName">Account Name is required</div>
            </div>
            <div class="form-group">
                <label for="account_website">Account Website:</label>
                <input type="url" class="form-control" v-model="account.account_website" :class="{ 'is-invalid': !validateAccountWebsite && submitted }" required/>
                <div class="invalid-feedback" v-if="submitted && !validateAccountWebsite">Valid Account Website URL is required</div>
            </div>
            <div class="form-group">
                <label for="account_phone">Account Phone:</label>
                <input type="tel" class="form-control" v-model="account.account_phone" :class="{ 'is-invalid': !validateAccountPhone && submitted }" required/>
                <div class="invalid-feedback" v-if="submitted && !validateAccountPhone">Valid Account Phone number is required</div>
            </div>
            <button type="submit" class="btn btn-primary" @click="submitted = true">Submit</button>
        </form>
        <div v-if="message" class="mt-3">{{ message }}</div>
    </div>

</template>

<script setup>
import {computed, watch} from "vue";
import {useStore} from "vuex";

const store = useStore();

const account = computed(() => store.getters['account/account'])
const message = computed(() => store.getters['account/message'])
const submitted = computed(() => store.getters['account/submitted'])
const validateAccountName = computed(() => store.getters['account/validateAccountName']);
const validateAccountWebsite = computed(() => store.getters['account/validateAccountWebsite']);
const validateAccountPhone = computed(() => store.getters['account/validateAccountPhone']);

watch(() => message.value, (newVal) => {
    if (newVal) {
        setTimeout(() => {
            store.commit('account/setMessage', '');
        }, 5000);
    }
});

watch(() => submitted.value, (newVal) => {
    if (newVal) {
        store.commit('account/setSubmitted', false);
    }
});
</script>


<style scoped>

</style>
