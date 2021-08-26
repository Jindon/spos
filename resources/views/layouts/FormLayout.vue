<template>
    <div class="relative flex min-h-screen text-gray-700">
        <div class="z-10 w-full bg-white">
            <div :class="maxWidth" class="m-auto px-4">
            <div class="pt-6 md:pt-12 mb-4 md:mb-6">
                <button
                    @click="goBack"
                    class="flex items-center font-bold text-sm text-blue-500 mb-6 hover:bg-gray-100 -ml-3 px-3 py-1 rounded"
                >
                    <ArrowNarrowLeftIcon class="h-4 w-4 mr-2"/><span>Go Back</span>
                </button>
                <h2 class="text-xl md:text-3xl font-bold leading-none">
                    {{ headerText }}
                </h2>
            </div>
                <slot />
            </div>
        </div>
         <ReloadPrompt />
    </div>
</template>

<script>
import { useRouter } from 'vue-router'
import { XIcon, ArrowNarrowLeftIcon } from '@heroicons/vue/outline'
import user from '@/scripts/store/modules/user'
import ReloadPrompt from '@/views/components/ReloadPrompt.vue'
export default {
    components: {
        ArrowNarrowLeftIcon,
        XIcon,
        ReloadPrompt,
    },

    props: {
        headerText: {
            type: String,
            default: 'Home',
        },
        maxWidth: {
            type: String,
            default: 'max-w-4xl'
        }
    },

    setup() {
        const router = useRouter()
        const goBack = () => {
            router.back()
        }
        return {
            goBack
        }
    }
}
</script>
