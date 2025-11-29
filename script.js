// 1. EFEK NAVBAR SAAT SCROLL
// Saat pengguna scroll lebih dari 50px, navbar diberi bayangan
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// 2. EFEK SCROLL REVEAL (MUNCUL PERLAHAN)
// Menggunakan 'Mata-mata' (IntersectionObserver) untuk melihat apakah elemen masuk layar
const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        // Jika elemen terlihat di layar
        if (entry.isIntersecting) {
            entry.target.classList.add('active'); // Tambahkan class .active (agar muncul)
        }
    });
}, {
    threshold: 0.1 // Animasi mulai saat 10% elemen terlihat
});

// Cari semua elemen yang punya class '.reveal' dan pasang pengawas
const hiddenElements = document.querySelectorAll('.reveal');
hiddenElements.forEach((el) => observer.observe(el));


// 3. INTERAKSI KLIK (POPUP)
// Interaksi untuk lokasi Gunadarma
const inactiveCard = document.querySelector('.loc-card.inactive');

if (inactiveCard) {
    inactiveCard.addEventListener('click', function() {
        alert("Ops! Wilayah Universitas Gunadarma sedang dalam persiapan. Tunggu kabar baiknya ya!");
    });
}

// Interaksi tombol Login & Daftar
const buttons = document.querySelectorAll('.btn, .nav-link');
buttons.forEach(btn => {
    btn.addEventListener('click', function(e) {
        // Cek jika tombol ini hanyalah link dummy (#)
        if(this.getAttribute('href') === '#') {
            e.preventDefault(); // Jangan refresh halaman
            // Jangan munculkan alert jika itu adalah tombol Gunadarma (karena sudah ada alert khusus di atas)
            if (!this.closest('.loc-card')) { 
                alert("Fitur ini akan segera aktif! Website ini masih dalam tahap demonstrasi.");
            }
        }
    });
});

/* --- LOGIKA HALAMAN LOGIN --- */

const loginForm = document.getElementById('loginForm');

if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah halaman refresh otomatis

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const btn = document.getElementById('loginBtn');
        const originalText = btn.innerText;

        // 1. Validasi Sederhana
        if(email === "" || password === "") {
            alert("Harap isi email dan password!");
            return;
        }

        // 2. Efek Loading (Interaksi)
        btn.innerText = "Memproses...";
        btn.style.opacity = "0.7";
        btn.disabled = true;

        // 3. Simulasi Login (Delay 1.5 detik)
        setTimeout(() => {
            // Kembalikan tombol seperti semula
            btn.innerText = originalText;
            btn.style.opacity = "1";
            btn.disabled = false;

            // Cek Password Dummy (Contoh interaksi)
            if(password.length < 6) {
                alert("Password terlalu pendek! (Minimal 6 karakter)");
            } else {
                alert("Login Berhasil! Selamat datang kembali.");
                // Di sini nanti bisa redirect ke dashboard:
                // window.location.href = "dashboard.html";
            }
        }, 1500);
    });
}

/* --- LOGIKA HALAMAN REGISTER --- */

// 1. Fungsi Copy ke Clipboard
function copyText(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert("Nomor rekening berhasil disalin: " + text);
    }).catch(err => {
        console.error('Gagal menyalin: ', err);
    });
}

// 2. Logika Upload File (Drag & Drop Simulation)
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('fileInput');
const uploadText = document.getElementById('uploadText');

if (uploadArea) {
    // Saat area diklik, buka file dialog
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });

    // Saat file dipilih
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const fileName = this.files[0].name;
            uploadText.innerHTML = `<strong>File Terpilih:</strong> ${fileName}`;
            uploadArea.style.borderColor = "#22c55e"; // Ubah border jadi hijau
            uploadArea.style.backgroundColor = "#f0fdf4";
        }
    });
}

// 3. Submit Form Pendaftaran
const registerForm = document.getElementById('registerForm');

if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validasi File Upload
        if(fileInput.files.length === 0) {
            alert("Mohon upload bukti transfer terlebih dahulu!");
            return;
        }

        const btn = document.getElementById('regBtn');
        const originalText = btn.innerText;

        btn.innerText = "Mengirim Data...";
        btn.style.opacity = "0.7";
        btn.disabled = true;

        // Simulasi Kirim (2 Detik)
        setTimeout(() => {
            alert("Pendaftaran Berhasil! Selamat datang di Dashboard Premium.");
            window.location.href = "dashboard.html";
            btn.style.opacity = "1";
            btn.disabled = false;
            // Reset form
            registerForm.reset();
            uploadText.innerText = "Klik untuk upload atau drag & drop";
            uploadArea.style.borderColor = "#d1d5db";
            uploadArea.style.backgroundColor = "#fdfdfd";
        }, 2000);
    });
}

/* --- LOGIKA DIREKTORI TUKANG --- */

// Cari semua tombol "Buka Kontak"
const lockedButtons = document.querySelectorAll('.btn-locked');

lockedButtons.forEach(btn => {
    btn.addEventListener('click', function() {
        // Tampilkan konfirmasi
        let userWantsToRegister = confirm("Kontak tukang dikunci untuk member premium.\nBiaya pendaftaran hanya Rp 15.000 seumur hidup.\n\nMau daftar sekarang?");
        
        if (userWantsToRegister) {
            window.location.href = "register.html"; // Arahkan ke halaman daftar
        }
    });
});

/* --- DASHBOARD INTERACTION --- */

// Fungsi Tombol WhatsApp
function hubungiWA(namaTukang) {
    // Membuka link API WhatsApp (Simulasi)
    // Di aplikasi nyata: window.open("https://wa.me/62812xxxxxx?text=Halo...");
    alert("Membuka WhatsApp untuk menghubungi " + namaTukang + "...");
}

// Fungsi Tombol Telepon
function hubungiTelp(namaTukang) {
    // Membuka dialer telepon
    // Di aplikasi nyata: window.location.href = "tel:0812xxxxxx";
    alert("Membuka menu telepon untuk menghubungi " + namaTukang + "...");
}