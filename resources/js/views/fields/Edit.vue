<template>
    <div>
        <h2 class="text-2xl font-bold mb-6">Edit Field</h2>

        <div class="bg-white shadow rounded-lg p-6">
            <form @submit.prevent="saveField">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <InputLabel for="title" value="Field Title" />
                        <TextInput
                            id="title"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="field.title"
                            required
                        />
                        <InputError class="mt-2" :message="errors.title ? errors.title[0] : ''" />
                    </div>

                    <div class="sm:col-span-4">
                        <SelectInput
                            id="field-type"
                            label="Field type"
                            v-model="field.type"
                            :options="[
                                { value: 'string', label: 'String' },
                                { value: 'number', label: 'Number' },
                                { value: 'boolean', label: 'Boolean' },
                                { value: 'date', label: 'Date' }
                            ]"
                            :error="errors.type"
                            required
                        />

                        <div class="mt-2 text-sm text-gray-600">
                            <p v-if="field.type === 'string'">Text values like names, addresses, etc.</p>
                            <p v-else-if="field.type === 'number'">Numeric values like age, count, etc.</p>
                            <p v-else-if="field.type === 'date'">Date values like birthday, anniversary, etc.</p>
                            <p v-else-if="field.type === 'boolean'">Yes/No values.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <Button type="button" variant="secondary" @click="$router.push('/fields')">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="loading">
                        Update Field
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import api from '@/services/api';
import TextInput from '@/components/TextInput.vue';
import SelectInput from '@/components/SelectInput.vue';
import InputLabel from '@/components/InputLabel.vue';
import InputError from '@/components/InputError.vue';
import Button from '@/components/Button.vue';
import { useToast } from "vue-toastification";

const toast = useToast();
const router = useRouter();
const route = useRoute();
const id = route.params.id;

const field = ref({
    title: '',
    type: ''
});

const loading = ref(false);
const errors = ref({});

const loadField = async () => {
    loading.value = true;
    try {
        const response = await api.getField(id);
        const data = response.data.data;

        field.value = {
            title: data.title,
            type: data.type
        };

    } catch (error) {
        toast.error('Failed to load field data');
        console.error('Error loading field:', error);
        router.push('/fields');
    } finally {
        loading.value = false;
    }
};

const saveField = async () => {
    loading.value = true;
    errors.value = {};

    try {
        const { data, status } = await api.updateField(id, field.value);
        if(status === 200) {
            toast.success(data.message);
            router.push('/fields');
        }
    } catch (error) {
        toast.error('Error saving field');

        if (error.response && error.response.data && error.response.data.errors) {
            errors.value = error.response.data.errors;
        } else {
            toast.error('Failed to save field');
        }
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadField();
});
</script>
