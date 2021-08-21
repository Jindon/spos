<template>
  <div class="min-h-screen md:bg-gray-50 py-4 md:py-16">
    <div class="p-4 md:p-10 md:border-t-4 md:shadow-lg md:border-gray-800 rounded-md bg-white max-w-md m-auto">
        <spin :spinning="loading">
            <div class="mb-8">
                <p class="text-gray-700 text-xl md:text-3xl font-bold">Login</p>
            </div>

            <div v-if="errors" class="bg-red-100 text-red-800 px-2 py-3 rounded text-sm font-semibold mb-6">
                <div v-for="(v, k) in errors" :key="k">
                    <p v-for="error in v" :key="error" class="text-sm">
                        {{ error }}
                    </p>
                </div>
            </div>

            <div class="md:pb-8">
                <form @submit.prevent="login">
                    <FormGroup label="Email" label-for="email" :error="emailError" required>
                        <input id="email" name="email" v-model="email" required
                            class="form-control" :class="emailError ? 'form-control--error' : ''"
                        />
                    </FormGroup>

                    <FormGroup label="Password" label-for="password" :error="passwordError" required>
                        <input id="password" name="password" v-model="password" type="password" required
                            class="form-control" :class="passwordError ? 'form-control--error' : ''"
                        />
                    </FormGroup>

                    <button type="submit" class="login-btn mt-6" :disabled="loading">Login</button>
                </form>
            </div>
        </spin>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { UserIcon, LockClosedIcon } from '@heroicons/vue/outline'
import { useForm, useField } from 'vee-validate'
import * as yup from 'yup'
import Spin from '@/views/components/Spin.vue'
import FormGroup from '@/views/components/FormGroup.vue'

export default defineComponent({
    components: {
        Spin,
        UserIcon,
        LockClosedIcon,
        FormGroup,
    },
    setup() {
        const store = useStore()
        const router = useRouter()

        const schema = yup.object({
            email: yup.string().required().email(),
            password: yup.string().required().min(8),
        })
        const form = useForm({
            validationSchema: schema,
        })

        const { value: email, errorMessage: emailError } = useField('email')
        const { value: password, errorMessage: passwordError } = useField('password')

        const loading = ref(false)
        const errors = ref(null)

        const login = () => {
            form.validate().then(({ valid }) => {
                if(valid) {
                    loading.value = true
                    errors.value = null
                    store.dispatch('user/login', form.values).then(() => {
                        router.replace({ name: 'dashboard' })
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
            email,
            emailError,
            password,
            passwordError,
            login,
        }
    }
})
</script>
