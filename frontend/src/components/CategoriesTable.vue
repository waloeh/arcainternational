<template>
    <div class="container mt-4" style="padding-top: 70px;">
        <div class="d-flex align-items-center mb-4">
            <h3 class="mb-0">Data Categories</h3>
            <RouterLink class="btn btn-outline-primary btn-sm ms-auto" :to="{ name: 'form-categori' }">Tambah</RouterLink>
        </div>

        <BaseSpinner v-if="loading" />

        <table v-else class="table table-striped table-hover">
            <thead>
                <tr class="align-middle">
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Limit Per Month</th>
                    <th scope="col">Date</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody v-if="categories.length === 0">
                <tr>
                    <td colspan="6" class="text-center">Tidak ada item</td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr v-for="(categori, index) in categories" :key="index" class="align-middle">
                    <th scope="row">{{ index + 1 }}</th>
                    <td>{{ categori.name }}</td>
                    <td>{{ formatRupiah(categori.limit_per_month) }}</td>
                    <td>{{ categori.created_at }}</td>
                    <td>
                        <button class="btn btn-outline-warning btn-sm mx-1" @click="EditItem(categori)">Edit</button>
                        <button class="btn btn-outline-danger btn-sm" @click="deleteItem(categori.category_id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script setup>
    import { formatRupiah } from '@/utils/format';
    import axios from 'axios';
    import Swal from 'sweetalert2';
    import { onMounted, ref } from 'vue';
    import { useRouter } from 'vue-router';
    import BaseSpinner from './micro/BaseSpinner.vue';

    const baseUrl = import.meta.env.VITE_API_BASE_URL
    const token = localStorage.getItem('token')
    const categories = ref([])
    const loading = ref(false)
    const router = useRouter()

    onMounted( async () => {
        loading.value = true
        try {
            const response = await axios.get(`${baseUrl}/category`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            })

            categories.value = response.data.data
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal memuat data',
                text: 'Silahkan hubungi Administrator.',
            })
        } finally {
            loading.value = false
        }
    })

    const EditItem = (cate) => {
        router.push({
            name: 'form-categori', 
            params: {id: cate.category_id}
        })
    }

    const deleteItem = async (id) => {
        const result = await Swal.fire({
            title: 'Yakin akan dihapus?',
            text: 'Data yang sudah dihapus tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        })

        if (result.isConfirmed) {
            try {
                const response = await axios.delete(`${baseUrl}/category/${id}`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })

                Swal.fire({ 
                    title: 'Berhasil!',
                    text: response.data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                })

                categories.value = categories.value.filter(cate => cate.category_id !== id)
            } catch (error) {
                console.log(error)
            }
        }
    }
</script>
<style scoped>

</style>