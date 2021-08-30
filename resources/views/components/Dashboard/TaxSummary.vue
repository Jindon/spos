<template>
    <div>
        <p class="text-xs font-bold text-blue-600 mb-2">Tax Summary</p>
        <div class="border border-gray-200 rounded-md overflow-x-auto">
            <table class="table-fixed w-full">
                <thead class="text-left text-xs uppercase text-gray-600 border-b border-gray-200">
                    <tr>
                        <th class="w-1/5 px-2 py-1">Tax %</th>
                        <th class="w-1/5 px-2 py-1">Taxable</th>
                        <th class="w-1/5 px-2 py-1">CGST</th>
                        <th class="w-1/5 px-2 py-1">SGST</th>
                        <th class="w-1/5 px-2 py-1">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="data.length">
                        <template v-for="(summary, idx) in data" :key="summary.tax">
                            <tr :class="idx%2 === 0 ? 'bg-gray-50' : ''">
                                <td class="px-2 py-1">{{ parseInt(summary.tax) }}%</td>
                                <td class="px-2 py-1">₹{{ parseFloat(summary.taxable_total) }}</td>
                                <td class="px-2 py-1">₹{{ parseFloat(summary.cgst) }}</td>
                                <td class="px-2 py-1">₹{{ parseFloat(summary.sgst) }}</td>
                                <td class="px-2 py-1">₹{{ parseFloat(summary.sum_total) }}</td>
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
        },
    }
}
</script>
