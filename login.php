<?php
session_start();
require_once 'config.php';

// Jika sudah login, redirect ke halaman yang sesuai
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'asisten') {
        header("Location: asisten/dashboard.php");
    } elseif ($_SESSION['role'] == 'mahasiswa') {
        header("Location: mahasiswa/dashboard.php");
    }
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $message = "Email dan password harus diisi!";
    } else {
        $sql = "SELECT id, nama, email, password, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Password benar, simpan semua data penting ke session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['role'] = $user['role'];

                // Logika untuk mengarahkan pengguna berdasarkan peran (role)
                if ($user['role'] == 'asisten') {
                    header("Location: asisten/dashboard.php");
                    exit();
                } elseif ($user['role'] == 'mahasiswa') {
                    header("Location: mahasiswa/dashboard.php");
                    exit();
                } else {
                    // Fallback jika peran tidak dikenali
                    $message = "Peran pengguna tidak valid.";
                }

            } else {
                $message = "Password yang Anda masukkan salah.";
            }
        } else {
            $message = "Akun dengan email tersebut tidak ditemukan.";
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
    <title>Login</title>
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
            color: #63B3ED; /* Warna biru cerah yang kontras */
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
        .form-group input {
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
        .form-group input:focus {
            border-color: #63B3ED; /* Border berubah saat fokus (biru cerah) */
            box-shadow: 0 0 0 3px rgba(99, 179, 237, 0.5); /* Shadow pada fokus */
            outline: none;
        }
        .btn {
            background-color: #4299E1; /* Biru yang kuat */
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
            background-color: #3182CE; /* Warna lebih gelap saat hover */
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
        .message.success {
            color: #68D391; /* Hijau terang untuk sukses */
        }
        .register-link {
            text-align: center;
            margin-top: 30px; /* Margin atas lebih besar */
            font-size: 1em;
            color: #A0AEC0; /* Warna abu-abu terang */
        }
        .register-link a {
            color: #63B3ED; /* Biru cerah untuk link */
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .register-link a:hover {
            color: #4299E1; /* Biru yang lebih kuat saat hover */
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Akun</h2>
        <?php
            if (isset($_GET['status']) && $_GET['status'] == 'registered') {
                echo '<p class="message success">Registrasi berhasil! Silakan login.</p>';
            }
            if (!empty($message)) {
                echo '<p class="message">' . $message . '</p>';
            }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Masuk</button>
        </form>
         <div class="register-link">
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </div>
    </div>
</body>
</html>