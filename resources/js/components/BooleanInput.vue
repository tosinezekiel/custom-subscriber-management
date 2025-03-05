<template>
  <div class="flex items-center">
    <div class="flex items-center h-5">
      <input
        :id="id"
        type="checkbox"
        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
        :checked="modelValue === true"
        @change="handleChange"
        :disabled="disabled"
      />
    </div>
    <div class="ml-3 text-sm">
      <label :for="id" class="font-medium text-gray-700">{{ label }}</label>
      <p v-if="description" class="text-gray-500">{{ description }}</p>
      <InputError v-if="error" class="mt-1" :message="error" />
    </div>
  </div>
</template>

<script setup>
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
  description: {
    type: String,
    default: ''
  },
  modelValue: {
    type: Boolean,
    default: false
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

const handleChange = (event) => {
  emit('update:modelValue', event.target.checked);
};
</script>