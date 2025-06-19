<template>
    <div class="mb-4">
        <input 
            type="text" 
            :value="modelValue" 
            @input="$emit('update:modelValue', $event.target.value)"
            class="form-control" 
            placeholder="Cari makanan..."
        >
    </div>
</template>
<script setup>
    defineProps({
        modelValue: String
    }) 

    defineEmits(['update:modelValue'])
</script>
<style>
       
</style>