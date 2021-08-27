<template>
    <AppLayout max-width="max-w-xl">
        <template #header>
            <h2 class="text-xl md:text-3xl font-bold leading-none">Settings</h2>
        </template>
        <div>
            <div class="flex space-x-8 items-center">
                <button @click.prevent="currentTab = 'general'" class="font-semibold py-2 border-b border-gray-100" :class="currentTab === 'general' ? 'text-blue-500 border-b border-blue-500' : ''">General</button>
                <button @click.prevent="currentTab = 'business'" class="font-semibold py-2 border-b border-gray-100" :class="currentTab === 'business' ? 'text-blue-500 border-b border-blue-500' : ''">Business</button>
            </div>
            <hr class="mb-6 border-t border-gray-100" style="margin-top: -1px;">

            <transition name="fade">
                <div v-if="currentTab === 'general'">
                    <GeneralSettings />
                </div>
            </transition>

            <transition name="fade">
                <div v-if="currentTab === 'business'">
                    <BusinessSettings />
                </div>
            </transition>
        </div>
    </AppLayout>
</template>

<script>
import { ref } from 'vue'
import AppLayout from '@/views/layouts/AppLayout.vue'
import BusinessSettings from '@/views/components/Settings/BusinessSettings.vue'
import GeneralSettings from '@/views/components/Settings/GeneralSettings.vue'
export default {
    components: {
        AppLayout,
        BusinessSettings,
        GeneralSettings,
    },
    setup() {
        const currentTab = ref('general')

        return {
            currentTab,
        }
    }
}
</script>
