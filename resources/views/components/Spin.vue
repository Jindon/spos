<template>
    <div class="relative">
        <div v-if="spinning" class="z-40 flex items-center justify-center absolute inset-0 min-w-full min-h-full">
            <scale-loader
                :loading="spinning"
                height="30px"
                width="10px"
                color="#598bf9"
            ></scale-loader>
        </div>
        <div :class="spinning ? 'opacity-20' : ''" class="z-10">
            <slot />
        </div>
    </div>
</template>

<script>
import ScaleLoader from 'vue-spinner/src/ScaleLoader.vue'
export default {
    components: {
        ScaleLoader
    },
    props: {
        spinning: {
            type: Boolean,
            default: false
        }
    }
}
</script>

<style>

</style>
