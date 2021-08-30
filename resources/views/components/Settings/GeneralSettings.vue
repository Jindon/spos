<template>
    <Spin :spinning="loading">
        <div class="w-full py-3 space-y-6">
            <div class="flex items-center">
                <div class="w-12">
                    <input type="checkbox"
                        @change="handleChange"
                        v-model="showBankDetails"
                        id="showBankDetails"
                        class="w-5 h-5"
                    >
                </div>
                <div class="flex-1">
                    <div>
                        <p class="text-lg font-bold">Show bank details in invoice</p>
                        <p class="text-sm">Uncheck the checkbox if you want the bank details displayed on the invoices.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center">
                <div class="w-12">
                    <input type="checkbox"
                        @change="handleChange"
                        v-model="saveCustomers"
                        id="saveCustomers"
                        class="w-5 h-5"
                    >
                </div>
                <div class="flex-1">
                    <div>
                        <p class="text-lg font-bold">Save customers on invoice creation</p>
                        <p class="text-sm">Check this if you want customer with unique GSTIN to be saved automatically during invoice creation</p>
                    </div>
                    <p class="text-sm text-red-500">Customer won't be saved automatically if there is no GSTIN provided for the customer</p>
                </div>

            </div>
        </div>
    </Spin>
</template>

<script>
import { useStore } from 'vuex'
import { useToast } from "vue-toastification"
import axios from 'axios'
import Spin from '@/views/components/Spin.vue'

export default {
    components: {
        Spin,
    },

    data: () => {
        return {
            showBankDetails: false,
            saveCustomers: false,
            loading: false,
        };
    },

    mounted() {
        this.getSettings()
    },
    methods: {
        getSettings() {
            this.loading = true
            axios.get(`/api/settings`).then((response) => {
                this.loading = false
                this.saveCustomers = response.data.data.save_customers
                this.showBankDetails = response.data.data.show_bank_details
            }).catch((error) => { this.loading = false; console.log(error.response) })
        },
        handleChange() {
            this.loading = true
            axios.patch('/api/settings', {
                save_customers: this.saveCustomers,
                show_bank_details: this.showBankDetails,
             }).then((response) => {
                this.loading = false
                this.store.commit('user/SET_SETTINGS', response.data.data)
            }).catch((error) => { this.loading = false; console.log(error) })
        }
    },

    setup() {
        const store = useStore()
        const toast = useToast()

        return {
            toast,
            store
        }
    }
}
</script>
