<template>
  <div v-if="invoice" class="p-2 min-h-full">
      <InvoiceDetails :invoice="invoice"/>
  </div>
</template>

<script>
import axios from 'axios'
import InvoiceDetails from '@/views/components/Invoices/InvoiceDetails.vue'
export default {
    components: {
        InvoiceDetails,
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
                this.$nextTick(() => {
                    setTimeout(() => {
                        window.print()
                        this.$router.back()
                     }, 1000)
                })
            })
        }
    }
}
</script>
