<template>
    <Navbar />
    <div class="container mt-4" style="padding-top: 70px;">
        <div class="d-flex align-items-center mb-4">
            <h3 class="mb-0">Data Categori</h3>
        </div>

        <BaseSpinner v-if="isLoading" />

        <div class="row" v-else>
            <div class="col-md-6">
                <fieldset class="border p-4 rounded">
                    <legend class="float-none w-auto px-2">{{ isEdit ? 'Form Edit' : 'Form Tambah' }}</legend>
                    <form @submit.prevent="submit">
                        <BaseInput 
                            id="categori"
                            label="Nama Kategori"
                            v-model="form.name"
                            placeholder="Masukan Nama Kategori"
                            :error="errors.name"
                        />
                        <BaseInput
                            id="limit_per_month"
                            label="Limit Per Bulan"
                            v-model="form.limit_per_month"
                            placeholder="Masukan Limit"
                            :error="errors.limit_per_month"
                        />

                        <button type="submit" class="btn btn-primary btn-sm mx-1">Submit</button>
                        <button type="button" class="btn btn-danger btn-sm" @click="router.back()">Batal</button>
                    </form>
                </fieldset>
            </div>
        </div>
    </div>
</template>
<script setup>
    import Navbar from '@/components/Navbar.vue';
    import BaseInput from '@/components/micro/BaseInput.vue';
    import axios from 'axios';
    import { onMounted, ref, watch } from 'vue';
    import Swal from 'sweetalert2';
    import { useRoute, useRouter } from 'vue-router';
    import BaseSpinner from '@/components/micro/BaseSpinner.vue';

    const route = useRoute()
    const router = useRouter()
    const isEdit = ref(false)
    const editId = ref(null)
    const baseUrl = import.meta.env.VITE_API_BASE_URL
    const kategorOption = ref([])
    const isLoading = ref(true)
    const form = ref({
        name: '',
        limit_per_month: '',
    })

    const errors = ref({
        name: '',
        limit_per_month: '',
    })

    onMounted( async () => {
        const token = localStorage.getItem('token')
        try {
            const response = await axios.get(`${baseUrl}/category`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            })

            kategorOption.value = response.data.data
        } catch (error) {
            console.log(error)
        }

        if (route.params.id) {
            isEdit.value = true,
            editId.value = route.params.id

            try {
                const response = await axios.get(`${baseUrl}/category/${editId.value}`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })

                const categori = response.data.data
                form.value.name = categori.name
                form.value.limit_per_month = Number(categori.limit_per_month)
            } catch (error) {
                console.log('gagal fetch categori: ', error)
            }
        }

        isLoading.value = false
    })

    const validationRules = {
        name: {required: 'name wajib diisi'},
        limit_per_month: {required: 'limit per month wajib diisi'}
    }

    const validate = () => {
        let isValid = true
        for (const field in validationRules) {
            const rules = validationRules[field]
            const value = form.value[field]

            if (rules.required && !value) {
                errors.value[field] = rules.required
                isValid = false
            } else {
                errors.value[field] = ''
            }
        }
        return isValid
    }

    const submit = async () => {
        if (!validate()) return

        try {
            const token = localStorage.getItem('token')
            const formData = new FormData()

            formData.append('name', form.value.name)
            formData.append('limit_per_month', form.value.limit_per_month)

            let response
            if (isEdit.value) {
                formData.append('_method', 'PUT')
                response = await axios.post(`${baseUrl}/category/${editId.value}`, formData, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })
            } else {
                response = await axios.post(`${baseUrl}/category`, formData, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })
            }

            //Reset error & form
            resetForm()

            Swal.fire({
                title: 'Berhasil!',
                text: response.data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            })

            router.push('/category')
        } catch (error) {
            console.log('gagal submit: ', error)
            Swal.fire({
                title: 'Error!',
                text: error.response.data.message,
                icon: 'error',
                confirmButtonText: 'OK'
            })
        }
    }

    const resetForm = () => {
        Object.keys(errors.value).forEach(key => errors.value[key] = '')

        form.value.kategori = ''
        form.value.namaItem = ''
        form.value.harga = ''
        form.value.gambar = null
    }
</script>
<style>
    
</style>