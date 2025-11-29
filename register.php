<?php
include 'koneksi.php';

// Logika PHP saat tombol Daftar ditekan
if (isset($_POST['daftar'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $wa = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    
    // Hash password agar aman
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Proses Upload Bukti Bayar
    $nama_file = $_FILES['bukti']['name'];
    $tmp_file = $_FILES['bukti']['tmp_name'];
    $ukuran_file = $_FILES['bukti']['size'];
    
    // Rename file agar unik (pake waktu upload)
    $nama_baru = time() . "_" . $nama_file;
    $path = "uploads/" . $nama_baru;

    // Cek apakah email sudah ada?
    $cek_email = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        echo "<script>alert('Email sudah terdaftar! Gunakan email lain.');</script>";
    } else {
        // Cek ada file yang diupload ga?
        if (!empty($nama_file)) {
            // Pindahkan file ke folder uploads
            if (move_uploaded_file($tmp_file, $path)) {
                // Masukkan ke Database
                $sql = "INSERT INTO users (nama_lengkap, email, whatsapp, password, bukti_bayar, status) 
                        VALUES ('$nama', '$email', '$wa', '$password', '$nama_baru', 0)";
                
                if (mysqli_query($conn, $sql)) {
                    // --- SIAPKAN LINK WHATSAPP ---
                    // 1. Masukkan nomor HP Admin (Ganti dengan nomor aslimu, awali dengan 62)
                    $nomor_admin = "62895332572882"; 
                    
                    // 2. Buat pesan otomatis (Nama user diambil dari variabel $nama)
                    $pesan = "Halo Admin, saya baru saja mendaftar di BenahinYuk a.n *$nama*. Saya sudah upload bukti transfer, mohon segera diaktifkan akun saya.";
                    
                    // 3. Encode pesan agar aman di URL (spasi jadi %20, dst)
                    $pesan_encoded = urlencode($pesan);
                    
                    // 4. Gabungkan jadi Link
                    $link_wa = "https://wa.me/$nomor_admin?text=$pesan_encoded";

                    // --- TAMPILKAN ALERT & REDIRECT ---
                    echo "<script>
                        // Pesan yang muncul di Alert
                        alert('✅ Pendaftaran Berhasil! \\n\\nKlik OK untuk langsung konfirmasi ke WhatsApp Admin.');
                        
                        // Setelah klik OK, buka WhatsApp
                        window.location.href = '$link_wa';
                    </script>";
                } else {
                    echo "<script>alert('Error Database: " . mysqli_error($conn) . "');</script>";
                }
            } else {
                echo "<script>alert('Gagal upload gambar.');</script>";
            }
        } else {
            echo "<script>alert('Harap upload bukti pembayaran!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Member - Benahin Yuk</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <nav class="navbar">
        <div class="container nav-container">
            <a href="index.php" style="text-decoration: none;">
                <div class="logo">
                    <i class="fa-solid fa-wrench"></i> benahinyuk
                </div>
            </a>
            <div class="nav-right">
                <a href="login.php" class="nav-link">Login</a>
                <button class="btn btn-orange" style="opacity: 0.5; cursor: default;">Daftar Sekarang</button>
            </div>
        </div>
    </nav>

    <section class="login-section"> 
        <div class="login-container reveal">
            
            <div class="register-card">
                <div class="reg-header">
                    <h2>Daftar Member benahinyuk</h2>
                    <p>Akses Premium Selamanya. Cukup Bayar Satu Kali:</p>
                    <div class="price-tag">Rp 15.000</div>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="email@example.com" required>
                    </div>

                    <div class="form-group">
                        <label>Nomor WhatsApp</label>
                        <input type="tel" name="whatsapp" placeholder="0812 XXXX XXXX" required>
                        <small style="color: #6b7280; font-size: 0.8rem;">Penting untuk konfirmasi pembayaran</small>
                    </div>

                    <div class="form-group">
                        <label>Buat Password</label>
                        <input type="password" name="password" placeholder="Minimal 6 karakter" required>
                    </div>

                    <hr class="divider">

                    <div class="payment-section">
                        <h4>Instruksi Pembayaran</h4>
                        <div class="payment-box">
                            <p class="pay-instruction-text">Silakan transfer Rp 15.000 ke salah satu rekening berikut:</p>
                            
                            <div class="payment-method">
                                <div>
                                    <h5>OVO/DANA/GoPay</h5>
                                    <p class="account-number">0812 XXXX XXXX</p>
                                    <p class="account-name">a.n. Tim benahinyuk</p>
                                </div>
                            </div>

                            <div class="payment-method">
                                <div>
                                    <h5>BCA</h5>
                                    <p class="account-number">12345678</p>
                                    <p class="account-name">a.n. Fulan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Upload Bukti Transfer</label>
                        <div class="upload-area" id="uploadArea">
                            <input type="file" name="bukti" id="fileInput" hidden accept="image/*">
                            <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                            <p id="uploadText">Klik untuk upload atau drag & drop</p>
                            <span class="upload-sub">PNG, JPG hingga 5MB</span>
                        </div>
                    </div>

                    <button type="submit" name="daftar" class="btn btn-blue-full">Daftar Sekarang</button>
                </form>

                <div class="wa-confirm">
                    <p>Setelah klik "Daftar", konfirmasi pembayaran Anda ke WhatsApp Admin:</p>
                    <a href="#">0895332572882</a>
                </div>

                <div class="disclaimer-box">
                    <p><strong>Disclaimer:</strong> Akun Anda akan kami aktifkan secara manual dalam 1x24 jam setelah konfirmasi.</p>
                </div>

            </div>
        </div>
    </section>

    <footer>
        <div class="copyright">
            <p>&copy; 2025 benahinyuk. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="script.js"></script>
    <script>
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const uploadText = document.getElementById('uploadText');

        uploadArea.addEventListener('click', () => { fileInput.click(); });

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                uploadText.innerHTML = `<strong>File Terpilih:</strong> ${this.files[0].name}`;
                uploadArea.style.borderColor = "#22c55e";
                uploadArea.style.backgroundColor = "#f0fdf4";
            }
        });
    </script>
</body>
</html>