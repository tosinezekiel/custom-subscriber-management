<template>
  <div>
    <InputLabel :for="id" :value="label" />
    <TextInput
      :id="id"
      type="number"
      class="mt-1 block w-full"
      :placeholder="placeholder"
      :modelValue="modelValue"
      @update:modelValue="handleInput"
      :step="step"
      :min="min"
      :max="max"
      :disabled="disabled"
    />
    <InputError v-if="error" class="mt-1" :message="error" />
  </div>
</template>

<script setup>
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
    type: [Number, String],
    default: ''
  },
  step: {
    type: String,
    default: 'any'
  },
  min: {
    type: [Number, String],
    default: undefined
  },
  max: {
    type: [Number, String],
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
const handleInput = (value) => {
  if (value === '') {
    emit('update:modelValue', '');
    return;
  }
  
  const parsedValue = parseFloat(value);
  emit('update:modelValue', isNaN(parsedValue) ? '' : parsedValue);
};
</script>