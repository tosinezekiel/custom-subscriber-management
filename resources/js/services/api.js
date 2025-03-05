import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
});

export default {
    getSubscribers(params = {}) {
        return api.get('/subscribers', { params });
    },

    getSubscriber(id) {
        return api.get(`/subscribers/${id}`);
    },

    createSubscriber(subscriber) {
        return api.post('/subscribers', subscriber);
    },

    updateSubscriber(id, subscriber) {
        return api.put(`/subscribers/${id}`, subscriber);
    },

    deleteSubscriber(id) {
        return api.delete(`/subscribers/${id}`);
    },

    getFields(params = {}) {
        return api.get('/fields', { params });
    },

    getField(id) {
        return api.get(`/fields/${id}`);
    },

    createField(field) {
        return api.post('/fields', field);
    },

    updateField(id, field) {
        return api.put(`/fields/${id}`, field);
    },

    deleteField(id) {
        return api.delete(`/fields/${id}`);
    }
};
