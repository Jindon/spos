<template>
    <Spin :spinning="loading">
        <div v-if="states">
            <Form :validation-schema="schema" @submit="onSubmit" v-slot="{ values, errors }" ref="invoiceForm">
                <div class="grid md:grid-cols-3 grid-cols-1 gap-4 md:gap-16 pb-8">
                    <div class="col-span-2">
                        <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200">
                            <h3 class="font-bold text-xl text-gray-400">Customer details</h3>
                            <div>
                                <Field
                                    v-slot="{field, value}"
                                    :id="`walk_in_customer`"
                                    :name="`walk_in_customer`"
                                    type="checkbox"
                                    :unchecked-value="0"
                                    :value="1"
                                >
                                    <label for="walk_in_customer" class="flex items-center space-x-2">
                                        <input type="checkbox"
                                            v-bind="field"
                                            :value="1"
                                            :name="`walk_in_customer`"
                                            :id="`walk_in_customer`"
                                            :checked="value"
                                            class="w-5 h-5"
                                        > <span class="leading-none font-semibold text-gray-400">Walk-In Customer</span>
                                    </label>
                                </Field>
                            </div>
                            <button type="button"
                                @click.prevent="openCustomerSelect"
                                class="flex items-center px-3 py-2 rounded bg-blue-100 text-blue-500 hover:bg-blue-200 transition-all ease-in-out duration-200"
                                :class="values.walk_in_customer ? 'opacity-30 cursor-not-allowed' : ''"
                                :disabled="values.walk_in_customer"
                            >
                                <div class="font-semibold text-xs mr-3">Select customer</div>
                                <DuplicateIcon class="w-4 h-4" />
                            </button>
                        </div>
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-x-4 gap-y-2">
                            <FormGroup label="Customer Name" label-for="customerName" :error="errors[`customer_name`]"
                                required class="md:col-span-2"
                                v-show="!values.walk_in_customer"
                            >
                                <Field
                                    id="customerName"
                                    :name="`customer_name`"
                                    class="form-control"
                                    :class="errors[`customer_name`] ? 'item-form--error' : ''"
                                />
                            </FormGroup>

                            <FormGroup label="Customer Address" label-for="customerAddress"
                                :error="errors[`customer_address`]"
                                 v-show="!values.walk_in_customer"
                            >
                                <Field
                                    v-slot="{ field }"
                                    type="textarea"
                                    id="customerAddress"
                                    :name="`customer_address`"
                                >
                                    <textarea
                                        v-bind="field"
                                        :name="`customer_address`"
                                        class="form-control"
                                        :class="errors[`customer_address`] ? 'item-form--error' : ''"
                                    ></textarea>
                                </Field>
                            </FormGroup>

                            <FormGroup label="Customer State" label-for="customerState" required
                                :error="errors[`state_id`]" class="md:col-span-1"
                            >
                                <Field
                                    v-slot="{ field, value }"
                                    id="customerState"
                                    :name="`state_id`"
                                    :class="errors[`state_id`] ? 'item-form--error' : ''"
                                >
                                <select id="customerState"
                                    :name="`state_id`"
                                    class="form-control"
                                    v-bind="field"
                                >
                                    <template v-for="state in states" :key="state.id">
                                        <option :value="state.id" :selected="value == state.id">
                                            {{ state.name }}
                                        </option>
                                    </template>
                                </select>
                                </Field>
                            </FormGroup>

                            <FormGroup label="Customer GSTIN" label-for="customerGstin"
                                :error="errors[`customer_gstin`]" class="md:col-span-1"
                                 v-show="!values.walk_in_customer"
                            >
                                <Field
                                    id="customerGstin"
                                    :name="`customer_gstin`"
                                    class="form-control"
                                    :class="errors[`customer_gstin`] ? 'item-form--error' : ''"
                                />
                            </FormGroup>

                            <FormGroup label="Customer PAN" label-for="customerPan"
                                :error="errors[`customer_pan`]" class="md:col-span-1"
                                 v-show="!values.walk_in_customer"
                            >
                                <Field
                                    id="customerPan"
                                    :name="`customer_pan`"
                                    class="form-control"
                                    :class="errors[`customer_pan`] ? 'item-form--error' : ''"
                                />
                            </FormGroup>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-gray-400 pb-4 mb-4 border-b border-gray-200">Invoice details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2">
                            <FormGroup label="Invoice No" label-for="invoiceNo" :error="errors[`invoice_no`]"
                                required class="md:col-span-2"
                            >
                                <Field
                                    id="invoiceNo"
                                    :name="`invoice_no`"
                                    class="form-control"
                                    :class="errors[`invoice_no`] ? 'item-form--error' : ''"
                                    :disabled="true"
                                />
                            </FormGroup>

                            <FormGroup label="Invoice date" label-for="invoiceDate" :error="errors[`date`]"
                                required class="md:col-span-2"
                            >
                                <datepicker v-model="date" input-format="dd-MM-yyyy"/>
                            </FormGroup>
                        </div>
                    </div>
                </div>

                <div class="mb-32">
                    <h3 class="font-bold text-xl text-gray-400 pb-4 mb-4 border-b border-gray-200">Invoice items</h3>
                    <table class="table-fixed" v-if="items.length">
                        <thead>
                            <tr class="text-left border-b border-gray-100">
                                <th class="w-1/24 py-1">#</th>
                                <th class="w-6/24 px-2 py-1">Item name</th>
                                <th class="w-2/24 px-2 py-1">Qty</th>
                                <th class="w-3/24 px-2 py-1">Unit price</th>
                                <th class="w-2/24 px-2 py-1">Tax%</th>
                                <th class="w-1/24 px-2 py-1">Inclusive</th>
                                <th class="w-2/24 px-2 py-1">Taxable</th>
                                <th class="w-2/24 px-2 py-1">Tax</th>
                                <th class="w-2/24 px-2 py-1">Total</th>
                                <th class="w-1/24 px-2 py-1"><CogIcon class="w-5 h-5 text-blue-500" /></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, idx) in items" :key="item.id" class="border-b border-gray-100">
                                <td class="py-2">
                                    <div class="flex items-center justify-between">
                                        <!-- <legend>{{ idx + 1 }}</legend> -->
                                        <button type="button"
                                            @click.prevent="openProductSelect(values, idx)"
                                            class="p-2 rounded bg-blue-100 text-blue-500 hover:bg-blue-200 transition-all ease-in-out duration-200"
                                        >
                                            <DuplicateIcon class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                                <td class="p-2">
                                    <Field
                                        :id="`name_${idx}`"
                                        :name="`items[${idx}].name`"
                                        class="item-form"
                                        :class="errors[`items[${idx}].name`] ? 'item-form--error' : ''"
                                    />
                                </td>
                                <td class="p-2">
                                    <Field
                                        @input="setTotal(values, idx)"
                                        :id="`quantity_${idx}`"
                                        :name="`items[${idx}].quantity`"
                                        type="number"
                                        value="1"
                                        min="1"
                                        class="item-form"
                                        :class="errors[`items[${idx}].quantity`] ? 'item-form--error' : ''"
                                        :disabled="setDisabled(values, idx)"
                                    />
                                </td>
                                <td class="p-2">
                                    <Field
                                        @input="setTotal(values, idx)"
                                        :id="`unit_price_${idx}`"
                                        :name="`items[${idx}].unit_price`"
                                        type="number"
                                        min="0"
                                        value="0"
                                        class="item-form"
                                        :class="errors[`items[${idx}].unit_price`] ? 'item-form--error' : ''"
                                        :disabled="setDisabled(values, idx)"
                                    />
                                </td>
                                <td class="p-2">
                                    <Field
                                        @input="setTotal(values, idx)"
                                        :id="`tax_${idx}`"
                                        :name="`items[${idx}].tax`"
                                        type="number"
                                        min="0"
                                        value="0"
                                        class="item-form"
                                        :class="errors[`items[${idx}].tax`] ? 'item-form--error' : ''"
                                        :disabled="setDisabled(values, idx)"
                                    />
                                </td>
                                <td class="p-2 text-center">
                                    <Field
                                        @change="setTotal(values, idx)"
                                        v-slot="{field, value}"
                                        :id="`inclusive_${idx}`"
                                        :name="`items[${idx}].inclusive`"
                                        type="checkbox"
                                        :unchecked-value="0"
                                        :value="1"
                                    >
                                        <input type="checkbox"
                                            v-bind="field"
                                            :value="1"
                                            :name="`items[${idx}].inclusive`"
                                            :id="`inclusive_${idx}`"
                                            :checked="value"
                                            class="w-5 h-5"
                                        >
                                    </Field>
                                </td>
                                <td class="p-2 hide-number-control">
                                    <Field
                                        :id="`taxable_amount_${idx}`"
                                        :name="`items[${idx}].taxable_amount`"
                                        type="number"
                                        value="0"
                                        class="item-form"
                                        :disabled="true"
                                    />
                                </td>
                                <td class="p-2 hide-number-control">
                                    <Field
                                        :id="`tax_amount_${idx}`"
                                        :name="`items[${idx}].tax_amount`"
                                        type="number"
                                        value="0"
                                        class="item-form"
                                        :disabled="true"
                                    />
                                </td>
                                <td class="p-2 hide-number-control">
                                    <Field
                                        :id="`total_${idx}`"
                                        :name="`items[${idx}].total`"
                                        class="item-form"
                                        :disabled="true"
                                    />
                                </td>
                                <td>
                                    <div v-if="idx !== 0">
                                        <button type="button" @click="remove(item)" class="p-2">
                                            <XCircleIcon class="w-5 h-5 text-red-500" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10">
                                    <div class="flex justify-center">
                                        <button type="button" @click="add" class="w-full flex justify-center items-center text-sm border-2 border-dashed border-gray-200 bg-white hover:bg-gray-100 hover:border-blue-500 transition-all ease-in-out duration-200 rounded-md px-4 py-3">
                                            <PlusIcon class="w-4 h-4 mr-2" /><span class="font-semibold">Add item</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div>

                            <div class="flex justify-end items-center">
                                <div class="p-4 font-bold text-right">Discount</div>
                                <div class="p-4">
                                    <div class="w-48">
                                        <Field
                                            @change="setDiscount(values)"
                                            id="discount"
                                            name="discount"
                                            type="number"
                                            value="0"
                                            min="0"
                                            class="item-form"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <div class="w-1/12 py-2 mr-3 font-bold text-lg">
                                    <p class="text-sm font-semibold">Taxable</p>
                                    <p>₹{{ totalTaxable }}</p>
                                </div>
                                <div class="w-1/12 py-2 mr-3 font-bold text-lg">
                                    <p class="text-sm font-semibold">Tax total</p>
                                    <p>₹{{ totalTax }}</p>
                                </div>
                                <div class="w-1/12 py-2 font-bold text-lg">
                                    <p class="text-sm font-semibold">Invoice Total</p>
                                    <p>₹{{ total }}</p>
                                </div>
                            </div>
                    </div>
                </div>

                <div class="z-40 fixed bottom-0 left-0 flex items-center py-4 w-full bg-white border-t-2 border-gray-600">
                    <div class="w-full max-w-7xl m-auto px-4 md:px-0">
                        <button type="submit" class="btn-primary">{{ editInvoice ? 'Update Invoice' : 'Create Invoice'}}</button>
                        <button @click.prevent="router.back()" type="button" class="btn-secondary ml-4">Cancel</button>
                    </div>
                </div>
            </Form>
        </div>

        <div>
            <Modal
                v-model="showProductModal"
                @cancel="handleModalClose"
            >
                <div class="px-4">
                    <p class="text-lg font-bold">Select a product</p>
                    <p class="text-xs font-semibold text-gray-500">Use the search if you dont see the product in the list</p>
                </div>
                <div class="px-4 py-3">
                    <div class="text-xs font-semibold text-blue-600 mb-1">Search products</div>
                    <input class="p-2 rounded-md bg-gray-100 w-full" type="text" placeholder="Product name"
                        v-debounce:300ms="updateSearch"
                    >
                </div>
                <div v-if="products.length"
                    class="px-4 w-full text-left text-sm grid grid-cols-3 gap-2"
                >
                    <div v-for="product in products" :key="product.id"
                        class="w-full rounded-md border border-white bg-gray-50 group hover:bg-blue-50 hover:border-blue-400 transition-all ease-in-out duration-200"
                    >
                        <button
                            @click="handleProductSelect(product)"
                            class="w-full text-left px-2 py-1"
                        >
                            <ProductSelectCard :product="product" />
                        </button>
                    </div>
                </div>
                <div v-else class="flex justify-center items-center py-12 bg-gray-50 mx-4 rounded-md">
                    <div class="text-sm font-semibold text-gray-400">
                        No products found
                    </div>
                </div>
            </Modal>
        </div>
        <div>
            <Modal
                v-model="showCustomerModal"
                @cancel="handleCustomerModalClose"
            >
                <div class="px-4">
                    <p class="text-lg font-bold">Select a product</p>
                    <p class="text-xs font-semibold text-gray-500">Use the search if you dont see the product in the list</p>
                </div>
                <div class="px-4 py-3">
                    <div class="text-xs font-semibold text-blue-600 mb-1">Search customers</div>
                    <input class="p-2 rounded-md bg-gray-100 w-full" type="text" placeholder="Customer name / gstin"
                        v-debounce:300ms="updateCustomerSearch"
                    >
                </div>
                <div v-if="customers.length"
                    class="px-4 w-full text-left text-sm grid grid-cols-3 gap-2"
                >
                    <div v-for="customer in customers" :key="customer.id"
                        class="w-full rounded-md border border-white bg-gray-50 group hover:bg-blue-50 hover:border-blue-400 transition-all ease-in-out duration-200"
                    >
                        <button
                            @click="handleCustomerSelect(customer)"
                            class="w-full text-left px-2 py-1"
                        >
                            <CustomerSelectCard :customer="customer" />
                        </button>
                    </div>
                </div>
                <div v-else class="flex justify-center items-center py-12 bg-gray-50 mx-4 rounded-md">
                    <div class="text-sm font-semibold text-gray-400">
                        No customers found
                    </div>
                </div>
            </Modal>
        </div>
    </Spin>
</template>

<script>
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { Field, Form, ErrorMessage } from "vee-validate";
import * as yup from 'yup';
import { useToast } from "vue-toastification"
import Datepicker from 'vue3-datepicker'
import axios from 'axios'
import moment from 'moment'
import { CogIcon, XCircleIcon, PlusIcon, DuplicateIcon } from '@heroicons/vue/outline'
import FormGroup from '@/views/components/FormGroup.vue'
import Spin from '@/views/components/Spin.vue'
import Modal from '@/views/components/Modal.vue'
import CustomerSelectCard from '@/views/components/Customers/CustomerSelectCard.vue'
import ProductSelectCard from '@/views/components/Products/ProductSelectCard.vue'

const gstinRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/
const panRegex = /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/

export default {
    props: {
        editInvoice: {
            default: null
        }
    },
    components: {
        FormGroup,
        Field,
        Form,
        ErrorMessage,
        CogIcon,
        XCircleIcon,
        PlusIcon,
        DuplicateIcon,
        Datepicker,
        Spin,
        Modal,
        CustomerSelectCard,
        ProductSelectCard,
    },

    mounted() {
        this._keyListener = function(e) {
            if (e.key === "n" && (e.altKey || e.metaKey)) {
                e.preventDefault();
                this.add();
            }
        };
        document.addEventListener('keydown', this._keyListener.bind(this));

        this.getInitialData()
    },
    beforeDestroy() {
        document.removeEventListener('keydown', this._keyListener);
    },

    watch: {
        search: function (val) {
            this.getProducts()
        }
    },

    data: () => {
        const schema = yup.object().shape({
            invoice_no: yup.string().required(),
            walk_in_customer: yup.number().label("Walk-In Customer"),
            customer_name: yup.lazy(() => yup.string().when(['walk_in_customer'], {
                is: (walk_in_customer) => walk_in_customer != 1,
                then: yup.string().required().label("Customer Name").typeError('Customer name is required'),
                otherwise: yup.string().nullable().label("Customer Name"),
            }).nullable()),
            customer_address: yup.string().nullable().label("Customer Address"),
            state_id: yup.string().required().label("Customer State"),
            customer_gstin: yup.lazy(() => yup.string().when(['gstin'], {
                is: (gstin) => gstin?.length > 0,
                then: yup.string().matches(gstinRegex, 'Must be a valid GSTIN number')
            }).nullable()),
            discount: yup.string().label("Discount"),
            customer_pan: yup.lazy(() => yup.string().when(['pan'], {
                is: (pan) => pan?.length > 0,
                then: yup.string().matches(panRegex, 'Must be a valid PAN number')
            }).nullable()),
            items: yup
                .array()
                .of(
                    yup.object().shape({
                        name: yup.string().required().label("Name"),
                        quantity: yup.string().required().label("Quantity"),
                        unit_price: yup.string().required().label("unit price"),
                        tax: yup.string().label("Tax %"),
                        tax_amount: yup.string().label("Tax amount"),
                        taxable_amount: yup.string().label("Taxable amount"),
                        inclusive: yup.number().label("Inclusive"),
                        total: yup.string().label("Total"),
                    })
                )
                .strict(),
        });

        return {
            loading: false,
            search: '',
            products: [],
            showProductModal: false,
            searchCustomer: '',
            customers: [],
            showCustomerModal: false,
            selectedIndex: null,
            createdInvoice: null,
            formValues: null,
            schema,
            states: [],
            date: null,
            invoiceCount: '',
            total: 0,
            totalTaxable: 0,
            totalTax: 0,
            items: [],
        };
    },

    methods: {
        openProductSelect(values, idx) {
            this.selectedIndex = idx
            this.formValues = values
            this.showProductModal = true
            this.getProducts()
        },
        openCustomerSelect() {
            this.showCustomerModal = true
            this.getCustomers()
        },
        updateSearch(search) {
            this.search = search
        },
        updateCustomerSearch(search) {
            this.searchCustomer = search
        },
        getProducts() {
            const search = this.search ? `&filter[search]=${this.search}` : ''
            axios.get(`/api/products?limit=12` + search).then((response) => {
                this.products = response.data.data
            }).catch((error) => {
                console.log(error.response)
            })
        },
        getCustomers() {
            const search = this.searchCustomer ? `&filter[search]=${this.searchCustomer}` : ''
            axios.get(`/api/customers?limit=12` + search).then((response) => {
                this.customers = response.data.data
            }).catch((error) => {
                console.log(error.response)
            })
        },
        handleModalClose() {
            this.showProductModal = false
        },
        handleCustomerModalClose() {
            this.showCustomerModal = false
        },
        handleProductSelect(product) {
            this.$refs.invoiceForm.setFieldValue(`items[${this.selectedIndex}].name`, product.name.toString());
            this.$refs.invoiceForm.setFieldValue(`items[${this.selectedIndex}].unit_price`, product.unit_price.toString());
            this.$refs.invoiceForm.setFieldValue(`items[${this.selectedIndex}].tax`, product.tax.toString());
            this.$refs.invoiceForm.setFieldValue(`items[${this.selectedIndex}].inclusive`, product.inclusive);
            this.$nextTick(() => {
                this.handleModalClose()
                this.setTotal(this.formValues, this.selectedIndex)
            })
        },
        handleCustomerSelect(customer) {
            this.$refs.invoiceForm.setFieldValue(`customer_name`, customer.name.toString());
            this.$refs.invoiceForm.setFieldValue(`customer_address`, customer.address.toString());
            this.$refs.invoiceForm.setFieldValue(`customer_gstin`, customer.gstin.toString());
            this.$refs.invoiceForm.setFieldValue(`customer_pan`, customer.pan.toString());
            this.$refs.invoiceForm.setFieldValue(`state_id`, customer.state_id.toString());
            this.$nextTick(() => {
                this.handleCustomerModalClose()
            })
        },
        getInitialData() {
            this.loading = true
            axios.get('/api/states').then(({ data }) => {
                this.states = data.data
                this.$refs.invoiceForm.setFieldValue(`state_id`, 14);
                axios.get('/api/invoice-count').then(({ data }) => {
                    this.loading = false
                    const invoiceCount = data.count + 1
                    this.$refs.invoiceForm.setFieldValue(`invoice_no`, moment().format('yyyy/MM') + `-${invoiceCount.toString().padStart(4, 0)}`);
                    this.$refs.invoiceForm.setFieldValue(`state_id`, 14);

                    if(this.editInvoice) {
                        this.items = this.editInvoice.items
                        this.date = new Date(this.editInvoice.invoice_date)
                        this.setInvoiceData()
                    } else {
                        this.items.push({ id: Date.now(), })
                        this.date = new Date()
                        this.$refs.invoiceForm.setFieldValue(`walk_in_customer`, 1);
                    }

                }).catch((error) => { this.loading = false; console.log(error) })
            }).catch((error) => { this.loading = false; console.log(error) })
        },
        onSubmit(values) {
            this.loading = true
            values.invoice_date = moment(this.date).format('YYYY-MM-DD')
            if(this.editInvoice) {
                axios.patch(`/api/invoices/${this.editInvoice.id}`, values).then((response) => {
                    this.loading = false
                    this.createdInvoice = response.data.data
                    this.toast.success('Invoice updated successfully!')
                    this.$router.push({ name: 'invoices.list', params: { createdInvoiceId: this.createdInvoice.id } })
                }).catch((e) => { this.loading = false; console.log(e) })
            } else {
                axios.post('/api/invoices', values).then((response) => {
                    this.loading = false
                    this.createdInvoice = response.data.data
                    this.toast.success('Invoice created successfully!')
                    this.$router.push({ name: 'invoices.list', params: { createdInvoiceId: this.createdInvoice.id } })
                }).catch((e) => { this.loading = false; console.log(e) })
            }
        },
        add() {
            this.items.push({
                id: Date.now()
            });
        },
        remove(item) {
            const index = this.items.indexOf(item);
            if (index === -1) {
                return;
            }
            this.items.splice(index, 1);
        },
        setDisabled(values, idx) {
            if(values.items && values.items[idx]) {
                if(values.items[idx].hasOwnProperty('name')) {
                    return !values.items[idx].name
                }
                return true
            }
            return true
        },
        setTotal(values, idx) {
            if(values) {
                const item = values.items[idx]
                let total = item.quantity * item.price

                const itemKey = `items[${idx}]`

                if(item.inclusive == 1) {
                    //inclusive
                    const price = item.unit_price /((item.tax / 100) + 1)
                    const totalTaxable = price * item.quantity
                    const tax = (item.unit_price - price)
                    const taxTotal = tax * item.quantity
                    total = item.unit_price * item.quantity
                    this.$refs.invoiceForm.setFieldValue(`${itemKey}.total`, total > 0 ? Math.round(total.toFixed(2)).toString() : 0);
                    this.$refs.invoiceForm.setFieldValue(`${itemKey}.taxable_amount`, totalTaxable > 0 ? totalTaxable.toFixed(2) : 0);
                    this.$refs.invoiceForm.setFieldValue(`${itemKey}.tax_amount`, total > 0 ? taxTotal.toFixed(2) : 0);
                } else {
                    // not inclusive
                    const price = parseFloat(item.unit_price)
                    const totalTaxable = price * item.quantity
                    const tax = price * (item.tax / 100)
                    const taxTotal = tax * item.quantity
                    total = (price + tax) * item.quantity
                    this.$refs.invoiceForm.setFieldValue(`${itemKey}.total`, total > 0 ? Math.round(total.toFixed(2)).toString() : 0);
                    this.$refs.invoiceForm.setFieldValue(`${itemKey}.taxable_amount`, totalTaxable > 0 ? totalTaxable.toFixed(2) : 0);
                    this.$refs.invoiceForm.setFieldValue(`${itemKey}.tax_amount`, total > 0 ? taxTotal.toFixed(2) : 0);
                }
                this.setInvoiceTotals(values)
            }
        },
        setInvoiceTotals(values) {
            let total = 0
            let totalTaxable = 0
            let totalTax = 0
            for (let index = 0; index < values.items.length; index++) {
                total += parseFloat(values.items[index]['total'])
                totalTaxable += parseFloat(values.items[index]['taxable_amount'])
                totalTax += parseFloat(values.items[index]['tax_amount'])
            }
            const discount = parseFloat(values.discount) >= 0 ? parseFloat(values.discount) : 0
            total = total >= 0 ? total : 0
            this.total = Math.round(total - discount)
            this.totalTaxable = Math.round(totalTaxable)
            this.totalTax = Math.round(totalTax)
        },
        setDiscount(values) {
            let total = 0
            for (let index = 0; index < values.items.length; index++) {
                total += parseFloat(values.items[index]['total'])
            }
            const discount = parseFloat(values.discount) >= 0 ? parseFloat(values.discount) : 0
            if(discount == 0) {
                this.$refs.invoiceForm.setFieldValue(`discount`, discount.toString());
            }
            total = total >= 0 ? total : 0
            this.total = Math.round(total - parseFloat(discount))
        },
        setInvoiceData() {
            this.total = this.editInvoice.total
            this.totalTaxable = this.editInvoice.taxable
            this.totalTax = this.editInvoice.tax

            this.$refs.invoiceForm.setFieldValue(`walk_in_customer`, this.editInvoice.walk_in_customer);
            this.$refs.invoiceForm.setFieldValue(`customer_name`, this.editInvoice.customer_name);
            this.$refs.invoiceForm.setFieldValue(`customer_address`, this.editInvoice.customer_address);
            this.$refs.invoiceForm.setFieldValue(`customer_gstin`, this.editInvoice.customer_gstin);
            this.$refs.invoiceForm.setFieldValue(`customer_pan`, this.editInvoice.customer_pan);
            this.$refs.invoiceForm.setFieldValue(`discount`, this.editInvoice.discount);
            this.$refs.invoiceForm.setFieldValue(`invoice_no`, this.editInvoice.invoice_no);
            this.$refs.invoiceForm.setFieldValue(`state_id`, this.editInvoice.state.id);
            this.$nextTick(() => {
                for (let index = 0; index < this.editInvoice.items.length; index++) {
                    const item = this.editInvoice.items[index];
                    this.$refs.invoiceForm.setFieldValue(`items[${index}].name`, item.name.toString());
                    this.$refs.invoiceForm.setFieldValue(`items[${index}].quantity`, item.quantity.toString());
                    this.$refs.invoiceForm.setFieldValue(`items[${index}].unit_price`, item.unit_price.toString());
                    this.$refs.invoiceForm.setFieldValue(`items[${index}].tax`, item.tax.toString());
                    this.$refs.invoiceForm.setFieldValue(`items[${index}].tax_amount`, item.tax_amount.toString());
                    this.$refs.invoiceForm.setFieldValue(`items[${index}].taxable_amount`, item.taxable_amount.toString());
                    this.$refs.invoiceForm.setFieldValue(`items[${index}].inclusive`, item.inclusive);
                    this.$refs.invoiceForm.setFieldValue(`items[${index}].total`, item.total.toString());
                }
            })
        }
    },

    setup() {
        const store = useStore()
        const router = useRouter()
        const toast = useToast()
        // console.log(store.getters['user/authenticated'])

        return {
            router,
            toast
        }
    }
}
</script>
