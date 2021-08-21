<template>
    <FormLayout header-text="Create a new invoice" max-width="max-w-7xl">
        <InvoiceForm />
    </FormLayout>
</template>

<script>
import FormLayout from '@/views/layouts/FormLayout.vue'
import InvoiceForm from '@/views/components/Invoices/InvoiceForm.vue'

export default {
    components: {
        FormLayout,
        InvoiceForm,
    },
}
</script>
