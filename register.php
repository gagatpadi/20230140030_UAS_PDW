<?php
require_once 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // Validasi sederhana
    if (empty($nama) || empty($email) || empty($password) || empty($role)) {
        $message = "Semua field harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format email tidak valid!";
    } elseif (!in_array($role, ['mahasiswa', 'asisten'])) {
        $message = "Peran tidak valid!";
    } else {
        // Cek apakah email sudah terdaftar
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Email sudah terdaftar. Silakan gunakan email lain.";
        } else {
            // Hash password untuk keamanan
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Simpan ke database
            $sql_insert = "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssss", $nama, $email, $hashed_password, $role);

            if ($stmt_insert->execute()) {
                header("Location: login.php?status=registered");
                exit();
            } else {
                $message = "Terjadi kesalahan. Silakan coba lagi.";
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pengguna</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap'); /* Menggunakan font Poppins, tambahkan 700 */

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1A202C, #2D3748); /* Gradient background gelap yang elegan */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #E2E8F0; /* Warna teks default terang */
        }
        .container {
            background-color: rgba(30, 41, 59, 0.9); /* Sedikit transparan dan lebih gelap */
            padding: 40px 50px; /* Padding lebih besar */
            border-radius: 15px; /* Border radius lebih besar */
            box-shadow: 0 15px 30px rgba(0,0,0,0.4); /* Shadow lebih menonjol dan gelap */
            width: 400px; /* Lebar container lebih besar */
            text-align: center;
            backdrop-filter: blur(8px); /* Efek blur lebih kuat */
            border: 1px solid rgba(74, 85, 104, 0.5); /* Border tipis untuk definisi */
        }
        h2 {
            text-align: center;
            color: #68D391; /* Warna hijau cerah yang kontras untuk registrasi */
            margin-bottom: 30px; /* Margin bawah lebih besar */
            font-weight: 700; /* Lebih tebal */
            font-size: 2.2em; /* Ukuran font lebih besar */
        }
        .form-group {
            margin-bottom: 25px; /* Margin bawah lebih besar */
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px; /* Margin bawah lebih besar */
            color: #A0AEC0; /* Warna abu-abu terang untuk label */
            font-weight: 500; /* Sedikit lebih tebal */
            font-size: 1.1em; /* Ukuran font label lebih besar */
        }
        .form-group input, .form-group select {
            width: calc(100% - 24px); /* Menyesuaikan padding */
            padding: 14px 12px; /* Padding lebih besar */
            border: 1px solid #4A5568; /* Border gelap */
            border-radius: 8px; /* Border radius lebih besar */
            box-sizing: border-box;
            font-size: 1.05em; /* Ukuran font input lebih besar */
            background-color: #2D3748; /* Background input gelap */
            color: #E2E8F0; /* Teks input terang */
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #68D391; /* Border berubah saat fokus (hijau cerah) */
            box-shadow: 0 0 0 3px rgba(104, 211, 145, 0.5); /* Shadow pada fokus */
            outline: none;
        }
        .btn {
            background-color: #48BB78; /* Hijau yang kuat */
            color: white;
            padding: 15px; /* Padding lebih besar */
            border: none;
            border-radius: 8px; /* Border radius lebih besar */
            cursor: pointer;
            width: 100%;
            font-size: 1.2em; /* Ukuran font tombol lebih besar */
            font-weight: 700; /* Lebih tebal */
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            margin-top: 20px; /* Margin atas lebih besar */
            letter-spacing: 0.05em; /* Spasi antar huruf */
        }
        .btn:hover {
            background-color: #38A169; /* Warna lebih gelap saat hover */
            transform: translateY(-3px); /* Sedikit naik saat hover */
            box-shadow: 0 8px 15px rgba(0,0,0,0.3); /* Shadow saat hover */
        }
        .message {
            color: #FC8181; /* Merah terang untuk error */
            text-align: center;
            margin-bottom: 20px;
            font-size: 1em;
            font-weight: 600;
        }
        .login-link {
            text-align: center;
            margin-top: 30px; /* Margin atas lebih besar */
            font-size: 1em;
            color: #A0AEC0; /* Warna abu-abu terang */
        }
        .login-link a {
            color: #68D391; /* Hijau cerah untuk link */
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .login-link a:hover {
            color: #48BB78; /* Hijau yang lebih kuat saat hover */
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Daftar Akun Baru</h2>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Daftar Sebagai</label>
                <select id="role" name="role" required>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="asisten">Asisten</option>
                </select>
            </div>
            <button type="submit" class="btn">Daftar Sekarang</button>
        </form>
        <div class="login-link">
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</body>
</html>