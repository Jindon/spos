<template>
    <div>
        <div class="font-bold text-base capitalize group-hover:text-blue-600">{{ customer.name }}</div>
        <div class="uppercase text-xs font-semibold text-gray-500">
            GSTIN: <span class="font-semibold group-hover:text-blue-600">{{ customer.gstin }}</span>
        </div>
        <div class="uppercase text-xs font-semibold text-gray-500">
            PAN: <span class="font-semibold group-hover:text-blue-600">{{ customer.pan }}</span>
        </div>
        <div class="mt-1">
            Address: <span class="font-semibold group-hover:text-blue-600">{{ customer.address }}</span>
        </div>
        <div>
            State: <span class="font-semibold group-hover:text-blue-600">{{ customer.state.name }}</span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        customer: {
            required: true,
            type: Object
        }
    }
}
</script>
