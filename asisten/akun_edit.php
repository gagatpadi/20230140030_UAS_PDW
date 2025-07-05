<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

$id_user = $_GET['id'] ?? null;
if (!$id_user) {
    header("Location: manajemen_akun.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);
    $password = trim($_POST['password']);

    $sql = "UPDATE users SET nama = ?, email = ?, role = ?";
    $types = "sss";
    $params = [$nama, $email, $role];

    if (!empty($password)) {
        $sql .= ", password = ?";
        $types .= "s";
        $params[] = password_hash($password, PASSWORD_BCRYPT);
    }

    $sql .= " WHERE id = ?";
    $types .= "i";
    $params[] = $id_user;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        header("Location: manajemen_akun.php?status=edit_sukses");
        exit();
    } else {
        $error = "Gagal memperbarui akun: " . $stmt->error;
    }
    $stmt->close();
}

$stmt_get = $conn->prepare("SELECT nama, email, role FROM users WHERE id = ?");
$stmt_get->bind_param("i", $id_user);
$stmt_get->execute();
$user = $stmt_get->get_result()->fetch_assoc();
$stmt_get->close();

if (!$user) {
    echo "<div class='text-red-400 text-center text-xl mt-10'>Akun tidak ditemukan.</div>";
    exit;
}

$pageTitle = 'Edit Akun';
$activePage = 'manajemen_akun';
require_once 'templates/header.php';
?>

<div class="bg-gray-800 p-8 rounded-xl shadow-2xl text-gray-100 border border-gray-700 max-w-2xl mx-auto"> <a href="manajemen_akun.php" class="inline-flex items-center text-blue-400 hover:text-blue-300 mb-6 transition-colors duration-200"> &larr; Kembali ke Daftar Akun
    </a>
    <h2 class="text-3xl font-bold text-gray-50 mb-6 border-b pb-3 border-gray-700">Form Edit Akun</h2> <?php if ($error): ?> <div class="bg-red-800 text-red-100 p-4 mb-6 rounded-lg border border-red-600"><?php echo htmlspecialchars($error); ?></div> <?php endif; ?> <form action="akun_edit.php?id=<?php echo $id_user; ?>" method="POST">
        <div class="mb-4">
            <label for="nama" class="block text-gray-300 font-semibold mb-2">Nama Lengkap</label> <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" class="shadow-sm border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200" required> </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-300 font-semibold mb-2">Email</label> <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="shadow-sm border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200" required> </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-300 font-semibold mb-2">Password Baru (Opsional)</label> <input type="password" name="password" id="password" class="shadow-sm border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200" placeholder="Kosongkan jika tidak ingin diubah"> </div>
        <div class="mb-6">
            <label for="role" class="block text-gray-300 font-semibold mb-2">Role</label> <select name="role" id="role" class="shadow-sm border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required> <option value="mahasiswa" class="bg-gray-800" <?php echo ($user['role'] == 'mahasiswa') ? 'selected' : ''; ?>>Mahasiswa</option> <option value="asisten" class="bg-gray-800" <?php echo ($user['role'] == 'asisten') ? 'selected' : ''; ?>>Asisten</option> </select>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-all duration-300 transform hover:scale-105"> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?php
$conn->close();
require_once 'templates/footer.php';
?>