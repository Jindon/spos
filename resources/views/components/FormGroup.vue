<template>
    <div class="flex flex-col">
        <label
            v-if="label"
            :for="labelFor"
            class="form-label"
            :class="required ? 'required' : ''"
        >{{ label }}</label>
        <slot />
        <span v-if="error" class="form-error">{{ error }}</span>
    </div>
</template>

<script>
export default {
    props: {
        error: String,
        label: String,
        labelFor: String,
        required: Boolean
    }
}
</script>
