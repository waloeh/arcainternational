import 'bootstrap/dist/css/bootstrap.min.css'  
import 'bootstrap'                             
import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import { formatRupiah } from './utils/format'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)

app.mount('#app')
