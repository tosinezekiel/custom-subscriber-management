<template>
    <div>
        <h2 class="text-2xl font-bold mb-6">Add Subscriber</h2>

        <FormSection title="Subscriber Information">
            <form @submit.prevent="saveSubscriber">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="col-span-2">
                        <StringInput
                            id="email"
                            label="Email Address"
                            placeholder="Enter email address"
                            v-model="subscriber.email"
                            :error="errors.email?.[0] || ''"
                        />
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <StringInput
                            id="name"
                            label="Name"
                            placeholder="Enter name"
                            v-model="subscriber.name"
                            :error="errors.name?.[0] || ''"
                        />
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <SelectInput
                            id="state"
                            class="mt-1 block w-full"
                            label="State"
                            v-model="subscriber.state"
                            :options="[
                                { value: 'active', label: 'Active' },
                                { value: 'unsubscribed', label: 'Unsubscribed' },
                                { value: 'junk', label: 'Junk' },
                                { value: 'bounced', label: 'Bounced' },
                                { value: 'unconfirmed', label: 'Unconfirmed' }
                            ]"
                            required
                        />
                    </div>

                    <div class="col-span-2">
                        <div class="mt-4 mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Others</h3>
                        </div>

                        <div v-if="availableFields.length === 0" class="text-gray-500 mb-4">
                            Loading fields...
                        </div>

                        <div v-else class="space-y-6">
                            <div v-for="field in availableFields" :key="field.id">
                                <DynamicFieldInput
                                    :field="field"
                                    v-model="getFieldValue(field.id).value"
                                    :error="getFieldError(field.id)"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 py-4 mt-6 flex justify-end space-x-3">
                    <Button type="button" variant="secondary" @click="$router.push('/subscribers')">Cancel</Button>
                    <Button type="submit" :disabled="loading">Create Subscriber</Button>
                </div>
            </form>
        </FormSection>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/services/api';
import SelectInput from '@/components/SelectInput.vue';
import StringInput from '@/components/StringInput.vue';
import DynamicFieldInput from '@/components/FieldType.vue';
import Button from '@/components/Button.vue';
import FormSection from '@/components/FormSection.vue';
import { useToast } from "vue-toastification";

const toast = useToast();
const router = useRouter();

const subscriber = ref({
    email: '',
    name: '',
    state: 'active',
    fields: []
});

const availableFields = ref([]);
const loading = ref(false);
const errors = ref({});

const loadFields = async () => {
    try {
        const { data } = await api.getFields();
        availableFields.value = data.data;
    } catch (error) {
        console.error('Error loading fields:', error);
    }
};

const getFieldValue = (fieldId) => {
    let fieldValue = subscriber.value.fields.find(fv => fv.field_id === fieldId);
    if (!fieldValue) {
        fieldValue = { field_id: fieldId, value: '' };
        subscriber.value.fields.push(fieldValue);
    }
    return fieldValue;
};

const getFieldError = (fieldId) => {
    return errors.value[`fields.${fieldId}`]?.[0] || '';
};

const saveSubscriber = async () => {
    loading.value = true;
    errors.value = {};
    
    availableFields.value.forEach(field => {
        if (field.type === 'boolean') {
            const existingField = subscriber.value.fields.find(f => f.field_id === field.id);
            if (!existingField) {
                subscriber.value.fields.push({ field_id: field.id, value: '0' });
            } else {
                existingField.value = existingField.value ? '1' : '0';
            }
        }
    });
    
    subscriber.value.fields = subscriber.value.fields.filter(fv => {
        const field = availableFields.value.find(f => f.id === fv.field_id);
        if (field && field.type === 'boolean') {
            return true; 
        }
        
        return fv.value !== null && fv.value !== undefined && String(fv.value).trim() !== '';
    });
    
    try {
        const { data, status } = await api.createSubscriber(subscriber.value);
        if(status === 201) {
            toast.success(data.message);
            router.push('/subscribers');
        }
    } catch (error) {
        toast.error('Error creating subscriber:');
        console.log(error)
        errors.value = error.response?.data?.errors || {};
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadFields();
});
</script>