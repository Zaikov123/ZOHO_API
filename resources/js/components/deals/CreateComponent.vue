<template>
    <div class="container">
        <h2>Create Deal</h2>
        <form @submit.prevent="store.dispatch('deals/submitForm')">
            <div class="form-group">
                <label for="deal_name">Deal Name:</label>
                <input type="text" class="form-control" v-model="deal.deal_name"
                       :class="{ 'is-invalid': !validateDealName && submitted }" required/>
                <div class="invalid-feedback" v-if="submitted && !validateDealName">Deal Name is required</div>
            </div>
            <div class="form-group">
                <label for="deal_stage">Deal Stage:</label>
                <input type="text" class="form-control" v-model="deal.deal_stage"
                       :class="{ 'is-invalid': !validateDealStage && submitted }" required/>
                <div class="invalid-feedback" v-if="submitted && !validateDealStage">Deal Stage is required</div>
            </div>
            <div class="form-group">
                <label for="deal_stage">Account:</label>
                <select class="form-select" v-model="deal.account">
                    <option selected>Choose account</option>
                    <option v-for="account in accounts" :key="account.id" :value="account">{{ account.Account_Name }}</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" @click="submitted = true">Submit</button>
        </form>
        <div v-if="message" class="mt-3">{{ message }}</div>
    </div>

</template>

<script setup>

import {computed, onMounted, ref, watch} from "vue";
import {useStore} from "vuex";

const store = useStore();

const accounts = ref([]);
const deal = computed(() => store.getters['deals/deal']);
const message = computed(() => store.getters['deals/message']);
const submitted = computed(() => store.getters['deals/submitted']);
const validateDealName = computed(() => store.getters['deals/validateDealName']);
const validateDealStage = computed(() => store.getters['deals/validateDealStage']);

watch(() => message.value, (newVal) => {
    if (newVal) {
        setTimeout(() => {
            store.commit('deals/setMessage', '');
        }, 5000);
    }
});

watch(() => submitted.value, (newVal) => {
    if (newVal) {
        store.commit('deals/setSubmitted', false);
    }
});

onMounted(async () => {
    try {
        const response = await store.dispatch('account/getAccounts');
        accounts.value = response.data;
        console.log(accounts.value);
    } catch (error) {
        console.error('Error fetching accounts:', error);
    }
});
</script>

<style scoped>

</style>
