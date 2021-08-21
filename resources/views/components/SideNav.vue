<template>
    <nav class="flex flex-col min-h-screen justify-between">
        <div>
            <div class="py-4 px-4">
                <p class="font-bold leading-none text-lg">{{ shop ? shop.name : 'SPOS' }}</p>
                <p v-if="shop && shop.gstin" class="text-xs text-gray-400 leading-none tracking-wide uppercase mt-2 font-bold"> {{ shop.gstin }}</p>
            </div>
            <div class="py-4">
                <ul class="flex flex-col">
                    <router-link
                        :to="{ name: 'invoices.create' }"
                        class="flex items-center px-4 py-3 border-t border-b mb-4 hover:bg-blue-500 hover:text-white leading-none transition-all ease-in-out duration-200"
                        active-class="bg-blue-500 text-white"
                    ><PlusIcon class="h-4 w-4 mr-2"/>New Invoice</router-link>

                    <router-link :to="{ name: 'dashboard' }" class="flex items-center px-4 py-2 leading-none hover:bg-gray-100 transition-all ease-in-out-200" active-class="text-gray-900 bg-gray-200">
                        <ChartSquareBarIcon class="h-5 w-5 mr-3 text-blue-500" />Dashboard
                    </router-link>
                    <router-link :to="{ name: 'invoices.list' }" class="flex items-center px-4 py-2 leading-none hover:bg-gray-100 transition-all ease-in-out-200" active-class="text-gray-900 bg-gray-200">
                        <ReceiptTaxIcon class="h-5 w-5 mr-3 text-green-500" />Invoices
                    </router-link>
                    <router-link :to="{ name: 'customers.list' }" class="flex items-center px-4 py-2 leading-none hover:bg-gray-100 transition-all ease-in-out-200" active-class="text-gray-900 bg-gray-200">
                        <CollectionIcon class="h-5 w-5 mr-3 text-pink-500" />Customers
                    </router-link>
                    <router-link :to="{ name: 'products.list' }" class="flex items-center px-4 py-2 leading-none hover:bg-gray-100 transition-all ease-in-out-200" active-class="text-gray-900 bg-gray-200">
                        <CubeIcon class="h-5 w-5 mr-3 text-yellow-500" />Products
                    </router-link>
                    <router-link :to="{ name: 'settings' }" class="flex items-center px-4 py-2 leading-none hover:bg-gray-100 transition-all ease-in-out-200" active-class="text-gray-900 bg-gray-200">
                        <AdjustmentsIcon class="h-5 w-5 mr-3 text-purple-500" />Settings
                    </router-link>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-200 mt-8 py-4">
            <button class="flex items-center px-4 py-2 text-left hover:text-red-600 transition-all ease-in-out duration-100" @click.prevent="logout">
                <LogoutIcon class="h-5 w-5 mr-2 text-red-500" /> <span class="leading-none font-semibold">Logout</span>
            </button>
        </div>
    </nav>
</template>

<script>
import { computed } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import {
    LogoutIcon, PlusIcon, ChartSquareBarIcon, ReceiptTaxIcon,
    AdjustmentsIcon, CollectionIcon, CubeIcon
} from '@heroicons/vue/outline'
export default {
    components: {
        LogoutIcon,
        PlusIcon,
        ChartSquareBarIcon,
        ReceiptTaxIcon,
        AdjustmentsIcon,
        CollectionIcon,
        CubeIcon
    },
    setup() {
        const store = useStore()
        const router = useRouter()

        let shop = computed(function () {
            return store.getters['user/shop']
        });

        const logout = () => {
            store.dispatch('user/logout').then(() => {
                router.replace({ name: 'login' })
            }).catch((e) => {
                console.log(e)
            })
        }

        return {
            shop,
            logout,
        }
    }

}
</script>
