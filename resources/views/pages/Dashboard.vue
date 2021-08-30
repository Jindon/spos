<template>
    <AppLayout max-width="max-w-3xl">
        <template #header>
            <div class="flex flex-col md:flex-row justify-between md:items-center">
                <h2 class="text-xl md:text-3xl font-bold leading-none">Dashboard</h2>
                <div class="md:w-1/3 flex items-center justify-between space-x-2">
                    <div class="w-full md:w-36">
                        <div class="text-xs font-semibold text-gray-600">From date</div>
                        <datepicker v-model="fromDate" input-format="dd-MM-yyyy"/>
                    </div>
                    <div class="w-full md:w-36">
                        <div class="text-xs font-semibold text-gray-600">To date</div>
                        <datepicker v-model="toDate" input-format="dd-MM-yyyy"/>
                    </div>
                </div>
            </div>
        </template>
        <Spin :spinning="loading">
            <div v-if="reports" class="pb-16 md:pb-32">
                <div class="mb-6">
                    Showing reports from
                    <span class="font-bold rounded-md bg-blue-50 text-blue-600 px-2">{{ moment(this.fromDate).format('DD-MM-YYYY') }}</span>
                    to
                    <span class="font-bold rounded-md bg-blue-50 text-blue-600 px-2">{{ moment(this.toDate).format('DD-MM-YYYY') }}</span>
                </div>

                <InvoiceSummary :data="reports.invoice_counts" />

                <div class="pt-6">
                    <TaxSummary :data="reports.tax_summary" />
                </div>

                <div class="pt-6">
                    <MostSold :data="reports.most_sold_items" />
                </div>

                <div class="pt-6">
                    <TopCustomers :data="reports.top_customers" />
                </div>
            </div>
        </Spin>
    </AppLayout>
</template>

<script>
import axios from 'axios'
import moment from 'moment'
import Datepicker from 'vue3-datepicker'
import AppLayout from '@/views/layouts/AppLayout.vue'
import Spin from '@/views/components/Spin.vue'
import TaxSummary from '@/views/components/Dashboard/TaxSummary.vue'
import MostSold from '@/views/components/Dashboard/MostSold.vue'
import TopCustomers from '@/views/components/Dashboard/TopCustomers.vue'
import InvoiceSummary from '@/views/components/Dashboard/InvoiceSummary.vue'
export default {
    components: {
        AppLayout,
        Datepicker,
        Spin,
        TaxSummary,
        MostSold,
        TopCustomers,
        InvoiceSummary,
    },
    mounted() {
        this.getReports()
    },
    data() {
        return {
            fromDate: moment().subtract(1, 'month').toDate(),
            toDate: moment().toDate(),
            loading: false,
            reports: null,
        }
    },
    watch: {
        fromDate: function (val) {
            this.getReports()
        },
        toDate: function (val) {
            this.getReports()
        },
    },
    methods: {
        moment,
        getReports() {
            this.loading = true
            let url = '/api/dashboard'
            if(this.fromDate && !this.toDate) {
                url += `?filter[from_date]=${this.getFormattedDate(this.fromDate)}`
            }
            if(this.toDate && !this.fromDate) {
                url += `?filter[to_date]=${this.getFormattedDate(this.toDate)}`
            }
            if(this.fromDate && this.toDate) {
                url += `?filter[from_date]=${this.getFormattedDate(this.fromDate)}&filter[to_date]=${this.getFormattedDate(this.toDate)}`
            }
            axios.get(url).then((response) => {
                this.loading = false
                this.reports = response.data.data
            }).catch((error) => {
                this.loading = false
                console.log(error.response)
            })
        },
        getFormattedDate(date) {
            return moment(date).format('YYYY-MM-DD')
        }
    }
}

</script>
