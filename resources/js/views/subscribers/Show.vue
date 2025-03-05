<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">Subscriber Details</h2>
            <div class="space-x-2">
                <Button variant="primary" @click="$router.push(`/subscribers/${id}/edit`)">
                    Edit
                </Button>
                <Button variant="secondary" @click="$router.push('/subscribers')">
                    Back
                </Button>
            </div>
        </div>

        <div v-if="loading" class="p-4 text-center">
            <div class="loader">Loading...</div>
        </div>

        <div v-else class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>

                        <div class="mb-4">
                            <InputLabel value="Email" />
                            <div class="mt-1 text-gray-900">{{ subscriber.email }}</div>
                        </div>

                        <div class="mb-4">
                            <InputLabel value="Name" />
                            <div class="mt-1 text-gray-900">{{ subscriber.name }}</div>
                        </div>

                        <div class="mb-4">
                            <InputLabel value="State" />
                            <div class="mt-1">
                                <span :class="getStateClass(subscriber.state)">
                                    {{ subscriber.state }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Custom Fields</h3>

                        <div v-if="fieldValues.length === 0" class="text-gray-500 italic">
                            No custom fields added yet
                        </div>

                        <div v-for="fieldValue in fieldValues" :key="fieldValue.id" class="mb-4">
                            <InputLabel :value="fieldValue.title" />
                            <div class="mt-1 text-gray-900">{{ formatFieldValue(fieldValue) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/services/api';
import InputLabel from '@/components/InputLabel.vue';
import Button from '@/components/Button.vue';

const route = useRoute();
const id = computed(() => route.params.id);

const subscriber = ref({
    email: '',
    name: '',
    state: '',
});

const fieldValues = ref([]);
const loading = ref(true);

const loadSubscriber = async () => {
    loading.value = true;
    try {
        const response = await api.getSubscriber(id.value);
        const data = response.data.data;

        subscriber.value = {
            email: data.email,
            name: data.name,
            state: data.state,
        };

        fieldValues.value = data?.fields || [];

    } catch (error) {
        console.error('Error loading subscriber:', error);
        alert('Failed to load subscriber data');
    } finally {
        loading.value = false;
    }
};

const getStateClass = (state) => {
    const classes = {
        'active': 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800',
        'unsubscribed': 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800',
        'junk': 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800',
        'bounced': 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800',
        'unconfirmed': 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800'
    };

    return classes[state] || classes['unconfirmed'];
};

const formatFieldValue = (fieldValue) => {
    if (fieldValue.type === 'boolean') {
        return fieldValue.value === 'true' ? 'Yes' : 'No';
    } else if (fieldValue.type === 'date') {
        const date = new Date(fieldValue.value);
        return date.toLocaleDateString();
    }
    return fieldValue.value;
};

onMounted(() => {
    loadSubscriber();
});
</script>
