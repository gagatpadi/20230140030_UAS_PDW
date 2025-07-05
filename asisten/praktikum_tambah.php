<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_praktikum = trim($_POST['kode_praktikum']);
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);

    if (empty($kode_praktikum) || empty($nama)) {
        $error = "Kode Praktikum dan Nama Praktikum wajib diisi!";
    } else {
        $stmt = $conn->prepare("INSERT INTO mata_praktikum (kode_praktikum, nama, deskripsi) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $kode_praktikum, $nama, $deskripsi);

        if ($stmt->execute()) {
            header("Location: manajemen_praktikum.php?status=tambah_sukses");
            exit();
        } else {
            if ($conn->errno == 1062) {
                $error = "Gagal menyimpan: Kode Praktikum '{$kode_praktikum}' sudah ada.";
            } else {
                $error = "Terjadi kesalahan saat menyimpan data: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

$pageTitle = 'Tambah Praktikum';
$activePage = 'manajemen_praktikum';
require_once 'templates/header.php';
?>

<div class="bg-gray-800 p-8 rounded-xl shadow-2xl text-gray-100 border border-gray-700 max-w-2xl mx-auto"> <a href="manajemen_praktikum.php" class="inline-flex items-center text-blue-400 hover:text-blue-300 mb-6 transition-colors duration-200"> &larr; Kembali ke Daftar Praktikum
    </a>
    <h2 class="text-3xl font-bold text-gray-50 mb-6 border-b pb-3 border-gray-700">Form Tambah Mata Praktikum</h2> <?php if (!empty($error)): ?>
        <div class="bg-red-800 border border-red-600 text-red-100 px-4 py-3 rounded relative mb-6 text-lg font-medium" role="alert"> <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <form action="praktikum_tambah.php" method="POST">
        <div class="mb-4">
            <label for="kode_praktikum" class="block text-gray-300 text-sm font-semibold mb-2">Kode Praktikum</label> <input type="text" id="kode_praktikum" name="kode_praktikum" class="shadow-sm appearance-none border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200" placeholder="Contoh: PW-01" required> </div>
        <div class="mb-4">
            <label for="nama" class="block text-gray-300 text-sm font-semibold mb-2">Nama Praktikum</label> <input type="text" id="nama" name="nama" class="shadow-sm appearance-none border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200" placeholder="Contoh: Pemrograman Web" required> </div>
        <div class="mb-6">
            <label for="deskripsi" class="block text-gray-300 text-sm font-semibold mb-2">Deskripsi</label> <textarea id="deskripsi" name="deskripsi" rows="4" class="shadow-sm appearance-none border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200" placeholder="Jelaskan singkat mengenai mata praktikum ini"></textarea> </div>
        <div class="flex items-center justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-all duration-300 transform hover:scale-105"> Simpan Praktikum
            </button>
        </div>
    </form>
</div>

<?php
$conn->close();
require_once 'templates/footer.php';
?>