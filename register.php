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
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap'); /* Menggunakan font Poppins dari Google Fonts */

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #A8E6CF, #DCE2E7); /* Gradient background yang lebih soft */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 35px 45px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
            backdrop-filter: blur(5px);
        }
        h2 {
            text-align: center;
            color: #28a745; /* Warna hijau untuk registrasi */
            margin-bottom: 25px;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 400;
        }
        .form-group input, .form-group select {
            width: calc(100% - 20px);
            padding: 12px 10px;
            border: 1px solid #cce7ff;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #28a745;
            outline: none;
        }
        .btn {
            background-color: #28a745;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 1.1em;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        .message {
            color: #d9534f;
            text-align: center;
            margin-bottom: 20px;
            font-size: 0.9em;
        }
        .login-link {
            text-align: center;
            margin-top: 25px;
            font-size: 0.9em;
            color: #777;
        }
        .login-link a {
            color: #28a745;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .login-link a:hover {
            color: #218838;
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