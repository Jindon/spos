<template>
    <div class="relative flex min-h-screen text-gray-700">
        <div class="z-40 w-52 fixed inset-0 bg-gray-50 hidden md:block print:hidden">
            <SideNav />
        </div>
        <div class="z-40 w-full min-h-full absolute bg-gray-50 md:hidden transition-all duration-300 ease-in-out print:hidden" :class="open ? '' : '-ml-780'">
            <button class="absolute right-0 top-2 p-1 pr-3 bg-red-600 text-white text-xs uppercase" @click.prevent="open = false"><XIcon class="w-6 h-6"/></button>
            <button v-if="!open" class="fixed right-0 top-2 p-1 pr-3 bg-gray-900 text-white text-xs uppercase" @click.prevent="open = true"><MenuIcon class="w-6 h-6"/></button>
            <SideNav />
        </div>
        <div class="z-10 md:ml-52 w-full bg-white">
            <div :class="maxWidth" class="m-auto px-4">
                <div class="pt-6 md:pt-12 mb-4 md:mb-6">
                    <slot name="header"/>
                </div>
                <slot />
            </div>
        </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { XIcon, MenuIcon } from '@heroicons/vue/outline'
import SideNav from '@/views/components/SideNav.vue'
export default {
    components: {
        XIcon,
        MenuIcon,
        SideNav,
    },

    props: {
        maxWidth: {
            type: String,
            default: 'max-w-4xl'
        }
    },

    setup() {
        const open = ref(false)
        return {
            open,
        }
    }
}
</script>
