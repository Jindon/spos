<template>
    <a href="javascript:void;" class="group">
        <div class="md:-mx-4 bg-white group-hover:bg-blue-50 border border-white group-hover:border rounded-md group-hover:border-blue-500 transition-all ease-in-out duration-200">
            <div class="w-full flex pb-1">
                <div class="px-4 py-1 w-6/12 hover:border-r group-hover:border-blue-500">
                    <h3 class="text-lg font-semibold capitalize">{{ product.name }}</h3>
                    <div class="leading-none text-xs text-gray-500 mt-1 uppercase tracking-wider">
                        <span class="bg-gray-50 rounded-md px-1">{{ product.code }}</span>
                    </div>
                </div>

                <div class="px-4 py-1 w-3/12 hover:border-r group-hover:border-blue-500 leading-none">
                    <p class="text-lg font-semibold">₹ {{ product.unit_price }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <template v-if="product.inclusive == 1">
                            <span class="px-1 rounded-md bg-blue-50 text-xs text-blue-500 font-semibold">Tax inclusive</span>
                        </template>
                        <template v-else>
                            <span class="px-1 rounded-md bg-yellow-50 text-xs text-yellow-500 font-semibold">Tax exclusive</span>
                        </template>
                    </p>
                </div>

                <div class="px-4 py-1 w-3/12">
                    <p class="text-xs font-bold text-gray-500">Tax</p>
                    <div class="font-bold text-lg text-gray-500">
                        {{ product.tax }} %
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
        product: {
            type: Object,
            required: true
        }
    },

    methods: {
        moment
    }
}
</script>
