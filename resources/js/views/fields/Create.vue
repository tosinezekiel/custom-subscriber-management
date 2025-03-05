<template>
    <div>
        <h2 class="text-2xl font-bold mb-6">Add Field</h2>

        <div class="bg-white shadow rounded-lg p-6 w-1/2">
            <form @submit.prevent="saveField">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
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
                            id="employment-type"
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
                        Create Field
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/services/api';
import TextInput from '@/components/TextInput.vue';
import SelectInput from '@/components/SelectInput.vue';
import InputLabel from '@/components/InputLabel.vue';
import InputError from '@/components/InputError.vue';
import Button from '@/components/Button.vue';
import { useToast } from "vue-toastification";

const toast = useToast();

const router = useRouter();

const field = ref({
    title: '',
    type: ''
});

const loading = ref(false);
const errors = ref({});

const saveField = async () => {
    loading.value = true;
    errors.value = {};

    try {
        const { data, status } = await api.createField(field.value);
        if(status === 201) {
            toast.success(data.message);
            router.push('/fields');
        }
    } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
            errors.value = error.response.data.errors;
        } else {
            toast.error('Failed to save field');
        }
    } finally {
        loading.value = false;
    }
};
</script>
