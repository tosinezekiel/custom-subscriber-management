<template>
  <div>
    <component 
      :is="getComponentForFieldType(field.type)"
      :id="`field-${field.id}`"
      :label="field.title"
      :placeholder="`Enter ${field.title}`"
      :description="field.type === 'boolean' ? `Toggle ${field.title}` : undefined"
      v-model="localValue"
      :error="error"
      :disabled="disabled"
    />
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import StringInput from '@/components/StringInput.vue';
import NumberInput from '@/components/NumberInput.vue';
import DateInput from '@/components/DateInput.vue';
import BooleanInput from '@/components/BooleanInput.vue';

const props = defineProps({
  field: {
    type: Object,
    required: true,
    validator: (field) => {
      return field.id !== undefined && 
             field.title !== undefined && 
             field.type !== undefined;
    }
  },
  modelValue: {
    type: [String, Number, Boolean],
    default: ''
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

const fieldTypeToComponent = {
  'string': StringInput,
  'number': NumberInput,
  'date': DateInput,
  'boolean': BooleanInput
};

const getComponentForFieldType = (type) => {
  return fieldTypeToComponent[type] || StringInput;
};

const localValue = ref(formatValueForFieldType(props.modelValue, props.field.type));


watch(() => props.modelValue, (newValue) => {
  localValue.value = formatValueForFieldType(newValue, props.field.type);
});


watch(localValue, (newValue) => {
  emit('update:modelValue', newValue);
});


function formatValueForFieldType(value, fieldType) {
  if (value === null || value === undefined) {
    switch (fieldType) {
      case 'number': return '';
      case 'boolean': return false;
      case 'date': return '';
      default: return '';
    }
  }
  
  switch (fieldType) {
    case 'number':
      return value === '' ? '' : Number(value);
    case 'boolean':
      if (typeof value === 'boolean') return value;
      if (typeof value === 'string') {
        return value.toLowerCase() === 'true' || value === '1';
      }
      return Boolean(value);
    case 'date':
      if (!value) return '';
      try {
        if (!/^\d{4}-\d{2}-\d{2}$/.test(value)) {
          const date = new Date(value);
          if (!isNaN(date.getTime())) {
            return date.toISOString().split('T')[0];
          }
        }
        return value;
      } catch (e) {
        return '';
      }
    default:
      return String(value);
  }
}
</script>