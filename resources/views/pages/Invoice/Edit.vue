<template>
    <FormLayout header-text="Edit invoice" max-width="max-w-7xl">
        <Spin :spinning="loading">
            <template v-if="invoice">
                <InvoiceForm :editInvoice="invoice"/>
            </template>
        </Spin>
    </FormLayout>
</template>

<script>
import FormLayout from '@/views/layouts/FormLayout.vue'
import InvoiceForm from '@/views/components/Invoices/InvoiceForm.vue'
import Spin from '@/views/components/Spin.vue'
import axios from 'axios'

export default {
    components: {
        FormLayout,
        InvoiceForm,
        Spin
    },
    mounted() {
        this.getInvoice(this.$route.params.invoiceId)
    },
    data() {
        return {
            loading: false,
            invoice: null,
        }
    },
    methods: {
        getInvoice(invoiceId) {
            this.loading = true
            axios.get(`/api/invoices/${invoiceId}`).then((response) => {
                this.loading = false
                this.invoice = response.data.data
            })
        }
    }
}
</script>
