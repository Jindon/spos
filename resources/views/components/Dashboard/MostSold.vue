<template>
    <div>
        <p class="text-xs font-bold text-blue-600 mb-2">Most sold items</p>
        <div class="border border-gray-200 rounded-md overflow-x-auto">
            <table class="table-fixed w-full">
                <thead class="text-left text-xs uppercase text-gray-600 border-b border-gray-200">
                    <tr>
                        <th class="w-1/12 px-2 py-1">#</th>
                        <th class="w-5/12 px-2 py-1">Name</th>
                        <th class="w-2/12 px-2 py-1">Tax%</th>
                        <th class="w-2/12 px-2 py-1">Sold</th>
                        <th class="w-2/12 px-2 py-1">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="data.length">
                        <template v-for="(item, idx) in data" :key="item.name">
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
