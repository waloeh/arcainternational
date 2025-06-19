<template>
    <div class="container mt-4" style="padding-top: 70px;">
        <div class="d-flex align-items-center mb-4">
            <h3 class="mb-0">List Reimbursements</h3>
        </div>

        <BaseSpinner v-if="loading" />

        <div v-else>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <!-- Input Search -->
                        <input
                            class="form-control me-2"
                            style="max-width: 300px;"
                            v-model="search"
                            placeholder="Cari..."
                        />

                        <RouterLink v-if="auth.user.role_id == 3" class="btn btn-outline-primary btn-sm ms-auto" :to="{ name: 'form-reimbursement' }">Tambah</RouterLink>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Employee</th>
                        <th scope="col">Tittle</th>
                        <th scope="col">Category</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Status</th>
                        <th scope="col">Submit Date</th>
                        <th scope="col">Approval Date</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <ReimbursementRaw
                        v-for="(reimburse, index) in reimbursements"
                        :key="index"
                        :reimburse="reimburse"
                        :index="index"
                        @delete-reimburse="handleDelete"
                    />
                </tbody>
            </table>
            <nav class="mt-3">
                <ul class="pagination justify-content-end">
                    <!-- Tombol ke halaman pertama -->
                    <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                        <button class="page-link" @click="fetchData(1)" :disabled="pagination.current_page === 1">
                            «
                        </button>
                    </li>

                    <!-- Tombol ke halaman sebelumnya -->
                    <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                        <button class="page-link" @click="fetchData(pagination.current_page - 1)" :disabled="pagination.current_page === 1">
                            ‹
                        </button>
                    </li>

                    <!-- Nomor halaman -->
                    <li
                        class="page-item"
                        v-for="page in pages"
                        :key="page"
                        :class="{ active: pagination.current_page === page }"
                    >
                        <button class="page-link" @click="fetchData(page)">
                            {{ page }}
                        </button>
                    </li>

                    <!-- Tombol ke halaman selanjutnya -->
                    <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
                        <button class="page-link" @click="fetchData(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page">
                            ›
                        </button>
                    </li>

                    <!-- Tombol ke halaman terakhir -->
                    <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
                    <button class="page-link" @click="fetchData(pagination.last_page)" :disabled="pagination.current_page === pagination.last_page">
                        »
                    </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
<script setup>
    import ReimbursementRaw from './ReimbursementRaw.vue';
    import { ref, onMounted, computed, watch } from 'vue';
    import axios from 'axios';
    import Swal from 'sweetalert2';
    import BaseSpinner from './micro/BaseSpinner.vue';
    import debounce from 'lodash/debounce'
    import { useAuthStore } from '@/stores/authStroe';

    const reimbursements = ref([])
    const pagination = ref({
        current_page: 1,
        last_page: 1
    })

    const pages = computed(() => {
        const total = pagination.value.last_page
        const current = pagination.value.current_page
        const delta = 2

        const range = []
        const start = Math.max(1, current - delta)
        const end = Math.min(total, current + delta)

        for (let i = start; i <= end; i++) {
            range.push(i)
        }

        return range
    })

    const search = ref('')
    const baseUrl = import.meta.env.VITE_API_BASE_URL
    const loading = ref(false)
    const token = localStorage.getItem('token')
    const auth = useAuthStore()

    const fetchData = async (page = 1) => {
        loading.value = true
        try {
            const response = await axios.get(`${baseUrl}/reimbursement`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }, 
                params: {
                    page,
                    search: search.value.trim() || undefined
                }
            })
            
            reimbursements.value = response.data.data.data
            pagination.value = {
                current_page: response.data.data.current_page,
                last_page: response.data.data.last_page
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal memuat data',
                text: 'Silahkan hubungi Administrator.',
            })
        } finally {
            loading.value = false
        }
    }

    const debouncedFetch = debounce(() => {
        fetchData(1)
    }, 1000)

    watch(search, () => {
        debouncedFetch()
    })

    onMounted ( async () => {
        fetchData()
    })

    function handleDelete(id) {
        reimbursements.value = reimbursements.value.filter(item => item.id_reimburse !== id)
    }

</script>
<style>
    
</style>