<template>
    <Spin :spinning="loading">
        <Form :validation-schema="schema" @submit="onSubmit" v-slot="{ errors }" ref="customerForm">
            <div class="w-full py-6">
                <div class="grid md:grid-cols-2 grid-cols-1 gap-x-4 gap-y-2">
                    <FormGroup label="Customer Name" label-for="customerName" :error="errors[`name`]"
                        required class="md:col-span-2"
                    >
                        <Field
                            id="customerName"
                            :name="`name`"
                            class="form-control"
                            :class="errors[`name`] ? 'item-form--error' : ''"
                        />
                    </FormGroup>

                    <FormGroup label="Customer Address" label-for="customerAddress"
                        :error="errors[`address`]"
                        class="md:col-span-1"
                    >
                        <Field
                            v-slot="{ field }"
                            type="textarea"
                            id="customerAddress"
                            :name="`address`"
                        >
                            <textarea
                                v-bind="field"
                                :name="`address`"
                                class="form-control"
                                :class="errors[`address`] ? 'item-form--error' : ''"
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
                        :error="errors[`gstin`]" class="md:col-span-1"
                    >
                        <Field
                            id="customerGstin"
                            :name="`gstin`"
                            class="form-control"
                            :class="errors[`gstin`] ? 'item-form--error' : ''"
                        />
                    </FormGroup>

                    <FormGroup label="Customer PAN" label-for="customerPan"
                        :error="errors[`pan`]" class="md:col-span-1"
                    >
                        <Field
                            id="customerPan"
                            :name="`pan`"
                            class="form-control"
                            :class="errors[`pan`] ? 'item-form--error' : ''"
                        />
                    </FormGroup>
                </div>
            </div>

            <div class="w-full px-0 pt-6 border-t border-gray-200 flex justify-between md:items-center">
                <div class="">
                    <div v-if="editCustomerId" class="pr-3">
                        <button class="btn-danger" @click.prevent="handleDelete">
                            <TrashIcon class="w-4 h-4 md:hidden" /><span class="md:block hidden">Delete</span>
                        </button>
                    </div>
                </div>
                <div class="flex">
                    <button @click.prevent="handleCancel" type="button" class="btn-secondary mr-3">Cancel</button>
                    <button type="submit" class="btn-primary">{{ editCustomerId ? 'Update' : 'Create'}}</button>
                </div>
            </div>
        </Form>
    </Spin>
</template>

<script>
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { Field, Form, ErrorMessage } from "vee-validate";
import * as yup from 'yup';
import axios from 'axios'
import FormGroup from '@/views/components/FormGroup.vue'
import Spin from '@/views/components/Spin.vue'
import { TrashIcon } from '@heroicons/vue/outline'

const gstinRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/
const panRegex = /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/

export default {
    props: {
        editCustomerId: {
            default: null
        },
        states: {
            type: Array,
            required: true
        },
    },
    components: {
        FormGroup,
        Field,
        Form,
        ErrorMessage,
        Spin,
        TrashIcon,
    },

    watch: {
        editCustomerId: function(customerId) {
            this.$refs.customerForm.resetForm()
            if(customerId) {
                this.getCustomerData(customerId)
            }
        }
    },

    data: () => {
        const schema = yup.object().shape({
            name: yup.string().required().label("Customer Name"),
            address: yup.string().nullable().label("Customer Address"),
            state_id: yup.string().required().label("Customer State"),
            gstin: yup.lazy(() => yup.string().when(['gstin'], {
                is: (gstin) => gstin?.length > 0,
                then: yup.string().matches(gstinRegex, 'Must be a valid GSTIN number')
            }).nullable()),
            pan: yup.lazy(() => yup.string().when(['pan'], {
                is: (pan) => pan?.length > 0,
                then: yup.string().matches(panRegex, 'Must be a valid PAN number')
            }).nullable()),
        });

        return {
            loading: false,
            schema,
        };
    },

    methods: {
        getCustomerData(customerId) {
            this.loading = true
            axios.get(`/api/customers/${customerId}`).then((response) => {
                this.loading = false
                const customer = response.data.data
                this.$refs.customerForm.setFieldValue('name', customer.name)
                this.$refs.customerForm.setFieldValue('address', customer.address)
                this.$refs.customerForm.setFieldValue('state_id', customer.state_id)
                this.$refs.customerForm.setFieldValue('gstin', customer.gstin)
                this.$refs.customerForm.setFieldValue('pan', customer.pan)
            }).catch((error) => { this.loading = false; console.log(error.response) })
        },
        onSubmit(values, { resetForm }) {
            this.loading = true
            if(this.editCustomerId) {
                axios.patch(`/api/customers/${this.editCustomerId}`, values).then((response) => {
                    this.loading = false
                    resetForm()
                    this.$emit('saved')
                }).catch((e) => { this.loading = false; console.log(e) })
            } else {
                axios.post('/api/customers', values).then((response) => {
                    this.loading = false
                    resetForm()
                    this.$emit('saved')
                }).catch((e) => { this.loading = false; console.log(e) })
            }
        },
        handleDelete() {
            if(window.confirm("Are you sure you want to delete this customer?")) {
                this.loading = true
                axios.delete(`/api/customers/${this.editCustomerId}`).then((response) => {
                    this.loading = false
                    this.$emit('deleted')
                }).catch((error) => {
                    this.loading = false
                    console.log(error.response)
                })
            }
        },
        handleCancel() {
            this.$emit('cancel')
        }
    },

    setup() {
        const store = useStore()
        const router = useRouter()
        // console.log(store.getters['user/authenticated'])

        return {
            router,
        }
    }
}
</script>
