<template>
    <Navbar />
    <div class="container mt-4" style="padding-top: 70px;">
        <div class="d-flex align-items-center mb-4">
            <h3 class="mb-0">Data Reimbursement</h3>
        </div>

        <BaseSpinner v-if="isLoading" />

        <div class="row" v-else>
            <div class="col-md-6">
                <fieldset class="border p-4 rounded">
                    <legend class="float-none w-auto px-2">{{ isEdit ? 'Form Edit' : 'Form Tambah' }}</legend>
                    <form @submit.prevent="submit">
                        <BaseSelect 
                            id="kategori"
                            label="Kategori"
                            v-model="form.kategori"
                            :options="kategorOption"
                            option-value="category_id"
                            option-label="name"
                            placeholder="Pilih Kategori"
                            :error="errors.kategori"
                        />

                        <BaseInput 
                            id="title"
                            label="Title"
                            v-model="form.title"
                            placeholder="Title"
                            :error="errors.title"
                        />

                        <BaseInput
                            id="amount"
                            label="amount"
                            v-model="form.amount"
                            placeholder="Input Amount"
                            :error="errors.amount"
                        />

                        <BaseInput 
                            id="ddescription"
                            label="Description"
                            v-model="form.description"
                            placeholder="description"
                            :error="errors.description"
                        />

                        <BaseInputFile
                            id="gambar"
                            label="Gambar"
                            v-model="form.gambar"
                            :error="errors.gambar"
                        />

                        <div v-if="form.imagePreview" class="mb-3">
                            <img v-if="isImage(form.imagePreview)"
                                :src="form.imagePreview"
                                alt="Gambar saat ini"
                                class="img-thumbnail"
                                style="max-height: 150px;"
                            />
                            <iframe v-if="isPDF(form.imagePreview)"
                                :src="form.imagePreview"
                                width="100%"
                                height="600px"
                                style="border: none;"
                                allow="fullscreen"
                            ></iframe>
                        </div>

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
    import BaseSelect from '@/components/micro/BaseSelect.vue';
    import BaseInput from '@/components/micro/BaseInput.vue';
    import axios from 'axios';
    import { onMounted, ref, watch } from 'vue';
    import BaseInputFile from '@/components/micro/BaseInputFile.vue';
    import Swal from 'sweetalert2';
    import { useRoute, useRouter } from 'vue-router';
    import BaseSpinner from '@/components/micro/BaseSpinner.vue';
    import { isPDF, isImage } from '@/utils/fileType';

    const route = useRoute()
    const router = useRouter()
    const isEdit = ref(false)
    const editId = ref(null)
    const baseUrl = import.meta.env.VITE_API_BASE_URL
    const kategorOption = ref([])
    const isLoading = ref(true)
    const form = ref({
        kategori: '',
        title: '',
        amount: '',
        description: '',
        gambar: null,
        imagePreview: ''
    })

    watch (() => form.value.gambar, (newFile) => {
        if (newFile) {
            form.value.imagePreview = ''
        }
    })

    const errors = ref({
        kategori: '',
        title: '',
        amount: '',
        description: '',
        gambar: ''
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
                const response = await axios.get(`${baseUrl}/reimbursement/${editId.value}`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })

                const reimbursement = response.data.data
                form.value.kategori = reimbursement.category_id
                form.value.title = reimbursement.title
                form.value.amount = Number(reimbursement.amount)
                form.value.description = reimbursement.description
                form.value.gambar = null,
                form.value.imagePreview = reimbursement.file_url
            } catch (error) {
                console.log('gagal fetch reimbursement: ', error)
            }
        }

        isLoading.value = false
    })

    const validationRules = {
        kategori: {required: 'kategori wajib diisi'},
        title: {required: 'title wajib diisi'},
        description: {required: 'description wajib diisi'},
        amount: {required: 'amount wajib diisi'},
        gambar: {required: 'gambar wajib diisi'}
    }

    const validate = () => {
        let isValid = true
        for (const field in validationRules) {
            const rules = validationRules[field]
            const value = form.value[field]

            if (field === 'gambar' && isEdit.value) {
                errors.value[field] = ''
                continue
            }

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

            formData.append('category_id', form.value.kategori)
            formData.append('title', form.value.title)
            formData.append('amount', form.value.amount)
            formData.append('description', form.value.description)
            if (form.value.gambar) {
                formData.append('file', form.value.gambar)
            }

            let response
            if (isEdit.value) {
                formData.append('_method', 'PUT')
                response = await axios.post(`${baseUrl}/reimbursement/${editId.value}`, formData, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })
            } else {
                response = await axios.post(`${baseUrl}/reimbursement`, formData, {
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

            router.push('/list-reimbursement')
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
        form.value.title = ''
        form.value.amount = ''
        form.value.description = ''
        form.value.gambar = null
    }
</script>
<style>
    
</style>