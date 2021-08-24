<template>
    <div>
        <div class="fixed inset-0 md:ml-52 print:m-2 min-h-full overflow-y-auto print:overflow-y-hidden bg-white border-t-4 border-blue-500 transition-all ease-in-out duration-300 print:border-t-0"
            :class="show ? '' : '-mt-10xl'"
        >
            <div class="max-w-6xl mx-auto print:max-w-100 px-4 md:px-0">
                <div class="flex justify-between items-center mt-8 border-b border-gray-200 print:hidden">
                    <div class="text-lg font-bold text-gray-600 py-2">
                        <slot name="header" />
                    </div>
                    <button
                        class="p-2 bg-red-50 hover:bg-red-100 text-red-600 transition-all ease-in-out duration-200"
                        @click="close"
                    >
                        <XIcon class="w-6 h-6"/>
                    </button>
                </div>

                <div class="py-8">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import { CalendarIcon, XIcon } from '@heroicons/vue/outline'

export default {
    components: {
        CalendarIcon,
        XIcon,
    },
    props: {
        show: {
            type: Boolean,
            default: false
        }
    },
    emits: ['confirm', 'close'],
    methods: {
        moment,
        confirm() {
            // some code...
            this.$emit('confirm', true)
        },
        close() {
            this.$emit('close')
        }
    },
}
</script>
