<template>
    <div>
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <Card
                name="Total Subscribers"
                :stat="stats.subscriberCount.toString()"
                :icon="UserGroupIcon"
                @click="$router.push('/subscribers')"
            />
            
            <Card
                name="Custom Fields"
                :stat="stats.fieldCount.toString()"
                :icon="TagIcon"
                @click="$router.push('/fields')"
            />
            
            <div class="relative overflow-hidden rounded-lg bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6">
                <dt>
                    <div class="absolute rounded-md bg-indigo-200 p-3">
                        <UserPlusIcon class="size-6 text-indigo-500" aria-hidden="true" />
                    </div>
                    <p class="ml-16 truncate text-sm font-medium text-gray-500">Add New Subscriber</p>
                </dt>
                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                    <p class="text-2xl font-semibold text-gray-900">Create</p>
                    <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-4 py-4 sm:px-6">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500" @click.prevent="$router.push('/subscribers/create')">
                                Create new subscriber
                            </a>
                        </div>
                    </div>
                </dd>
            </div>
        </dl>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { UserGroupIcon, TagIcon, UserPlusIcon } from '@heroicons/vue/24/outline';
import api from '@/services/api';
import Card from '@/components/Card.vue';

const stats = ref({
    subscriberCount: 0,
    fieldCount: 0
});

onMounted(async () => {
    try {
        const [subscribersResponse, fieldsResponse] = await Promise.all([
            api.getSubscribers(),
            api.getFields()
        ]);

        const subscribers = subscribersResponse.data.data || [];
        const fields = fieldsResponse.data.data || [];

        stats.value = {
            subscriberCount: subscribers?.length,
            fieldCount: fields?.length
        };
    } catch (error) {
        console.error('Error loading dashboard data:', error);
    }
});
</script>