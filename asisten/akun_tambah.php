<?php
// Mulai sesi dan panggil config di atas
session_start();
require_once '../config.php';

// Validasi sesi asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

$error = '';

// Proses form SEBELUM mencetak HTML
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (empty($nama) || empty($email) || empty($password) || empty($role)) {
        $error = "Semua field wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        if ($stmt_check->get_result()->num_rows > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt_insert = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt_insert->bind_param("ssss", $nama, $email, $hashed_password, $role);
            if ($stmt_insert->execute()) {
                header("Location: manajemen_akun.php?status=tambah_sukses");
                exit();
            } else {
                $error = "Gagal membuat akun.";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}

// Setelah semua logika selesai, baru panggil header
$pageTitle = 'Tambah Akun';
$activePage = 'manajemen_akun';
require_once 'templates/header.php';
?>

<div class="bg-gray-800 p-8 rounded-xl shadow-2xl text-gray-100 border border-gray-700 max-w-2xl mx-auto"> <a href="manajemen_akun.php" class="inline-flex items-center text-blue-400 hover:text-blue-300 mb-6 transition-colors duration-200"> &larr; Kembali ke Daftar Akun
    </a>
    <h2 class="text-3xl font-bold text-gray-50 mb-6 border-b pb-3 border-gray-700">Form Tambah Akun Baru</h2> <?php if ($error): ?>
        <div class="bg-red-800 border border-red-600 text-red-100 px-4 py-3 rounded relative mb-6 text-lg font-medium"> <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <form action="akun_tambah.php" method="POST">
        <div class="mb-4">
            <label for="nama" class="block text-gray-300 font-semibold mb-2">Nama Lengkap</label> <input type="text" name="nama" id="nama" class="shadow-sm border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200" required> </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-300 font-semibold mb-2">Email</label> <input type="email" name="email" id="email" class="shadow-sm border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200" required> </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-300 font-semibold mb-2">Password</label> <input type="password" name="password" id="password" class="shadow-sm border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200" required> </div>
        <div class="mb-6">
            <label for="role" class="block text-gray-300 font-semibold mb-2">Role</label> <select name="role" id="role" class="shadow-sm border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required> <option value="mahasiswa" class="bg-gray-800">Mahasiswa</option> <option value="asisten" class="bg-gray-800">Asisten</option> </select>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-all duration-300 transform hover:scale-105"> Simpan Akun
            </button>
        </div>
    </form>
</div>

<?php
$conn->close();
require_once 'templates/footer.php';
?>