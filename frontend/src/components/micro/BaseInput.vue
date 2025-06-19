<template>
    <div class="mb-3">
        <label :for="id" class="form-label">{{ label }}</label>
        <input 
            :id="id"
            :type="type"
            class="form-control"
            :placeholder="placeholder"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <small v-if="error" class="text-danger">{{ error }}</small>
    </div>
</template>
<script setup>
    defineProps({
        id: String,
        label: String,
        type: {type: String, default: 'text'},
        modelValue: [String, Number],
        placeholder: {type: String, default: ''},
        error: {type: String, default: ''}
    })

    defineEmits(['update:modelValue'])
</script>
<style>
    
</style>