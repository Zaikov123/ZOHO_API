<template>
<div>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Stage</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="deal in deals" :key="deal.id">
            <td>{{ deal.Deal_Name }}</td>
            <td>{{ deal.Stage }}</td>
        </tr>
        </tbody>
    </table>
</div>
</template>

<script setup>
import {useStore} from "vuex";
import {onMounted, ref} from "vue";

const store = useStore();
const deals = ref([]);

onMounted(async () => {
    try {
        const response = await store.dispatch('deals/getDeals');
        deals.value = response.data;
    } catch (error) {
        console.error('Error fetching deals:', error);
    }
});
</script>

<style scoped>



</style>
