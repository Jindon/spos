<template>
    <a href="javascript:void;" class="group">
        <div class="md:-mx-4 bg-white group-hover:bg-blue-50 border border-white group-hover:border rounded-md group-hover:border-blue-500 transition-all ease-in-out duration-200">
            <div class="w-full flex flex-col md:flex-row">
                <div class="md:px-4 py-2 md:w-3/12 hover:border-r group-hover:border-blue-500">
                    <h3 class="text-lg text-gray-500 font-bold"># {{ invoice.invoice_no }}</h3>
                    <div class="flex items-center leading-none text-sm text-gray-400 font-bold mt-1">
                        <CalendarIcon class="w-4 h-4 mr-1" />
                        <span>{{ moment(invoice.invoice_date).format('DD-MM-YYYY') }}</span>
                    </div>
                </div>

                <div class="md:w-9/12 flex justify-between">
                    <div class="md:px-4 py-2 hover:border-r group-hover:border-blue-500 leading-none">
                        <p class="text-lg font-semibold">{{ invoice.customer_name }}</p>
                        <p class="text-xs font-bold text-gray-500 mt-1">GSTIN: <span class="text-gray-500">{{ invoice.customer_gstin ?? '----' }}</span></p>
                    </div>

                    <div class="md:px-4 py-2 md:w-1/3">
                        <p class="text-xs font-bold text-gray-500">Invoice total</p>
                        <div class="font-bold text-lg text-gray-500">
                            ₹ {{ invoice.total }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</template>

<script>
import moment from 'moment'
import { CalendarIcon, XIcon } from '@heroicons/vue/outline'

export default {
    components: {
        CalendarIcon,
        XIcon,
    },
    props: {
        invoice: {
            type: Object,
            required: true
        }
    },

    methods: {
        moment
    }
}
</script>
