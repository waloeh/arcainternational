<template>
    <div class="mb-3">
        <label :for="id" class="form-label">{{ label }}</label>
        <select
            :id="id"
            class="form-select"
            :value="modelValue"
            @change="$emit('update:modelValue', $event.target.value)"
        >
            <option disabled value="">{{ placeholder }}</option>
            <option 
                v-for="option in options" 
                :key="option[optionValue]" 
                :value="option[optionValue]"
            >
                {{ option[optionLabel] }}
            </option>
        </select>
        <small v-if="error" class="text-danger">{{ error }}</small>
    </div>
</template>
<script setup>
    defineProps({
        id: String,
        label: String,
        modelValue: [String, Number],
        options: {
            type: Array,
            default: () => [],
        },
        placeholder: {
            type: String,
            default: 'Pilih Salah Satu'
        },
        error: {
            type: String,
            default: '',
        },
        optionValue: {
            type: String,
            default: 'value'
        },
        optionLabel: {
            type: String,
            default: 'label'
        }
    })

    defineEmits(['update:modelValue'])

</script>
<style>
    
</style>