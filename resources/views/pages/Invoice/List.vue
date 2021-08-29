<template>
    <AppLayout>
        <template #header>
            <div class="flex flex-col justify-between">
                <h2 class="text-xl md:text-3xl font-bold leading-none mb-2">Invoices</h2>
                <div class="flex flex-col md:flex-row md:items-center justify-between space-x-2">
                    <div class="flex-1">
                        <div class="text-xs font-semibold text-gray-600">Search invoices</div>
                        <input class="p-2 rounded-md bg-gray-50 w-full" type="text" placeholder="Invoice no / customer name"
                            v-debounce:350ms="updateSearch"
                        >
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="md:w-36">
                            <div class="text-xs font-semibold text-gray-600">From date</div>
                            <datepicker v-model="fromDate" input-format="dd-MM-yyyy"/>
                        </div>
                        <div class="md:w-36">
                            <div class="text-xs font-semibold text-gray-600">To date</div>
                            <datepicker v-model="toDate" input-format="dd-MM-yyyy"/>
                        </div>
                    </div>
                    <div class="md:w-36">
                        <div class="text-xs font-semibold text-gray-600">Customer type</div>
                        <select v-model="customerType" class="form-control w-full">
                            <option value="all">All</option>
                            <option value="retail">Retail</option>
                            <option value="b2b">B2B</option>
                        </select>
                    </div>
                </div>
            </div>
        </template>
        <Spin :spinning="loading">
            <div v-if="invoices.length">
                <div v-for="invoice in invoices" :key="invoice.id">
                    <InvoiceCard :invoice="invoice" @click.prevent="selectInvoice(invoice)"/>
                    <hr class="bg-gray-100">
                </div>
                <Pagination :currentPage="currentPage" :meta="meta" @change="changePage"/>
            </div>
            <div v-else class="bg-gray-50 rounded-md">
                <div class="flex items-center justify-center w-full h-full py-32">
                    <p class="text-gray-400">No Invoices found</p>
                </div>
            </div>
            <InvoiceSlide :show="showInvoice" @close="closeInvoiceDetails">
                <template #header v-if="selectedInvoice">
                    <p class="text-xs font-bold text-blue-500">Invoice details for</p>
                    <p class="text-xl font-bold"># {{selectedInvoice.invoice_no}}</p>
                </template>
                <div v-if="selectedInvoice">
                    <InvoiceDetails :invoice="selectedInvoice" @deleted="handleInvoiceDeleted"/>
                </div>
            </InvoiceSlide>
        </Spin>
    </AppLayout>
</template>

<script>
import axios from 'axios'
import moment from 'moment'
import Datepicker from 'vue3-datepicker'
import AppLayout from '@/views/layouts/AppLayout.vue'
import Spin from '@/views/components/Spin.vue'
import InvoiceCard from '@/views/components/Invoices/InvoiceCard.vue'
import InvoiceSlide from '@/views/components/Invoices/InvoiceSlide.vue'
import InvoiceDetails from '@/views/components/Invoices/InvoiceDetails.vue'
import Pagination from '@/views/components/Pagination.vue'

export default {
    props: ['createdInvoiceId'],
    components: {
        AppLayout,
        InvoiceCard,
        InvoiceSlide,
        Spin,
        InvoiceDetails,
        Pagination,
        Datepicker,
    },

    mounted() {
        if(this.$route.query.page) {
            this.currentPage = parseInt(this.$route.query.page)
        }
        this.getInvoices()
        if(this.createdInvoiceId) {
            this.loading = true
            axios.get(`/api/invoices/${this.createdInvoiceId}`).then((response) => {
                this.loading = false
                this.selectedInvoice = response.data.data
                this.showInvoice = true
            })
        }
    },

    data() {
        return {
            fromDate: moment().subtract(1, 'month').toDate(),
            toDate: moment().toDate(),
            search: '',
            customerType: 'all',
            loading: false,
            selectedInvoice: null,
            showInvoice: false,
            invoices: [],
            meta: {},
            currentPage: 1,
        }
    },
    watch: {
        fromDate: function (val) {
            this.getInvoices()
        },
        toDate: function (val) {
            this.getInvoices()
        },
        search: function (val) {
            this.getInvoices()
        },
        customerType: function (val) {
            this.getInvoices()
        }
    },
    methods: {
        changePage(page) {
            this.currentPage = page
            this.getInvoices()
            this.$router.push({query: {page: page}})
        },
        updateSearch(search) {
            this.search = search
        },
        getInvoices() {
            this.loading = true
            const fromDate = moment(this.fromDate).format('YYYY-MM-DD')
            const toDate = moment(this.toDate).format('YYYY-MM-DD')
            let url = `/api/invoices?page=${this.currentPage}`

            if (this.search) {
                url += `&filter[search]=${this.search}`
            } else {
                url += `&filter[from_date]=${fromDate}&filter[to_date]=${toDate}`
            }
            if (this.customerType) {
                url += `&filter[customer_type]=${this.customerType}`
            }
            axios.get(url).then((response) => {
                this.loading = false
                this.invoices = response.data.data
                this.meta = response.data.meta
                if(this.currentPage > 1 && !this.invoices.length) {
                    this.changePage(1)
                }
            }).catch((error) => {
                console.log(error)
            })
        },
        selectInvoice(invoice) {
            this.showInvoice = true
            this.selectedInvoice = invoice
        },
        closeInvoiceDetails() {
            this.showInvoice = false,
            setTimeout(() => {
                this.selectedInvoice = null
            }, 300);
        },
        handleInvoiceDeleted() {
            this.showInvoice = false,
            this.selectedInvoice = null
            this.getInvoices()
        }
    },
}

</script>
