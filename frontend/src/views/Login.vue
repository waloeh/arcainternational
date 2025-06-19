<template>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
      <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
        <h3 class="mb-4 text-center">Login</h3>
  
        <div class="text-center mb-4">
          <img :src="Logo" alt="Logo" style="height: 40px;" />
        </div>
  
        <form @submit.prevent="login">
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input 
              type="email" 
              class="form-control" 
              id="email" 
              v-model="email" 
              placeholder="Enter your email" 
              required
            />
          </div>
  
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input 
              type="password" 
              class="form-control" 
              id="password" 
              v-model="password" 
              placeholder="Enter your password" 
              required
            />
          </div>
  
          <button type="submit" class="btn btn-primary w-100" :disabled="isLoading">
            <span v-if="isLoading" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            {{ isLoading ? 'Loging in...' : 'Login' }}
          </button>
        </form>
        <p v-if="errorMessages" class="error-message">{{ errorMessages }}</p>
      </div>
    </div>
  </template>
  
  <script setup>
    import { ref } from 'vue'
    import Logo from '@/assets/logo.svg'
    import { useAuthStore } from '@/stores/authStroe'
    import { useRouter } from 'vue-router'
  
    const email = ref('')
    const password = ref('')
    const errorMessages = ref('')
    const router = useRouter()
    const isLoading = ref(false)
    const authUser = useAuthStore()
    
    const login = async () => {
      isLoading.value = true
      await authUser.login(email.value, password.value)

      if (authUser.token) {
        router.push({ name: 'list-reimbursement' })
      } else {
        errorMessages.value = authUser.error
      }

      isLoading.value = false
    }
  </script>
  
  <style scoped>

  </style>
  