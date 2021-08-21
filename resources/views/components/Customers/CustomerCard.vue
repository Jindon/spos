<template>
    <a href="javascript:void;" class="group">
        <div class="md:-mx-4 bg-white group-hover:bg-blue-50 border border-white group-hover:border rounded-md group-hover:border-blue-500 transition-all ease-in-out duration-200">
            <div class="w-full flex pb-1">
                <div class="px-4 py-1 w-5/12 hover:border-r group-hover:border-blue-500">
                    <h3 class="text-lg font-semibold capitalize">{{ customer.name }}</h3>
                    <div class="leading-none text-xs text-gray-500 mt-1 uppercase tracking-wider">
                        GSTIN: <span class="bg-blue-50 font-bold rounded-md px-1">{{ customer.gstin }}</span>
                    </div>
                    <div class="leading-none text-xs text-gray-500 mt-1 uppercase tracking-wider">
                        PAN: <span class="bg-yellow-50 font-bold rounded-md px-1">{{ customer.pan }}</span>
                    </div>
                </div>

                <div class="px-4 py-1 w-5/12 hover:border-r group-hover:border-blue-500 leading-none">
                    <p class="text-xs font-semibold">Address</p>
                    <p class="text-sm text-gray-500 mt-1">
                        <span class="text-xs text-blue-500 font-semibold">
                            {{ customer.address }}
                        </span>
                    </p>
                </div>

                <div class="px-4 py-1 w-3/12">
                    <p class="text-xs font-bold text-gray-500">State</p>
                    <div class="font-bold text-lg text-gray-500">
                        {{ customer.state.name }}
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
        customer: {
            type: Object,
            required: true
        }
    },

    methods: {
        moment
    }
}
</script>
