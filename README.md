# Reimbursement App
Aplikasi Reimbursement menggunakan **Laravel 11** (Backend) dan **Vue 3** (Frontend) berbasis SPA.

## Requirements
- PHP ≥ 8.2
- Composer ≥ 2.x
- Node.js ≥ 18.x
- NPM ≥ 9.x
- MySQL 

## Arsitektur Solusi
    Frontend Vue 3.5.13
        - Komunikasi dengan backend via Axios (HTTP request)
        - Menggunakan Pinia untuk state management (data user login)
        - Routing pakai Vue Router
        - bootstrap untuk styling Frontend
        - sweetalert2 untuk pop up
        - Autentikasi dengan token sanctum (dari backend)
        - vitejs untuk fast development Hot Module Replacement
    
    Backend laravel 11
        - Menyediakan API
        - generate token sanctum
        - semua endpoint di pasang validasi
        - migration untuk generate database
        - seeder untuk data dummy
        - Queue untuk antrian send email (jalan di bacground secara asyncronus)
        - symlink untuk file bisa di akses dengan aman
    
    * User login ke system untuk mendapatkan token
    * Tokenuser dan data user disimpan di state frontend
    * User Selalu gunakan token yang valid untuk CRUD data
    * Jika user akses url, sebelum melewati login maka aka redirect ke page login

## Penjelasan Desain
    - Aplikasi dibuat terpisah antara Frontend dan Backend (Laravel, Vue)
    - Dengan framework laravel, lebih cepat dalam proses development, karena sudah banyak dependency yang dibutuhkan tersedia
    - Arsitektur di laravel, saya gunakan MVC + Repository Layer, untuk memisahkan anatara logika akses data dengan logika bisnis, membuat kode tetap rapi, mudah diuji, dan fleksibel 
    - Dengan Vue, website sudah SPA (website modern dengan performa cepat)
    - Email menggunakan smtp gmail dengan email arcainternational652@gmail.com

    - Employee create reimburse, data masuk ke DB dan kirim email secara asyncronus ke semua akun dengan role manager
    - Employee bisa update data yang sudah dibuat dan bisa hapus, sebelum di approve atau di reject oleh manager 
    - Employee bisa lihat list data reimbursement, yang milik sendiri 
    - Ketika submit remiburse, system akan hitung total amount reimburse pada kategori yang dipilh, pada bulan saat submit, yang sudah statusnya sudah di approve, jika nilai amount reimburse lebih besar, dari nilai limit per bulan, pada kategori yang di pilh, dikurangi total amount yang sudah di approve selama satu bulan, maka akan gagal
    - Admin bisa melihat semua list data riembursement baik yang sudah dihapus atau tidak (yang sudah dihapus diberi tanda merah pada text nya)
    - Manager bisa lihat semua list reimbursement dan bisa approve/reject
    - Manager akan gagal approve, jika amount reimburse melebihi amount limit kategori reimburse, dikurangi total amount yang sudah di approve selama satu bulan tersebut
    - Fitur CRUD kategori hanya akan muncul ketika login sebagai admin
    - jenis file yang bisa di upload ke system hanya format pdf dan image

## Setup & Deploy
    - git clone https://github.com/waloeh/arcainternational.git

1. **Di Sisi Backend**
    - masuk ke direktori arcainternational\backend
    - composer install
    - rename file .env.example menjadi .env
    - php artisan key:generate
    - buat database baru di mysql dengan nama reimbursement_db
    - php artisan migrate
    - php artisan db:seed
    - php artisan storage:link 
    - php artisan serve
    - buka terminal baru dan jalankan php artisan queue:work

2. **Di sisi Frontend**
    - masuk ke direktori arcainternational\frontend
    - npm install
    - npm run dev

3. **Url & Akun Login**
    - base_url: http://localhost:8000/api (kebutuhan untuk di environments variable, di postman)
    - url aplikasi: http://localhost:5173
    - Token menggunakan Bearer
    - akun manager:
        email: mulyana14101990@gmail.com
        pass: password
    - akun admin: 
        email: admin@example.com
        pass: password
    - akun employee:
        email: employee@example.com
        pass: password

        email: employee2@example.com
        pass: password

## Tantangan & Solusi

1. **Tantangan**
    - Dengan posisi saya pada saat ini masih sebagai karyawan aktif, saya merasa terkendala di waktu pengerjaan, yang mana, saya masih punya kewajiban untuk menuntaskan tiket tiket kerjaan saya.

2. **Solusi**
    - Saya harus extra time, untuk mengerjakannya, saya bergadang setelah beres jam kerja saya dan dilanjutkan di waktu pagi sebelum berangkat kerja, untuk bisa selesai sesuai dengan waktu yang sudah diberikan.

    

    


