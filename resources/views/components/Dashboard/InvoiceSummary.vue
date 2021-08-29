<template>
    <div>
        <div class="py-2 font-bold text-blue-600 text-sm">
            Total number of invoices issued
        </div>
        <div class="rounded-md text-gray-600 bg-gray-50 border border-gray-200">
            <div class="grid grid-cols-3 font-semibold text-sm text-center">
                <div class="px-3 py-2 border-r border-gray-200">
                    <p>Retail:</p>
                    <div class="text-xl md:text-3xl font-bold">{{ data.retail }}</div>
                </div>
                <div class="px-3 py-2 border-r border-gray-200">
                    <p>B2B:</p>
                    <div class="text-xl md:text-3xl font-bold">{{ data.b2b }}</div>
                </div>
                <div class="px-3 py-2">
                    <p>Total:</p>
                    <div class="text-xl md:text-3xl font-bold">{{ data.total }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    }
}
</script>

<style>

</style>
