import axios from "axios";
import { defineStore } from "pinia";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: localStorage.getItem('token') || '',
        user: JSON.parse(localStorage.getItem('user')) || null,
        error: '',
        baseUrl: import.meta.env.VITE_API_BASE_URL
    }),

    actions: {
        async login(email, password) {
            this.error = ''
            try {
                const response = await axios.post(`${this.baseUrl}/login`, {
                    email,
                    password
                })

                const { token, user } = response.data

                localStorage.setItem('token', token)
                localStorage.setItem('user', JSON.stringify(user))
                this.token = token
                this.user = user
            } catch (err) {
                this.error = err.response?.data?.message || 'Not connection to database'
            }
        },

        setToken(token) {
            this.token = token,
            localStorage.setItem('token', token)
        },

        setUser(user) {
            this.user = user,
            localStorage.setItem('user', JSON.stringify(user))
        },

        logout() {
            this.token = ''
            this.user = null
            localStorage.removeItem('token')
            localStorage.removeItem('user')
        }
    }
})