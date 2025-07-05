<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

$error = '';
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: manajemen_praktikum.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_praktikum = trim($_POST['kode_praktikum']);
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);

    if (empty($kode_praktikum) || empty($nama)) {
        $error = "Kode dan Nama Praktikum tidak boleh kosong!";
    } else {
        $stmt = $conn->prepare("UPDATE mata_praktikum SET kode_praktikum = ?, nama = ?, deskripsi = ? WHERE id = ?");
        $stmt->bind_param("sssi", $kode_praktikum, $nama, $deskripsi, $id);

        if ($stmt->execute()) {
            header("Location: manajemen_praktikum.php?status=edit_sukses");
            exit();
        } else {
            $error = "Gagal memperbarui data: " . $stmt->error;
        }
        $stmt->close();
    }
}

$stmt_select = $conn->prepare("SELECT * FROM mata_praktikum WHERE id = ?");
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$praktikum = $result->fetch_assoc();
$stmt_select->close();

if (!$praktikum) {
    echo "Data tidak ditemukan.";
    exit;
}

$pageTitle = 'Edit Praktikum';
$activePage = 'manajemen_praktikum';
require_once 'templates/header.php';
?>

<div class="bg-gray-800 p-8 rounded-xl shadow-2xl text-gray-100 border border-gray-700 max-w-2xl mx-auto"> <a href="manajemen_praktikum.php" class="inline-flex items-center text-blue-400 hover:text-blue-300 mb-6 transition-colors duration-200"> &larr; Kembali ke Daftar Praktikum
    </a>
    <h2 class="text-3xl font-bold text-gray-50 mb-6 border-b pb-3 border-gray-700">Form Edit Mata Praktikum</h2> <?php if (!empty($error)): ?>
        <div class="bg-red-800 border border-red-600 text-red-100 px-4 py-3 rounded relative mb-6 text-lg font-medium" role="alert"> <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <form action="praktikum_edit.php?id=<?php echo $id; ?>" method="POST">
        <div class="mb-4">
            <label for="kode_praktikum" class="block text-gray-300 text-sm font-semibold mb-2">Kode Praktikum</label> <input type="text" id="kode_praktikum" name="kode_praktikum" value="<?php echo htmlspecialchars($praktikum['kode_praktikum']); ?>" class="shadow-sm appearance-none border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200" required> </div>
        <div class="mb-4">
            <label for="nama" class="block text-gray-300 text-sm font-semibold mb-2">Nama Praktikum</label> <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($praktikum['nama']); ?>" class="shadow-sm appearance-none border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200" required> </div>
        <div class="mb-6">
            <label for="deskripsi" class="block text-gray-300 text-sm font-semibold mb-2">Deskripsi</label> <textarea id="deskripsi" name="deskripsi" rows="4" class="shadow-sm appearance-none border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200"><?php echo htmlspecialchars($praktikum['deskripsi']); ?></textarea> </div>
        <div class="flex items-center justify-end">
            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-all duration-300 transform hover:scale-105"> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?php
$conn->close();
require_once 'templates/footer.php';
?>