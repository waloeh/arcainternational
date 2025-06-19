<template lang="">
    <Navbar />
        <div class="container mt-4" style="padding-top: 70px;">
        <div class="d-flex align-items-center mb-4">
            <h3 class="mb-0">Data Reimbursement</h3>
        </div>

        <BaseSpinner v-if="isLoading" />

        <div class="row" v-else>
            <div class="col-md-6">
                <fieldset class="border p-4 rounded">
                    <legend class="float-none w-auto px-2">Detail Reimbursement</legend>
                    <Card 
                        v-if="reimbursement"
                        :reimbursement= "reimbursement"
                        @aksi= "handleAction"
                    />
                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-sm btn-secondary"  @click="router.back()">Back</button>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</template>
<script setup>
    import Navbar from '@/components/Navbar.vue';
    import Card from '@/components/Card.vue';
    import BaseSpinner from '@/components/micro/BaseSpinner.vue';
    import axios from 'axios';
    import { useRoute, useRouter } from 'vue-router';
    import { onMounted, ref } from 'vue';
    import Swal from 'sweetalert2';

    const baseUrl = import.meta.env.VITE_API_BASE_URL
    const route = useRoute()
    const router = useRouter();
    const editId = ref(route.params.id)
    const isLoading = ref(true)

    const reimbursement = ref([])
    onMounted(async () => {
        try {
            const token = localStorage.getItem('token')
            const response = await axios.get(`${baseUrl}/reimbursement/${editId.value}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            })

            reimbursement.value = response.data.data
        } catch (error) {
            console.log('gagal fetch reimbursement: ', error)
        } finally {
            isLoading.value = false;
        }
    })

    const handleAction = async (id, plug) => {
        const confirm = await Swal.fire({
            title: plug + ' reimbursement?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        });

        if (confirm.isConfirmed) {
            try {
            const token = localStorage.getItem('token');
            const response = await axios.patch(`${baseUrl}/reimbursement/${id}`, {status: plug}, {
                headers: {
                Authorization: `Bearer ${token}`
                }
            });

            Swal.fire('Success!', response.data.message, 'success');
                router.push('/list-reimbursement');
            } catch (error) {
                console.error('Gagal approve:', error);
                Swal.fire('Error', 'Terjadi kesalahan saat menyetujui.', 'error');
            }
        }
    };

</script>
<style>

</style>