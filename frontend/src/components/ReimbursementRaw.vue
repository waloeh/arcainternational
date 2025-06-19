<template>
    <tr :class="{ 'text-danger-override': reimburse.deleted_at }">
        <th scope="row">{{ index + 1 }}</th>
        <td>{{ reimburse.user_name }}</td>
        <td>{{ reimburse.title }}</td>
        <td>{{ reimburse.category_name }}</td>
        <td>{{ formatRupiah(reimburse.amount) }}</td> 
        <td>
            <span :class="['badge', reimburse.status === 'approve' ? 'bg-success' : reimburse.status === 'pending' ? 'bg-warning' : 'bg-danger']">
                {{ reimburse.status }}
            </span>
        </td>
        <td>{{ reimburse.submited_at }}</td>
        <td>{{ reimburse.approved_at ? reimburse.approved_at : '-' }}</td>
        <td>
            <button type="button" class="btn btn-outline-primary btn-sm" @click="detailReimbursement(reimburse)">Detail</button>
            <button v-if="auth.user.role_id == 3 && reimburse.status == 'pending'" class="btn btn-outline-warning btn-sm mx-1" @click="EditReimbursement(reimburse)">Edit</button>
            <button v-if="auth.user.role_id == 3 && reimburse.status == 'pending'"  class="btn btn-outline-danger btn-sm" @click="deleteReimbursement(reimburse.id_reimburse)">Delete</button>
        </td>
    </tr>
</template>
<script setup>
    import { formatRupiah } from '@/utils/format';
    import axios from 'axios';
    import Swal from 'sweetalert2';
    import router from '@/router';
    import { defineProps, defineEmits } from 'vue';
    import { useAuthStore } from '@/stores/authStroe';

    defineProps({
        reimburse: Object,
        index: Number
    })

    const emit = defineEmits(['delete-reimburse'])
    const baseUrl = import.meta.env.VITE_API_BASE_URL
    const user = localStorage.getItem('user')
    const auth = useAuthStore()

    const detailReimbursement = (reimburse) => {
        router.push({
            name: 'detail-reimbursement', 
            params: {id: reimburse.id_reimburse}
        })
    }

    const EditReimbursement = (reimburse) => {
        router.push({
            name: 'form-reimbursement', 
            params: {id: reimburse.id_reimburse}
        })
    }

    const deleteReimbursement = async (id) => {
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
                const token = localStorage.getItem('token')
                const response = await axios.delete(`${baseUrl}/reimbursement/${id}`, {
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

                //kirim emit
                emit('delete-reimburse', id)
            } catch (error) {
                console.log(error)
            }
        }
    }
</script>
<style>
    .text-danger-override td,
    .text-danger-override th {
        color: red !important;
    }
</style>