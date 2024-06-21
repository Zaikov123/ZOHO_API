import './bootstrap';
import { createApp } from 'vue';
import Index from "./components/Index.vue";
import router from "./router.js";
import store from "./store/index.js";

const app = createApp(Index);

app.use(router);
app.use(store);

app.mount('#app');
