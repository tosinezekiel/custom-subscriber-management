<template>
    <div>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Custom Fields</h2>
            <Button @click="$router.push('/fields/create')">
                Add Field
            </Button>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Filters -->
            <div class="p-4 border-b">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <InputLabel for="search" value="Search" class="sr-only" />
                        <TextInput
                            id="search"
                            type="text"
                            class="block w-full"
                            v-model="filters.search"
                            placeholder="Search by field title..."
                            @input="debouncedSearch"
                        />
                    </div>
                    <div>
                        <SelectInput
                            id="filter-type"
                            @change="loadFields"
                            label=""
                            v-model="filters.type"
                            :options="[
                                { value: 'string', label: 'String' },
                                { value: 'number', label: 'Number' },
                                { value: 'boolean', label: 'Boolean' },
                                { value: 'date', label: 'Date' }
                            ]"
                            :error="errors.type"
                            required
                        />
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Title
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="field in fields" :key="field.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ field.title }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ field.type }}
                                </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <router-link :to="`/fields/${field.id}/edit`" class="text-green-600 hover:text-green-900 mr-3">
                                Edit
                            </router-link>
                            <button @click="deleteField(field)" class="text-red-600 hover:text-red-900">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <tr v-if="fields.length === 0">
                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                            No fields found
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="loading" class="flex justify-center p-4">
                <div class="loader">Loading...</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import TextInput from '@/components/TextInput.vue';
import SelectInput from '@/components/SelectInput.vue';
import InputLabel from '@/components/InputLabel.vue';
import Button from '@/components/Button.vue';
import { useToast } from "vue-toastification";

const toast = useToast();

const fields = ref([]);
const loading = ref(false);
const errors = ref({});
const filters = ref({
    search: '',
    type: ''
});

let searchTimeout;
const debouncedSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadFields();
    }, 300);
};

const loadFields = async () => {
    loading.value = true;
    try {
        const response = await api.getFields({
            search: filters.value.search,
            type: filters.value.type
        });
        fields.value = response.data.data;
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

const deleteField = async (field) => {
    if (!confirm(`Are you sure you want to delete the field "${field.title}"?`)) {
        return;
    }

    try {
        const {status} = await api.deleteField(field.id);
        if(status === 204) {
            toast.success("Field deleted successfully.");
            fields.value = fields.value.filter(f => f.id !== field.id);
        }
    } catch (error) {
        console.error('Error deleting field:', error);
        toast.error('Error deleting field:');
        if (error.response && error.response.data && error.response.data.message) {
            toast.error(error.response.data.message);
        } else {
            toast.error('Failed to delete field');
        }
    }
};

onMounted(() => {
    loadFields();
});
</script>
