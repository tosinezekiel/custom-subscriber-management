import './bootstrap';
import { createApp } from 'vue';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import routes from './router';

const router = createRouter({
    history: createWebHistory(),
    routes: routes
});

const options = {
    transition: "Vue-Toastification__bounce",
    maxToasts: 3,
    newestOnTop: true
};

const app = createApp(App);
app.use(router);
app.use(Toast, options);
app.mount('#app');
