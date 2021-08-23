<template>
    <vue-final-modal
        v-slot="{ params, close }"
        v-bind="$attrs"
        :esc-to-close="true"
        classes="m-auto max-w-2xl mt-16"
        content-class="relative flex flex-col max-h-full mx-4 p-4 border dark:border-gray-800 rounded bg-white dark:bg-gray-900"
    >
        <span class="mr-8 text-2xl font-bold">
            <slot name="title"></slot>
        </span>

        <div class="flex-grow overflow-y-auto py-4">
            <slot :params="params"></slot>
        </div>

        <!-- <div class="flex-shrink-0 flex justify-center items-center pt-4">
            <button class="vfm-btn" @click="$emit('confirm', close)">confirm</button>
            <button class="vfm-btn" @click="$emit('cancel', close)">cancel</button>
        </div> -->

        <button class="absolute top-0 right-0 mt-2 mr-2" @click="close">
            <XCircleIcon class="w-8 h-8 text-red-500"/>
        </button>
    </vue-final-modal>
</template>

<script>
import { XCircleIcon } from '@heroicons/vue/outline'
export default {
  components: { XCircleIcon },
  inheritAttrs: true,
  emits: ['cancel', 'confirm']
}
</script>
