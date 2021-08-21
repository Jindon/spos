<template>
    <AppLayout max-width="max-w-xl">
        <template #header>
            <h2 class="text-xl md:text-3xl font-bold leading-none">Settings</h2>
        </template>
        <div>
            <div class="flex space-x-8 items-center">
                <button @click.prevent="currentTab = 'business'" class="font-semibold py-2 border-b border-gray-100" :class="currentTab === 'business' ? 'text-blue-500 border-b border-blue-500' : ''">Business</button>
                <!-- <button @click.prevent="currentTab = 'invoice'" class="font-semibold py-2 border-b border-gray-100" :class="currentTab === 'invoice' ? 'text-blue-500 border-b border-blue-500' : ''">Invoice</button> -->
            </div>
            <hr class="mb-6 border-t border-gray-100" style="margin-top: -1px;">

            <transition name="fade">
                <div v-if="currentTab === 'business'">
                    <BusinessSettings />
                </div>
            </transition>

            <transition name="fade">
                <div v-if="currentTab === 'invoice'">
                    <InvoiceSettings />
                </div>
            </transition>
        </div>
    </AppLayout>
</template>

<script>
import { ref } from 'vue'
import AppLayout from '@/views/layouts/AppLayout.vue'
import BusinessSettings from '@/views/components/Settings/BusinessSettings.vue'
import InvoiceSettings from '@/views/components/Settings/InvoiceSettings.vue'
export default {
    components: {
        AppLayout,
        BusinessSettings,
        InvoiceSettings,
    },
    setup() {
        const currentTab = ref('business')

        return {
            currentTab,
        }
    }
}
</script>
