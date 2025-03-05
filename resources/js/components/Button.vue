<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    variant: {
        type: String,
        default: 'primary', 
    },
});
</script>

<template>
    <button
        :type="type"
        :class="[
            'inline-flex items-center px-4 py-2 border rounded-md font-medium text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150',
            variant === 'primary'
                ? 'border-transparent text-white bg-green-600 hover:bg-green-700 focus:ring-green-500'
                : variant === 'secondary'
                    ? 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-green-500'
                    : 'border-transparent text-white bg-red-600 hover:bg-red-700 focus:ring-red-500'
        ]"
    >
        <slot></slot>
    </button>
</template>
