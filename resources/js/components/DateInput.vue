<template>
  <div>
    <InputLabel :for="id" :value="label" />
    <TextInput
      :id="id"
      type="date"
      class="mt-1 block w-full"
      :placeholder="placeholder"
      :modelValue="formattedValue"
      @update:modelValue="handleDateInput"
      :min="min"
      :max="max"
      :disabled="disabled"
    />
    <InputError v-if="error" class="mt-1" :message="error" />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import TextInput from '@/components/TextInput.vue';
import InputLabel from '@/components/InputLabel.vue';
import InputError from '@/components/InputError.vue';

const props = defineProps({
  id: {
    type: String,
    required: true
  },
  label: {
    type: String,
    required: true
  },
  placeholder: {
    type: String,
    default: ''
  },
  modelValue: {
    type: String,
    default: ''
  },
  min: {
    type: String,
    default: undefined
  },
  max: {
    type: String,
    default: undefined
  },
  error: {
    type: String,
    default: ''
  },
  disabled: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:modelValue']);

const formattedValue = computed(() => {
  if (!props.modelValue) return '';
  
  if (/^\d{4}-\d{2}-\d{2}$/.test(props.modelValue)) {
    return props.modelValue;
  }
  
  try {
    const date = new Date(props.modelValue);
    if (isNaN(date.getTime())) return '';
    
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    
    return `${year}-${month}-${day}`;
  } catch (e) {
    return '';
  }
});

const handleDateInput = (value) => {
  if (!value) {
    emit('update:modelValue', '');
    return;
  }
  
  try {
    const date = new Date(value);
    if (isNaN(date.getTime())) {
      emit('update:modelValue', '');
      return;
    }
    
    emit('update:modelValue', date.toISOString().split('T')[0]);
  } catch (e) {
    emit('update:modelValue', '');
  }
};
</script>