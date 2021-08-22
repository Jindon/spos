<template>
    <AppLayout max-width="max-w-2xl">
        <template #header>
            <div class="flex flex-col md:flex-row justify-between md:items-center">
                <h2 class="text-xl md:text-3xl font-bold leading-none">Dashboard</h2>
                <div class="md:w-1/2 flex items-center justify-between space-x-2">
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
                    -
                    <span class="font-bold rounded-md bg-blue-50 text-blue-600 px-2">{{ moment(this.toDate).format('DD-MM-YYYY') }}</span>
                </div>

                <div class="flex items-center justify-between rounded-md text-blue-600 bg-blue-50 border border-blue-500">
                    <div class="px-3 py-2 font-bold">
                        Total number of invoices issued
                    </div>
                    <div class="w-32 px-3 py-2 text-center font-bold text-xl border-l border-blue-600">
                        {{ reports.invoice_count }}
                    </div>
                </div>

                <div class="pt-6">
                    <p class="text-xs font-bold text-blue-600 pb-2 border-b border-blue-500">Most sold items</p>
                    <table class="table-fixed w-full">
                        <thead class="text-left text-xs uppercase text-gray-600 border-b border-blue-300">
                            <tr>
                                <th class="w-1/12 px-2 py-1">#</th>
                                <th class="w-5/12 px-2 py-1">Name</th>
                                <th class="w-2/12 px-2 py-1">Tax%</th>
                                <th class="w-3/12 px-2 py-1">Sold</th>
                                <th class="w-1/12 px-2 py-1">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="reports.most_sold_items.length">
                                <template v-for="(item, idx) in reports.most_sold_items" :key="item.name">
                                    <tr :class="idx%2 === 0 ? 'bg-gray-50' : ''">
                                        <td class="px-2 py-1">{{ idx + 1 }}</td>
                                        <td class="px-2 py-1">{{ item.name }}</td>
                                        <td class="px-2 py-1">{{ parseFloat(item.tax_rate) }}%</td>
                                        <td class="px-2 py-1">₹{{ parseFloat(item.sold_total) }}</td>
                                        <td class="px-2 py-1">{{ parseFloat(item.sold_count) }}</td>
                                    </tr>
                                </template>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="5">
                                        <div class="mt-3 w-full bg-gray-100 rounded-md py-6 text-center text-sm text-gray-400">
                                            Not enough data to show report
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="pt-6">
                    <p class="text-xs font-bold text-blue-600 pb-2 border-b border-blue-500">Top customers</p>
                    <table class="table-fixed w-full">
                        <thead class="text-left text-xs uppercase text-gray-600 border-b border-blue-300">
                            <tr>
                                <th class="w-1/12 px-2 py-1">#</th>
                                <th class="w-7/12 px-2 py-1">Customer name</th>
                                <th class="w-2/12 px-2 py-1">Sold</th>
                                <th class="w-2/12 px-2 py-1">Tax</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="reports.top_customers.length">
                                <template v-for="(customer, idx) in reports.top_customers" :key="customer.name">
                                    <tr :class="idx%2 === 0 ? 'bg-gray-50' : ''">
                                        <td class="px-2 py-1">{{ idx + 1 }}</td>
                                        <td class="px-2 py-1">
                                            <div>
                                                <p>{{ customer.customer_name }}</p>
                                                <p v-if="customer.customer_gstin" class="text-sm">GSTIN:
                                                    <span class="text-blue-600 font-semibold">{{ customer.customer_gstin }}</span>
                                                </p>
                                                <p v-if="customer.customer_pan" class="text-sm">PAN:
                                                    <span class="text-blue-600 font-semibold">{{ customer.customer_pan }}</span>
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-2 py-1">₹{{ parseFloat(customer.sold_total) }}</td>
                                        <td class="px-2 py-1">₹{{ parseFloat(customer.tax_total) }}</td>
                                    </tr>
                                </template>
                            </template>

                            <template v-else>
                                <tr>
                                    <td colspan="5">
                                        <div class="mt-3 w-full bg-gray-100 rounded-md py-6 text-center text-sm text-gray-400">
                                            Not enough data to show report
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
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
export default {
    components: {
        AppLayout,
        Datepicker,
        Spin,
    },
    mounted() {
        this.getReports()
    },
    data() {
        return {
            fromDate: moment().startOf('year').subtract(2, 'years').toDate(),
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
            let url = 'api/dashboard'
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
