<template>
    <button
        :type="type"
        class="btn btn-sm"
        :class="[
            variantClass,
            {'disabled': disabled || loading},
        ]"
        :disabled="disabled || loading"
        @click="$emit('click', $event)"
    >
        <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        <slot></slot>
    </button>
</template>
<script setup>
    import { computed } from 'vue';

    defineProps({
        type: {
            type: String,
            default: 'button', //bisa submit atau reset
        },
        variant: {
            type: String,
            default: 'primary',
        },
        loading: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false
        }
    })

    defineEmits(['click'])

    const variantClass = computed(() => {
        switch (variant) {
            case 'primary':
            return 'btn-primary';
            case 'secondary':
            return 'btn-secondary';
            case 'danger':
            return 'btn-danger';
            case 'outline':
            return 'btn-outline-primary';
            case 'success':
            return 'btn-success';
            case 'warning':
            return 'btn-warning';
            default:
            return 'btn-primary';
        }
    })
</script>
<style>
    
</style>