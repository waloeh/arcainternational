import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
     {
      path: '/',
      name: 'home',
      component: () => import('../views/ListReimbursement.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/list-reimbursement',
      name: 'list-reimbursement',
      component: () => import('../views/ListReimbursement.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/category',
      name: 'category',
      component: () => import('../views/Categories.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/form-categori/:id?',
      name: 'form-categori',
      component: () => import('../views/CategoriesForm.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/form-reimbursement/:id?',
      name: 'form-reimbursement',
      component: () => import('../views/ReimbursementForm.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/detail-reimbursement/:id?',
      name: 'detail-reimbursement',
      component: () => import('../views/ReimbursementDetail.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/Login.vue'),
    },
  ],
})

// Global Navigation Guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')

  // Cek apakah route yang akan diakses membutuhkan autentikasi
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!token) {
      // Jika tidak ada token (belum login), arahkan ke halaman login
      next({ name: 'login' })
    } else {
      // Jika ada token (sudah login), lanjutkan ke route yang diminta
      next()
    }
  } else {
    // Jika route tidak memerlukan autentikasi, lanjutkan
    next()
  }
})

export default router
