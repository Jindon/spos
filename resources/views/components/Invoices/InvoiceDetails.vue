<template>
    <Spin :spinning="loading">
        <div v-if="invoice" id="invoiceDetails">
            <div v-if="!isPrint" class="mb-4 print:hidden flex justify-between items-center">
                <div>
                    <button class="btn-primary-sm" @click="print">Print</button>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="btn-secondary-sm" @click="edit">Edit</button>
                    <button class="btn-danger-sm" @click="destroy">Delete</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <div class="min-w-54 print:min-w-full">
                    <div class="flex justify-between border border-gray-200 border-b-0">
                        <div class="w-2/3 px-4 py-3 border-r border-gray-200">
                            <h1 class="text-xl font-bold">{{ shop.name }}</h1>
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">{{ shop.gstin }}</p>
                            <p>{{ shop.address }}</p>
                            <p>{{ shop.state.name }}</p>
                            <div class="flex items-center mt-2"><PhoneIcon class="w-4 h-4 mr-2" /> {{ shop.phone }} <span v-if="shop.alt_phone">| {{ shop.alt_phone }}</span></div>
                            <div class="flex items-center mt-1"><MailIcon class="w-4 h-4 mr-2" /> {{ shop.email }}</div>
                        </div>

                        <div class="w-1/3 px-4 py-3 leading-none">
                            <p class="text-sm font-semibold text-gray-700">Invoice no</p>
                            <p class="font-bold text-lg mt-1">{{ invoice.invoice_no }}</p>
                            <p class="text-sm font-semibold text-gray-700 mt-2">Invoice date</p>
                            <div class="flex items-center text-sm font-bold text-gray-500 mt-1">
                                <CalendarIcon class="w-4 h-4 mr-2" />
                                {{ moment(invoice.invoice_date).format('DD-MM-YYYY') }}
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between border border-gray-200 border-b-0">
                        <div class="w-2/3 px-4 py-3 border-r border-gray-200">
                            <p class="text-sm font-semibold text-gray-700">Issued to</p>
                            <div v-if="invoice.retail">
                                <p class="text-lg font-bold">Retail Customer</p>
                            </div>
                            <div v-else>
                                <p class="text-lg font-bold">{{ invoice.customer_name }}</p>
                                <p>{{ invoice.customer_address }}</p>
                                <p v-if="invoice.customer_gstin" class="text-sm font-bold text-gray-500 tracking-wide uppercase">GSTIN: {{ invoice.customer_gstin }}</p>
                                <p v-if="invoice.customer_pan" class="text-sm font-bold text-gray-500 tracking-wide uppercase">PAN: {{ invoice.customer_pan }}</p>
                            </div>
                        </div>

                        <div class="w-1/3 px-4 py-3">
                            <p class="text-sm font-semibold text-gray-700">State sold to</p>
                            <p>{{ invoice.state.name }}</p>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-bold text-gray-600 px-4 py-2 border border-gray-200 uppercase tracking-wider">Invoice items</div>
                        <table class="table-fixed w-full border border-gray-200 border-t-0">
                            <thead>
                                <tr class="text-left text-sm bg-gray-100">
                                    <th class="w-10 p-1 px-4 border-r border-b border-gray-200">#</th>
                                    <th class="w-3/12 p-1 border-r border-b border-gray-200">Item Name</th>
                                    <th class="w-auto p-1 border-r border-b border-gray-200">Quantity</th>
                                    <th class="w-auto p-1 border-r border-b border-gray-200">Unit price</th>
                                    <th class="w-auto p-1 border-r border-b border-gray-200">Taxable</th>
                                    <th class="w-auto p-1 border-r border-b border-gray-200">Tax %</th>
                                    <th class="w-auto p-1 border-r border-b border-gray-200">Tax</th>
                                    <th class="w-2/12 border-b border-gray-200 p-1 px-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, idx) in invoice.items" :key="item.id" :class="idx%2 !== 0 ? 'bg-gray-100' : ''">
                                    <td class="p-1 px-4 border-r border-b border-gray-200">{{ idx + 1 }}</td>
                                    <td class="p-1 px-4 border-r border-b border-gray-200">{{ item.name }}</td>
                                    <td class="p-1 border-r border-b border-gray-200">{{ item.quantity }}</td>
                                    <td class="p-1 border-r border-b border-gray-200">₹{{ item.unit_price }}</td>
                                    <td class="p-1 border-r border-b border-gray-200">₹{{ item.taxable_amount }}</td>
                                    <td class="p-1 border-r border-b border-gray-200">{{ item.tax }}</td>
                                    <td class="p-1 border-r border-b border-gray-200">₹{{ item.tax_amount }}</td>
                                    <td class="p-1 px-4 border-b border-gray-200">₹{{ item.total }}</td>
                                </tr>
                                <tr class="border-t border-gray-200 bg-blue-100">
                                    <td colspan="7" class="p-1 text-right border-r border-gray-200 pr-4 font-bold">Total amount</td>
                                    <td colspan="1" class="p-1 pl-4 font-bold">₹{{ invoice.total + invoice.discount }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between">
                        <div class="w-7/12 border-b border-l border-gray-200">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <p class="text-sm text-gray-500 font-semibold mb-1">Additional tax info</p>
                                <table class="table-fixed w-full">
                                    <thead>
                                        <tr class="text-left">
                                            <th class="w-1/6">Rate</th>
                                            <th class="w-1/6">Taxable</th>
                                            <th class="w-1/6">Tax</th>
                                            <th class="w-1/6">CGST</th>
                                            <th class="w-1/6">SGST</th>
                                            <th class="w-1/6">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="taxInfo in invoice.additional_tax_info" :key="taxInfo.tax">
                                            <td>{{ parseFloat(taxInfo.tax) }}%</td>
                                            <td>{{ taxInfo.taxable_total }}</td>
                                            <td>{{ taxInfo.tax_total }}</td>
                                            <td>{{ (taxInfo.tax_total / 2).toFixed(2) }}</td>
                                            <td>{{ (taxInfo.tax_total / 2).toFixed(2) }}</td>
                                            <td>{{ taxInfo.total_sum }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="px-4 py-2">
                                <p class="text-sm text-gray-500 font-semibold mb-1">Bank details</p>
                                <table class="table-fixed w-full">
                                    <tbody>
                                        <tr>
                                            <td class="w-24 text-sm">Name:</td>
                                            <td class="font-bold">{{ shop.bank_name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="w-24 text-sm">Branch:</td>
                                            <td class="font-bold">{{ shop.bank_branch }}</td>
                                        </tr>
                                        <tr>
                                            <td class="w-24 text-sm">IFSC:</td>
                                            <td class="font-bold">{{ shop.bank_ifsc }}</td>
                                        </tr>
                                        <tr>
                                            <td class="w-24 text-sm">Account:</td>
                                            <td class="font-bold">{{ shop.bank_account }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="w-5/12 border border-gray-200 border-t-0">
                            <p class="text-sm text-gray-500 font-semibold mb-1 px-4 pt-3">Total</p>
                            <table class="table-fixed w-full">
                                <tbody>
                                    <tr>
                                        <td class="px-4 border-b border-t border-gray-200">Taxable</td>
                                        <td class="px-4 border-b border-t border-gray-200">₹{{ invoice.taxable }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 border-b border-gray-200">CGST</td>
                                        <td class="px-4 border-b border-gray-200">₹{{ invoice.cgst }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 border-b border-gray-200">SGST</td>
                                        <td class="px-4 border-b border-gray-200">₹{{ invoice.sgst }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 border-b border-gray-200">Discount</td>
                                        <td class="px-4 border-b border-gray-200">₹{{ invoice.discount ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 border-b border-gray-200 font-bold">Total payable</td>
                                        <td class="px-4 border-b border-gray-200 font-bold text-lg">₹{{ invoice.total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="px-4 pt-3">
                                <p class="text-sm text-gray-500 font-semibold mb-1">Total in words</p>
                                <p class="font-bold capitalize">Rupees: {{ numWords(invoice.total) }} Only</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end border border-gray-200 border-t-0 px-4 py-3">
                        <div class="w-full flex justify-between items-end">
                            <div>
                                <p>Invoice issued by <span class="font-bold">{{ shop.name }}</span></p>
                            </div>
                            <div class="mr-16">
                                <p class="py-10"></p>
                                <p class="px-4 pt-1 border-t border-gray-200">Authorized signatory</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Spin>
</template>

<script>
import { useStore } from 'vuex'
import moment from 'moment'
import axios from 'axios'
import numWords from 'num-words'
import { PhoneIcon, MailIcon, CalendarIcon } from '@heroicons/vue/outline'
import Spin from '@/views/components/Spin.vue'

export default {
    emits: ['close'],
    props: {
        invoice: {
            type: Object,
            required: true,
            default: null
        },
        isPrint: {
            type: Boolean,
            default: false
        },
    },
    components: {
        Spin,
        PhoneIcon,
        MailIcon,
        CalendarIcon,
    },
    data() {
        return {
            loading: false
        }
    },
    methods: {
        print() {
            this.$router.push({ name: 'invoices.print', params: { invoiceId: this.invoice.id } })
        },
        edit() {
            this.$router.push({ name: 'invoices.edit', params: { invoiceId: this.invoice.id } })
        },
        destroy() {
            if(window.confirm("Are you sure you want to delete this invoice?")) {
                this.loading = true
                axios.delete(`/api/invoices/${this.invoice.id}`).then((response) => {
                    this.loading = false
                    this.$emit('deleted')
                }).catch((error) => {
                    this.loading = false
                    console.log(error.response)
                })
            }
        }
    },
    setup() {
        const store = useStore()
        const shop = store.getters['user/shop']

        return {
            moment,
            numWords,
            shop,
        }
    }
}
</script>

<style>
    @media print {
        @page { margin: 0; }
    }
</style>
