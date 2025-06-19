<template>
  <div class="mb-3">
    <label :for="id" class="form-label">{{ label }}</label>
    <input
      :id="id"
      class="form-control"
      type="file"
      accept="image/*"
      @change="handleChange"
    />

    <!-- Preview Image -->
    <div v-if="showPreview && previewUrl" class="mt-2">
      <img v-if="isImage(fileName)"
        :src="previewUrl"
        alt="Preview"
        class="img-thumbnail"
        style="max-height: 150px;"
      />

      <iframe v-if="isPDF(fileName)"
        :src="previewUrl"
        class="img-thumbnail"
        style="max-height: 150px; width: 100%; border: none;"
        allow="fullscreen"
        title="Preview"
      />
    </div>

    <small v-if="error" class="text-danger">{{ error }}</small>
  </div>
</template>

<script setup>
import { ref, watch, onBeforeUnmount } from 'vue'
import { isImage, isPDF } from '@/utils/fileType'

const props = defineProps({
  id: String,
  label: String,
  modelValue: File,
  error: { type: String, default: '' },
  showPreview: { type: Boolean, default: true },
})

const emit = defineEmits(['update:modelValue'])
const previewUrl = ref(null)
const fileName = ref('')

// Bersihkan object URL lama saat file berganti
watch(
  () => props.modelValue,
  (newFile, oldFile) => {
    if (oldFile && previewUrl.value) {
      URL.revokeObjectURL(previewUrl.value)
    }

    if (props.showPreview && newFile) {
      previewUrl.value = URL.createObjectURL(newFile)
      fileName.value = newFile.name
    } else {
      previewUrl.value = null
    }
  },
  { immediate: true }
)

// Bersihkan object URL saat komponen diunmount
onBeforeUnmount(() => {
  if (previewUrl.value) {
    URL.revokeObjectURL(previewUrl.value)
  }
})

function handleChange(event) {
  const file = event.target.files[0] || null
  emit('update:modelValue', file)
}
</script>

<style>
/* Opsional, kamu bisa atur styling di sini */
</style>
