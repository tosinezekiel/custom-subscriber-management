<script setup>
defineProps({
    title: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg w-1/2">
        <div class="px-4 py-5 sm:px-6" v-if="title">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ title }}</h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <slot></slot>
        </div>
    </div>
</template>
