<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

$id_praktikum = $_GET['id_praktikum'] ?? null;
if (!$id_praktikum) {
    echo "<div class='text-red-400 text-center text-xl mt-10'>ID Praktikum tidak valid.</div>";
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_modul = trim($_POST['nama_modul']);
    $deskripsi = trim($_POST['deskripsi']);
    $file_materi_path = null;

    if (empty($nama_modul)) {
        $error = "Nama modul wajib diisi!";
    } else {
        if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] == UPLOAD_ERR_OK) {
            $file_info = $_FILES['file_materi'];
            $file_name = $file_info['name'];
            $file_tmp_name = $file_info['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $upload_dir = '../uploads/';
            $new_file_name = uniqid('modul_', true) . '.' . $file_ext;
            $destination = $upload_dir . $new_file_name;

            $allowed_ext = ['pdf', 'doc', 'docx'];
            if (!in_array($file_ext, $allowed_ext)) {
                $error = "Format file tidak diizinkan. Harap unggah file PDF, DOC, atau DOCX.";
            } else if (move_uploaded_file($file_tmp_name, $destination)) {
                $file_materi_path = $new_file_name;
            } else {
                $error = "Terjadi kesalahan saat mengunggah file. Cek izin folder 'uploads'.";
            }
        }

        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO modul (id_praktikum, nama_modul, deskripsi, file_materi) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $id_praktikum, $nama_modul, $deskripsi, $file_materi_path);

            if ($stmt->execute()) {
                header("Location: modul.php?id_praktikum=" . $id_praktikum . "&status=tambah_sukses");
                exit();
            } else {
                $error = "Gagal menyimpan data ke database: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$pageTitle = 'Tambah Modul';
$activePage = 'manajemen_praktikum';
require_once 'templates/header.php';
?>

<div class="bg-gray-800 p-8 rounded-xl shadow-2xl text-gray-100 border border-gray-700 max-w-2xl mx-auto"> <a href="modul.php?id_praktikum=<?php echo $id_praktikum; ?>" class="inline-flex items-center text-blue-400 hover:text-blue-300 mb-6 transition-colors duration-200"> &larr; Kembali ke Daftar Modul
    </a>
    <h2 class="text-3xl font-bold text-gray-50 mb-6 border-b pb-3 border-gray-700">Form Tambah Modul Baru</h2> <?php if (!empty($error)): ?>
        <div class="bg-red-800 border border-red-600 text-red-100 px-4 py-3 rounded relative mb-6 text-lg font-medium" role="alert"> <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <form action="modul_tambah.php?id_praktikum=<?php echo $id_praktikum; ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="nama_modul" class="block text-gray-300 text-sm font-semibold mb-2">Nama Modul</label> <input type="text" id="nama_modul" name="nama_modul" class="shadow-sm appearance-none border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200" required> </div>
        <div class="mb-4">
            <label for="deskripsi" class="block text-gray-300 text-sm font-semibold mb-2">Deskripsi Singkat</label> <textarea id="deskripsi" name="deskripsi" rows="3" class="shadow-sm appearance-none border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200"></textarea> </div>
        <div class="mb-6">
            <label for="file_materi" class="block text-gray-300 text-sm font-semibold mb-2">File Materi (PDF/DOCX, maks 5MB)</label> <input type="file" id="file_materi" name="file_materi" class="block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-800 file:text-blue-100 hover:file:bg-blue-700 transition-colors duration-200"> </div>
        <div class="flex items-center justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-all duration-300 transform hover:scale-105"> Simpan Modul
            </button>
        </div>
    </form>
</div>

<?php
$conn->close();
require_once 'templates/footer.php';
?>