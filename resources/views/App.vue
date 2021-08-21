<template>
	<!-- <router-view></router-view> -->
    <router-view v-slot="{ Component, route }">
        <!-- <transition :name="route.meta.transition || 'fade'"> -->
        <transition name="fade">
            <component :is="Component" :key="route.path" />
        </transition>
    </router-view>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
export default defineComponent({
	name: 'App',
	components: {
	},
});
</script>
