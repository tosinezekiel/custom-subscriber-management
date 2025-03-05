<script setup>
import { onMounted, ref, watch } from 'vue';

const model = defineModel();
const input = ref(null);

onMounted(() => {
  if (input.value && input.value.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value && input.value.focus() });
</script>

<template>
  <input
    class="rounded-md border-gray-300 font-light text-sm shadow-sm focus:border-green-500 focus:ring-green-500"
    :value="model"
    @input="model = $event.target.value"
    ref="input"
  />
</template>