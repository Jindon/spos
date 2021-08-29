<template>
    <AppLayout maxWidth="max-w-3xl">
        <template #header>
            <div class="flex flex-col md:flex-row justify-between md:items-center">
                <h2 class="text-xl md:text-3xl font-bold leading-none">Products</h2>
                <div class="md:w-2/3 flex items-center justify-between space-x-2">
                    <div class="flex-1">
                        <div class="text-xs font-semibold text-gray-600">Search products</div>
                        <input class="p-2 rounded-md bg-gray-50 w-full" type="text" placeholder="Product name / code"
                            v-debounce:350ms="updateSearch"
                        >
                    </div>
                    <div class="mt-4 space-x-2">
                        <button @click.prevent="openAddModal" class="btn-primary">
                            <PlusIcon class="w-4 h-4" />
                        </button>
                        <button @click.prevent="openUploadModal" class="btn-secondary">
                            <UploadIcon class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        </template>
        <Spin :spinning="loading">
            <div v-if="products.length">
                <div v-for="product in products" :key="product.id">
                    <ProductCard :product="product" @click.prevent="selectProduct(product.id)"/>
                    <hr class="bg-gray-100">
                </div>
                <Pagination :currentPage="currentPage" :meta="meta" @change="changePage"/>
            </div>
            <div v-else class="bg-gray-50 rounded-md">
                <div class="flex items-center justify-center w-full h-full py-32">
                    <p class="text-gray-400">No Products found</p>
                </div>
            </div>

            <div>
                <Modal
                    v-model="showForm"
                    @cancel="closeForm"
                    class="md:ml-48"
                >
                    <div class="px-4">
                        <p class="text-lg font-bold">{{ selectedProductId ? 'Update product' : 'Add a product'}}</p>
                    </div>
                    <div class="px-4 w-full" >
                        <ProductForm
                            :editProductId="selectedProductId"
                            @saved="handleSaved"
                            @deleted="handleDeleted"
                            @cancel="closeForm"
                        />
                    </div>
                </Modal>
            </div>
            <div>
                <Modal
                    v-model="showUploadForm"
                    @cancel="closeUploadForm"
                    class="md:ml-48"
                >
                    <div class="px-4">
                        <p class="text-lg font-bold">Bulk upload products</p>
                    </div>
                    <div class="px-4 w-full" >
                        <ProductUploadForm
                            @uploaded="handleUploaded"
                            @cancel="closeUploadForm"
                        />
                    </div>
                </Modal>
            </div>
        </Spin>
    </AppLayout>
</template>

<script>
import axios from 'axios'
import { PlusIcon, UploadIcon } from '@heroicons/vue/outline'
import { useToast } from "vue-toastification"
import AppLayout from '@/views/layouts/AppLayout.vue'
import Spin from '@/views/components/Spin.vue'
import ProductCard from '@/views/components/Products/ProductCard.vue'
import ProductForm from '@/views/components/Products/ProductForm.vue'
import ProductUploadForm from '@/views/components/Products/ProductUploadForm.vue'
import Pagination from '@/views/components/Pagination.vue'
import Modal from '@/views/components/Modal.vue'

export default {
    props: ['createdInvoiceId'],
    components: {
        AppLayout,
        Modal,
        PlusIcon,
        UploadIcon,
        ProductCard,
        ProductForm,
        ProductUploadForm,
        Spin,
        Pagination,
    },

    mounted() {
        if(this.$route.query.page) {
            this.currentPage = parseInt(this.$route.query.page)
        }
        this.getProducts()
    },

    data() {
        return {
            search: '',
            loading: false,
            selectedProductId: null,
            showForm: false,
            showUploadForm: false,
            products: [],
            meta: {},
            currentPage: 1,
        }
    },
    watch: {
        search: function (val) {
            this.getProducts()
        }
    },
    methods: {
        changePage(page) {
            this.currentPage = page
            this.getProducts()
            this.$router.push({query: {page: page}})
        },
        updateSearch(search) {
            this.search = search
        },
        getProducts() {
            this.loading = true
            let url = `/api/products?page=${this.currentPage}`
            if (this.search) {
                url += `&filter[search]=${this.search}`
            }
            axios.get(url).then((response) => {
                this.loading = false
                this.products = response.data.data
                this.meta = response.data.meta
                if(this.currentPage > 1 && !this.products.length) {
                    this.changePage(1)
                }
            })
        },
        openAddModal() {
            this.showForm = true
            this.selectedProductId = null
        },
        openUploadModal() {
            this.showUploadForm = true
        },
        selectProduct(product) {
            this.showForm = true
            this.selectedProductId = product
        },
        closeForm() {
            this.showForm = false
            this.selectedProductId = null
        },
        closeUploadForm() {
            this.showUploadForm = false
        },
        handleSaved() {
            this.closeForm()
            this.toast.success('Product saved successfully!')
            this.getProducts()
        },
        handleUploaded() {
            this.closeUploadForm()
            this.toast.success('Products uploaded successfully!')
            this.getProducts()
        },
        handleDeleted() {
            this.closeForm()
            this.toast.success('Products deleted successfully!')
            this.getProducts()
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
