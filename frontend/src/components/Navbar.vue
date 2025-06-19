<template lang="">
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <RouterLink class="navbar-brand" :to="{ name: 'home' }"><b>AIC</b></RouterLink>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <RouterLink v-if="user_login.role_id == 2" class="nav-link" aria-current="page" :to="{ name: 'category' }" active-class="active">Category</RouterLink>
                </li>
                <li class="nav-item">
                <RouterLink class="nav-link" aria-current="page" :to="{ name: 'list-reimbursement' }" active-class="active">Reimbursement</RouterLink>
                </li>
                <li class="nav-item" v-if="!isLoggedIn">
                    <RouterLink class="nav-link" aria-current="page" :to="{ name: 'login' }">Login</RouterLink>
                </li>
                <li class="nav-item" v-else>
                    <button class="nav-link btn btn-link" @click="logout">Logout</button>
                </li>

            </ul>
            <span>Hi, {{ user_login.username }}</span>
            </div>
        </div>
    </nav>
</template>
<script setup>
    import axios from 'axios';
    import { ref } from 'vue';
    import { useRouter } from 'vue-router';
    import Swal from 'sweetalert2';

    const isLoggedIn = ref(false); 
    const router = useRouter();
    const token = localStorage.getItem('token'); 
    const user_login = JSON.parse(localStorage.getItem('user'))
    const baseUrl = import.meta.env.VITE_API_BASE_URL

    if (token) {
        isLoggedIn.value = true
    }

    const logout = async () => {
        if (token) {
            const result = await Swal.fire({
                title: 'Yakin akan logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Batal'
            })

            if(result.isConfirmed) {
                try {
                    await axios.post(`${baseUrl}/logout`, {}, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        }
                    });
                } catch (error) {
                    console.error('Logout gagal:', error);
                }

                // Menghapus token dari localStorage dan localStorage
                localStorage.removeItem('token');
                localStorage.clear();

                // Mengarahkan pengguna ke halaman login setelah logout
                router.push({ name: 'login' });
            }
        }
    }
</script>

<style scoped>
    
</style>