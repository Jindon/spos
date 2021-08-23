<template>
    <div class="md:pb-32 pb-16">
        <spin :spinning="loading">
            <form @submit.prevent="save">
                <div class="grid md:grid-cols-2 grid-cols-1 md:gap-x-4 gap-y-2">
                    <FormGroup label="Name" label-for="name" :error="nameError" required class="md:col-span-2">
                        <input id="name" name="name" v-model="name" required
                            class="form-control" :class="nameError ? 'form-control--error' : ''"
                        />
                    </FormGroup>

                    <FormGroup label="Address" label-for="address" :error="addressError" required class="md:col-span-2">
                        <textarea id="address" name="address" v-model="address" required
                            class="form-control" :class="addressError ? 'form-control--error' : ''"
                        ></textarea>
                    </FormGroup>

                    <FormGroup label="Phone" label-for="phone" :error="phoneError">
                        <input id="phone" name="phone" v-model="phone"
                            class="form-control" :class="phoneError ? 'form-control--error' : ''"
                        />
                    </FormGroup>

                    <FormGroup label="Alt Phone" label-for="alt_phone" :error="alt_phoneError">
                        <input id="alt_phone" name="alt_phone" v-model="alt_phone"
                            class="form-control" :class="alt_phoneError ? 'form-control--error' : ''"
                        />
                    </FormGroup>

                    <FormGroup label="Email" label-for="email" :error="emailError">
                        <input id="email" name="email" v-model="email"
                            class="form-control" :class="emailError ? 'form-control--error' : ''"
                        />
                    </FormGroup>

                    <FormGroup label="GSTIN" label-for="gstin" :error="gstinError">
                        <input id="gstin" name="gstin" v-model="gstin"
                            class="form-control" :class="gstinError ? 'form-control--error' : ''"
                        />
                    </FormGroup>

                    <FormGroup label="Bank name" label-for="bank_name" :error="bank_nameError">
                        <input id="bank_name" name="bank_name" v-model="bank_name"
                            class="form-control" :class="bank_nameError ? 'form-control--error' : ''"
                        />
                    </FormGroup>

                    <FormGroup label="Bank branch" label-for="bank_branch" :error="bank_branchError">
                        <input id="bank_branch" name="bank_branch" v-model="bank_branch"
                            class="form-control" :class="bank_branchError ? 'form-control--error' : ''"
                        />
                    </FormGroup>

                    <FormGroup label="Bank IFSC" label-for="bank_ifsc" :error="bank_ifscError">
                        <input id="bank_ifsc" name="bank_ifsc" v-model="bank_ifsc"
                            class="form-control" :class="bank_ifscError ? 'form-control--error' : ''"
                        />
                    </FormGroup>

                    <FormGroup label="Bank account" label-for="bank_account" :error="bank_accountError">
                        <input id="bank_account" name="bank_account" v-model="bank_account"
                            class="form-control" :class="bank_accountError ? 'form-control--error' : ''"
                        />
                    </FormGroup>
                </div>

                <button class="btn-primary mt-6" type="submit">Save</button>
            </form>
        </spin>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { useStore } from 'vuex'
import { UserIcon, LockClosedIcon } from '@heroicons/vue/outline'
import { useToast } from "vue-toastification"
import { useForm, useField } from 'vee-validate';
import * as yup from 'yup';

import axios from 'axios'
import FormGroup from '@/views/components/FormGroup.vue'
import Spin from '@/views/components/Spin.vue'

const gstinRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/
const ifscRegex = /^[A-Z]{4}0[A-Z0-9]{6}$/
const phoneRegex = /^[6-9]\d{9}$/

export default defineComponent({
    components: {
        Spin,
        UserIcon,
        LockClosedIcon,
        FormGroup,
    },
    setup() {
        onMounted(() => {
            fetchBusinessDetails()
        })
        const store = useStore()
        const toast = useToast()
        const businessData = ref({})
        const fetchBusinessDetails = () => {
            loading.value = true
            axios.get('/api/shop').then((response) => {
                businessData.value = response.data.data
                loading.value = false
            })
        }

        const schema = yup.object({
            name: yup.string().required(),
            address: yup.string().required(),
            phone: yup.lazy(() => yup.string().when(['phone'], {
                is: (phone) => phone?.length > 0,
                then: yup.string().matches(phoneRegex, 'Must be a valid phone number')
            }).nullable()),
            alt_phone: yup.lazy(() => yup.string().when(['alt_phone'], {
                is: (alt_phone) => alt_phone?.length > 0,
                then: yup.string().matches(phoneRegex, 'Must be a valid phone number')
            }).nullable()),
            email: yup.string().nullable().email(),
            gstin: yup.lazy(() => yup.string().when(['gstin'], {
                is: (gstin) => gstin?.length > 0,
                then: yup.string().matches(gstinRegex, 'Must be a valid GSTIN number')
            }).nullable()),
            bank_name: yup.string().nullable(),
            bank_branch: yup.string().nullable(),
            bank_ifsc: yup.lazy(() => yup.string().when(['bank_ifsc'], {
                is: (bank_ifsc) => bank_ifsc?.length > 0,
                then: yup.string().matches(ifscRegex, 'Must be a valid IFSC code')
            }).nullable()),
            bank_account: yup.string().nullable(),
        })
        const form = useForm({
            validationSchema: schema,
            initialValues: businessData
        })

        const { value: name, errorMessage: nameError } = useField('name');
        const { value: address, errorMessage: addressError } = useField('address')
        const { value: phone, errorMessage: phoneError } = useField('phone')
        const { value: alt_phone, errorMessage: alt_phoneError } = useField('alt_phone')
        const { value: email, errorMessage: emailError } = useField('email')
        const { value: gstin, errorMessage: gstinError } = useField('gstin')
        const { value: bank_name, errorMessage: bank_nameError } = useField('bank_name')
        const { value: bank_branch, errorMessage: bank_branchError } = useField('bank_branch')
        const { value: bank_ifsc, errorMessage: bank_ifscError } = useField('bank_ifsc')
        const { value: bank_account, errorMessage: bank_accountError } = useField('bank_account')

        const loading = ref(false)
        const errors = ref(null)

        const save = () => {
            form.validate().then(({ valid }) => {
                if(valid) {
                    loading.value = true
                    errors.value = null
                    axios.patch('/api/shop', form.values).then((response) => {
                        toast.success(response.data.message)
                        store.commit('user/SET_SHOP', response.data.data)
                        fetchBusinessDetails()
                    }).catch((e) => {
                        loading.value = false
                        if(e.response.status === 422) {
                            errors.value = e.response.data.errors
                        }
                    })
                }
            })
        }

        return {
            loading,
            errors,
            name, nameError,
            address, addressError,
            phone, phoneError,
            alt_phone, alt_phoneError,
            email, emailError,
            gstin, gstinError,
            bank_name, bank_nameError,
            bank_branch, bank_branchError,
            bank_ifsc, bank_ifscError,
            bank_account, bank_accountError,
            save
        }
    }
})
</script>
