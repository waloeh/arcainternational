  <template>
    <div class="card h-100">
      <!-- Image -->
      <img
        v-if="isImage(reimbursement.file_url)"
        :src="reimbursement.file_url"
        class="card-img-top"
        :alt="reimbursement.title"
        style="max-width: 100%; height: auto;"
      >

      <!-- Pdf -->
      <iframe v-if="isPDF(reimbursement.file_url)"
        :src="reimbursement.file_url"
        width="100%"
        height="600px"
        style="border: none;"
        allow="fullscreen"
      ></iframe>

      <div class="card-body py-2">
        <h5 class="card-title mb-1">{{ reimbursement.title }}</h5>
        <p class="card-text text-muted mb-1">{{ reimbursement.description }}</p>
        <div v-if="auth.user.role_id != 3">
          <div v-if="reimbursement.status == 'pending' && auth.user.role_id == 1">
            <button class="btn btn-sm btn-success me-2" @click="handleApprove">Approve</button>
            <button class="btn btn-sm btn-danger" @click="handleEject">Inject</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
    import { isPDF, isImage } from '@/utils/fileType'
    import { useAuthStore } from '@/stores/authStroe'

    const props = defineProps({
      reimbursement: Object
    })

    const auth = useAuthStore()
    const emit = defineEmits(['aksi'])

    const handleApprove = () => {
      emit('aksi', props.reimbursement.id_reimburse, 'approve')
    }

    const handleEject = () => {
      emit('aksi', props.reimbursement.id_reimburse, 'reject')
    }
  </script>

  <style scoped>
    .card-img-top {
      height: 200px; /* Atur tinggi sesuai kebutuhan */
      object-fit: cover;
      object-position: center;
    }
  </style>
  