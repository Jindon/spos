<template>
    <p class="text-xs font-bold text-blue-600 mb-2">Top customers</p>
    <div class="border border-gray-200 rounded-md overflow-x-auto">
        <table class="table-fixed w-full">
            <thead class="text-left text-xs uppercase text-gray-600 border-b border-gray-200">
                <tr>
                    <th class="w-1/12 px-2 py-1">#</th>
                    <th class="w-7/12 px-2 py-1">Customer name</th>
                    <th class="w-2/12 px-2 py-1">Sold</th>
                    <th class="w-2/12 px-2 py-1">Tax</th>
                </tr>
            </thead>
            <tbody>
                <template v-if="data.length">
                    <template v-for="(customer, idx) in data" :key="customer.name">
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
</template>

<script>
export default {
    props: {
        data: {
            type: Array,
            required: true
        }
    }
}
</script>

<style>

</style>
