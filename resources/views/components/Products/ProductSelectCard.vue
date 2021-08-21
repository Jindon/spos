<template>
    <div>
        <div class="font-bold capitalize group-hover:text-blue-600">{{ product.name }}</div>
        <div class="uppercase text-xs font-bold text-gray-500">Code: {{ product.code }}</div>
        <div>
            Unit price: <span class="font-bold group-hover:text-blue-600">₹{{ product.unit_price }}</span>
        </div>
        <div>
            Tax: <span class="group-hover:text-blue-600">{{ product.tax }}%</span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        product: {
            type: Object,
            required: true,
        }
    }
}
</script>
