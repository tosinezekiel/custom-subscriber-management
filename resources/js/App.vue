<template>
    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-xl font-bold text-green-600">
                                MailerLite Subscriber Management
                            </h1>
                        </div>
                        <nav class="ml-10 flex items-baseline space-x-4">
                            <router-link
                                to="/"
                                class="px-3 py-2 rounded-md text-sm font-medium hover:text-green-600"
                                :class="[$route.path === '/' ? 'text-green-600' : 'text-gray-700']"
                                exact
                            >
                                Dashboard
                            </router-link>

                            <router-link
                                to="/subscribers"
                                class="px-3 py-2 rounded-md text-sm font-medium hover:text-green-600"
                                :class="[$route.path.startsWith('/subscribers') ? 'text-green-600' : 'text-gray-700']"
                            >
                                Subscribers
                            </router-link>

                            <router-link
                                to="/fields"
                                class="px-3 py-2 rounded-md text-sm font-medium hover:text-green-600"
                                :class="[$route.path.startsWith('/fields') ? 'text-green-600' : 'text-gray-700']"
                            >
                                Fields
                            </router-link>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <router-view></router-view>
            </div>
        </main>
    </div>
</template>

<script setup>
import { useRoute } from 'vue-router';

const route = useRoute();
</script>
