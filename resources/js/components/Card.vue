<template>
    <div class="relative overflow-hidden rounded-lg bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6 cursor-pointer" @click="$emit('click')">
        <dt>
            <div class="absolute rounded-md bg-indigo-200 p-3">
                <component :is="icon" class="size-6 text-indigo-500" aria-hidden="true" />
            </div>
            <p class="ml-16 truncate text-sm font-medium text-gray-500">{{ name }}</p>
        </dt>
        <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
            <p class="text-2xl font-semibold text-gray-900">{{ stat }}</p>
            <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500" @click.prevent="$emit('click')">
                        View all<span class="sr-only"> {{ name }} stats</span>
                    </a>
                </div>
            </div>
        </dd>
    </div>
</template>

<script setup>
defineProps({
    name: {
        type: String,
        required: true
    },
    stat: {
        type: String,
        required: true
    },
    icon: {
        required: true
    }
});

defineEmits(['click']);
</script>