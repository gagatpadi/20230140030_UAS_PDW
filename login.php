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
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap'); /* Menggunakan font Poppins dari Google Fonts */

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #6DD5FA, #2980B9); /* Gradient background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.95); /* Sedikit transparan */
            padding: 35px 45px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); /* Shadow lebih menonjol */
            width: 350px;
            text-align: center;
            backdrop-filter: blur(5px); /* Efek blur pada background container */
        }
        h2 {
            text-align: center;
            color: #2980B9; /* Warna biru gelap */
            margin-bottom: 25px;
            font-weight: 600; /* Lebih tebal */
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
        .form-group input {
            width: calc(100% - 20px); /* Menyesuaikan padding */
            padding: 12px 10px;
            border: 1px solid #cce7ff; /* Border lebih soft */
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus {
            border-color: #2980B9; /* Border berubah saat fokus */
            outline: none;
        }
        .btn {
            background-color: #2980B9;
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
            background-color: #1a5276; /* Warna lebih gelap saat hover */
            transform: translateY(-2px); /* Sedikit naik saat hover */
        }
        .message {
            color: #d9534f; /* Merah untuk error */
            text-align: center;
            margin-bottom: 20px;
            font-size: 0.9em;
        }
        .message.success {
            color: #5cb85c; /* Hijau untuk sukses */
        }
        .register-link {
            text-align: center;
            margin-top: 25px;
            font-size: 0.9em;
            color: #777;
        }
        .register-link a {
            color: #2980B9;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .register-link a:hover {
            color: #1a5276;
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