<template>
    <AppLayout maxWidth="max-w-4xl">
        <template #header>
            <div class="flex flex-col md:flex-row justify-between md:items-center">
                <h2 class="text-xl md:text-3xl font-bold leading-none">Customers</h2>
                <div class="md:w-1/2 flex items-center justify-between space-x-2">
                    <div class="flex-1">
                        <div class="text-xs font-semibold text-gray-600">Search customers</div>
                        <input class="p-2 rounded-md bg-gray-50 w-full" type="text" placeholder="Customer name / gstin"
                            v-debounce:350ms="updateSearch"
                        >
                    </div>
                    <div class="mt-4">
                        <button @click.prevent="openAddModal" class="btn-primary">
                            <PlusIcon class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        </template>
        <Spin :spinning="loading">
            <div v-if="customers.length">
                <div v-for="customer in customers" :key="customer.id">
                    <CustomerCard :customer="customer" @click.prevent="selectCustomer(customer.id)"/>
                    <hr class="bg-gray-100">
                </div>
                <Pagination :currentPage="currentPage" :meta="meta" @change="changePage"/>
            </div>
            <div v-else class="bg-gray-50 rounded-md">
                <div class="flex items-center justify-center w-full h-full py-32">
                    <p class="text-gray-400">No Customers found</p>
                </div>
            </div>

            <div>
                <Modal
                    v-model="showForm"
                    @cancel="closeForm"
                    class="md:ml-48"
                >
                    <div class="px-4">
                        <p class="text-lg font-bold">{{ selectedCustomerId ? 'Update product' : 'Add a product'}}</p>
                    </div>
                    <div class="px-4 w-full" >
                        <CustomerForm
                            :editCustomerId="selectedCustomerId"
                            :states="states"
                            @saved="handleSaved"
                            @deleted="handleDeleted"
                            @cancel="closeForm"
                        />
                    </div>
                </Modal>
            </div>
        </Spin>
    </AppLayout>
</template>

<script>
import axios from 'axios'
import { PlusIcon } from '@heroicons/vue/outline'
import { useToast } from "vue-toastification"
import AppLayout from '@/views/layouts/AppLayout.vue'
import Spin from '@/views/components/Spin.vue'
import CustomerCard from '@/views/components/Customers/CustomerCard.vue'
import CustomerForm from '@/views/components/Customers/CustomerForm.vue'
import Pagination from '@/views/components/Pagination.vue'
import Modal from '@/views/components/Modal.vue'

export default {
    props: ['createdInvoiceId'],
    components: {
        AppLayout,
        Modal,
        PlusIcon,
        CustomerCard,
        CustomerForm,
        Spin,
        Pagination,
    },

    mounted() {
        if(this.$route.query.page) {
            this.currentPage = parseInt(this.$route.query.page)
        }
        axios.get('api/states').then(({ data }) => {
            this.states = data.data
        }).catch((error) => { this.loading = false; console.log(error) })

        this.getCustomers()
    },

    data() {
        return {
            search: '',
            loading: false,
            selectedCustomerId: null,
            showForm: false,
            customers: [],
            states: [],
            meta: {},
            currentPage: 1,
        }
    },
    watch: {
        search: function (val) {
            this.getCustomers()
        }
    },
    methods: {
        changePage(page) {
            this.currentPage = page
            this.getCustomers()
            this.$router.push({query: {page: page}})
        },
        updateSearch(search) {
            this.search = search
        },
        getCustomers() {
            this.loading = true
            let url = `/api/customers?page=${this.currentPage}`
            if (this.search) {
                url += `&filter[search]=${this.search}`
            }
            axios.get(url).then((response) => {
                this.loading = false
                this.customers = response.data.data
                this.meta = response.data.meta
                if(this.currentPage > 1 && !this.customers.length) {
                    this.changePage(1)
                }
            })
        },
        openAddModal() {
            this.showForm = true
            this.selectedCustomerId = null
        },
        selectCustomer(customerId) {
            this.showForm = true
            this.selectedCustomerId = customerId
        },
        closeForm() {
            this.showForm = false,
            this.selectedCustomerId = null
        },
        handleSaved() {
            this.closeForm()
            this.toast.success('Customer saved successfully!')
            this.getCustomers()
        },
        handleDeleted() {
            this.closeForm()
            this.getCustomers()
        }
    },
     setup() {
        const toast = useToast()
        return {
            toast
        }
    }
}

</script>
