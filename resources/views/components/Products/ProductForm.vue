<template>
    <Spin :spinning="loading">
        <Form :validation-schema="schema" @submit="onSubmit" v-slot="{ errors }" ref="productForm">
            <div class="w-full py-6">
                <div class="grid md:grid-cols-3 grid-cols-1 gap-x-4 gap-y-2">
                    <FormGroup label="Item name" label-for="itemName" :error="errors[`name`]"
                        required class="md:col-span-2"
                    >
                        <Field
                            id="name"
                            :name="`name`"
                            class="form-control"
                            :class="errors[`name`] ? 'item-form--error' : ''"
                        />
                    </FormGroup>

                    <FormGroup label="Code" label-for="code" :error="errors[`code`]"
                        class="md:col-span-1"
                    >
                        <Field
                            id="code"
                            :name="`code`"
                            class="form-control"
                            :class="errors[`code`] ? 'item-form--error' : ''"
                        />
                    </FormGroup>

                    <FormGroup label="Unit Price" label-for="unitPrice"
                        :error="errors[`unit_price`]" class="md:col-span-1"
                    >
                        <Field
                            :id="`unitPrice`"
                            :name="`unit_price`"
                            type="number"
                            min="0"
                            value="0"
                            class="item-form"
                            :class="errors[`unit_price`] ? 'item-form--error' : ''"
                        />
                    </FormGroup>

                    <FormGroup label="Tax inclusive" label-for="inclusive"
                        :error="errors[`inclusive`]"
                    >
                        <Field
                            v-slot="{field, value}"
                            id="inclusive"
                            name="inclusive"
                            type="checkbox"
                            :unchecked-value="0"
                            :value="1"
                        >
                            <input type="checkbox"
                                v-bind="field"
                                :value="1"
                                :name="`inclusive`"
                                :id="`inclusive`"
                                :checked="value"
                                class="w-5 h-5 mt-2"
                            >
                        </Field>
                    </FormGroup>

                    <FormGroup label="Tax %" label-for="tax"
                        :error="errors[`tax`]" class="md:col-span-1"
                    >
                        <Field
                            :id="`tax`"
                            :name="`tax`"
                            type="number"
                            min="0"
                            value="0"
                            class="item-form"
                            :class="errors[`tax`] ? 'item-form--error' : ''"
                        />
                    </FormGroup>
                </div>
            </div>

            <div class="w-full px-0 pt-6 border-t border-gray-200 flex justify-between md:items-center">
                <div>
                    <div v-if="editProductId" class="pr-3">
                        <button class="btn-danger" @click.prevent="handleDelete">
                            <TrashIcon class="w-4 h-4 md:hidden" /><span class="md:block hidden">Delete</span>
                        </button>
                    </div>
                </div>
                <div class="flex">
                    <button @click.prevent="handleCancel" type="button" class="btn-secondary mr-3">Cancel</button>
                    <button type="submit" class="btn-primary">{{ editProductId ? 'Update' : 'Create'}}</button>
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

export default {
    props: {
        editProductId: {
            default: null
        }
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
        editProductId: function(productId) {
            this.$refs.productForm.resetForm()
            if(productId) {
                this.getProductdata(productId)
            }
        }
    },

    data: () => {
        const schema = yup.object().shape({
            name: yup.string().required().label("Name"),
            code: yup.string().nullable().label("Code"),
            unit_price: yup.string().required().label("unit price"),
            tax: yup.string().label("Tax %"),
            inclusive: yup.number().label("Inclusive"),
        });

        return {
            loading: false,
            schema,
        };
    },

    methods: {
        getProductdata(productId) {
            this.loading = true
            axios.get(`/api/products/${productId}`).then((response) => {
                this.loading = false
                const product = response.data.data
                this.$refs.productForm.setFieldValue('name', product.name)
                this.$refs.productForm.setFieldValue('code', product.code)
                this.$refs.productForm.setFieldValue('unit_price', product.unit_price)
                this.$refs.productForm.setFieldValue('inclusive', product.inclusive)
                this.$refs.productForm.setFieldValue('tax', product.tax)
            }).catch((error) => { this.loading = false; console.log(error.response) })
        },
        onSubmit(values, { resetForm }) {
            this.loading = true
            if(this.editProductId) {
                axios.patch(`/api/products/${this.editProductId}`, values).then((response) => {
                    this.loading = false
                    resetForm()
                    this.$emit('saved')
                }).catch((e) => { this.loading = false; console.log(e) })
            } else {
                axios.post('/api/products', values).then((response) => {
                    this.loading = false
                    resetForm()
                    this.$emit('saved')
                }).catch((e) => { this.loading = false; console.log(e) })
            }
        },
        handleDelete() {
            if(window.confirm("Are you sure you want to delete this product?")) {
                this.loading = true
                axios.delete(`/api/products/${this.editProductId}`).then((response) => {
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
