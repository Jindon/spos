<template>
    <div class="flex items-center justify-between my-8">
        <div class="w-1/3 text-sm text-gray-500">
            <p>Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }} items.</p>
            <p class="text-xs">{{ meta.per_page }} items per page.</p>
        </div>
        <div class="flex-1 flex items-center justify-end">
            <button
                class="py-2 px-3 text-sm font-semibold leading-none rounded-md hover:bg-gray-100 text-gray-500 transition-all ease-in-out duration-200"
                :disabled="(currentPage - 1) === 0"
                :class="(currentPage - 1) === 0 ? 'cursor-not-allowed opacity-50' : ''"
                @click.prevent="changePage(currentPage - 1)"
            ><ArrowNarrowLeftIcon class="w-5 h-5"/></button>

            <div v-for="page in meta.last_page" :key="page.label">
                <button
                    class="py-2 px-3 text-sm font-semibold leading-none rounded-md transition-all ease-in-out duration-200"
                    :class="page === meta.current_page ? 'bg-gray-700 text-white' : 'text-gray-500 hover:bg-gray-100'"
                    @click.prevent="changePage(page)"
                >{{ page }}</button>
            </div>

            <button
                class="py-2 px-3 text-sm font-semibold leading-none rounded-md hover:bg-gray-100 text-gray-500 transition-all ease-in-out duration-200"
                :class="(currentPage + 1) > meta.last_page ? 'cursor-not-allowed opacity-50' : ''"
                :disabled="(currentPage + 1) > meta.last_page"
                @click.prevent="changePage(currentPage + 1)"
            ><ArrowNarrowRightIcon class="w-5 h-5"/></button>
        </div>
    </div>
</template>

<script>
import { ArrowNarrowLeftIcon, ArrowNarrowRightIcon } from '@heroicons/vue/outline'
export default {
    props: {
        meta: Object,
        currentPage: Number,
    },
    components: {
        ArrowNarrowLeftIcon,
        ArrowNarrowRightIcon
    },
    emits: ['change'],
    methods: {
        changePage(page) {
            this.$emit('change', page)
        }
    }
}
</script>

<style>

</style>
