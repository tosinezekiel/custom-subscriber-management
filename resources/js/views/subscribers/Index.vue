<template>
    <div>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Subscribers</h2>
            <Button @click="$router.push('/subscribers/create')">
                Add Subscriber
            </Button>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-4 border-b">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <InputLabel
                            for="search"
                            value="Search"
                            class="sr-only"
                        />
                        <TextInput
                            id="search"
                            type="text"
                            class="block w-full"
                            v-model="filters.search"
                            placeholder="Search by email or name..."
                            @input="debouncedSearch"
                        />
                    </div>
                    <div>
                        <SelectInput
                            id="filed-type"
                            @change="debouncedState"
                            label=""
                            v-model="filters.state"
                            :options="[
                                { value: 'active', label: 'Active' },
                                {
                                    value: 'unsubscribed',
                                    label: 'Unsubscribed',
                                },
                                { value: 'junk', label: 'Junk' },
                                { value: 'bounced', label: 'Bounced' },
                                { value: 'unconfirmed', label: 'Unconfirmed' },
                            ]"
                            :error="filters.errors.employment_type"
                            required
                        />
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Email
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Name
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                State
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="subscriber in subscribers"
                            :key="subscriber.id"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ subscriber.email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ subscriber.name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getStateClass(subscriber.state)">
                                    {{ subscriber.state }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                            >
                                <router-link
                                    :to="`/subscribers/${subscriber.id}`"
                                    class="text-blue-600 hover:text-blue-900 mr-3"
                                >
                                    View
                                </router-link>
                                <router-link
                                    :to="`/subscribers/${subscriber.id}/edit`"
                                    class="text-green-600 hover:text-green-900 mr-3"
                                >
                                    Edit
                                </router-link>
                                <button
                                    @click="deleteSubscriber(subscriber)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-if="subscribers.length === 0">
                            <td
                                colspan="4"
                                class="px-6 py-4 text-center text-sm text-gray-500"
                            >
                                No subscribers found
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="loading" class="flex justify-center p-4">
                <div class="loader">Loading...</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";
import TextInput from "@/components/TextInput.vue";
import SelectInput from "@/components/SelectInput.vue";
import InputLabel from "@/components/InputLabel.vue";
import Button from "@/components/Button.vue";
import { useToast } from "vue-toastification";

const toast = useToast();

const subscribers = ref([]);
const loading = ref(false);
const filters = ref({
    search: "",
    state: "",
    errors: {},
});

let searchTimeout;
const debouncedSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadSubscribers();
    }, 300);
};

let stateTimeout;
const debouncedState = () => {
    clearTimeout(stateTimeout);
    stateTimeout = setTimeout(() => {
        loadSubscribers();
    }, 300);
};

const loadSubscribers = async () => {
    loading.value = true;
    try {
        const response = await api.getSubscribers({
            search: filters.value.search,
            state: filters.value.state,
        });
        subscribers.value = response.data.data;
    } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
            filters.value.errors = error.response.data.errors;
        } else {
            filters.value.errors.general = "Failed to load subscribers. Please try again.";
        }
    } finally {
        loading.value = false;
    }
};

const deleteSubscriber = async (subscriber) => {
    if (!confirm(`Are you sure you want to delete ${subscriber.name}?`)) {
        return;
    }

    try {
        const {status} = await api.deleteSubscriber(subscriber.id);
        if(status === 204) {
            toast.success("Subscriber deleted successfully.");
            subscribers.value = subscribers.value.filter(
                (s) => s.id !== subscriber.id
            );
        }
    } catch (error) {
        console.error("Error deleting subscriber:", error);
        toast.error("Failed to delete subscriber");
    }
};

const getStateClass = (state) => {
    const classes = {
        active: "px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800",
        unsubscribed:
            "px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800",
        junk: "px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800",
        bounced:
            "px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800",
        unconfirmed:
            "px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800",
    };

    return classes[state] || classes["unconfirmed"];
};

onMounted(() => {
    loadSubscribers();
});
</script>
