<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

$id_laporan = $_GET['id'] ?? null;
if (!$id_laporan) {
    echo "<div class='text-red-400 text-center text-xl mt-10'>ID Laporan tidak valid.</div>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nilai = $_POST['nilai'];
    $feedback = $_POST['feedback'];
    $stmt = $conn->prepare("UPDATE laporan SET nilai = ?, feedback = ?, status = 'Dinilai' WHERE id = ?");
    $stmt->bind_param("isi", $nilai, $feedback, $id_laporan);
    if ($stmt->execute()) {
        header("Location: laporan.php?status_aksi=nilai_sukses"); // Ubah status ke status_aksi
    } else {
        header("Location: laporan.php?status_aksi=nilai_gagal"); // Ubah status ke status_aksi
    }
    $stmt->close();
    exit(); // Pastikan exit setelah header
}

$stmt_get = $conn->prepare("SELECT l.*, u.nama as nama_mahasiswa, m.nama_modul
                        FROM laporan l
                        JOIN users u ON l.id_mahasiswa = u.id
                        JOIN modul m ON l.id_modul = m.id
                        WHERE l.id = ?");
$stmt_get->bind_param("i", $id_laporan);
$stmt_get->execute();
$laporan = $stmt_get->get_result()->fetch_assoc();
$stmt_get->close();

if (!$laporan) {
    echo "<div class='text-red-400 text-center text-xl mt-10'>Laporan tidak ditemukan.</div>";
    exit;
}

$pageTitle = 'Beri Nilai Laporan';
$activePage = 'laporan';
require_once 'templates/header.php';
?>

<div class="bg-gray-800 p-8 rounded-xl shadow-2xl text-gray-100 border border-gray-700 max-w-2xl mx-auto"> <a href="laporan.php" class="inline-flex items-center text-blue-400 hover:text-blue-300 mb-6 transition-colors duration-200"> &larr; Kembali ke Daftar Laporan
    </a>
    <h2 class="text-3xl font-bold text-gray-50 mb-6 border-b pb-3 border-gray-700">Penilaian Laporan</h2> <div class="border-b border-gray-700 pb-4 mb-6"> <p class="mb-2"><strong class="text-gray-300">Mahasiswa:</strong> <span class="text-gray-100"><?php echo htmlspecialchars($laporan['nama_mahasiswa']); ?></span></p> <p class="mb-2"><strong class="text-gray-300">Modul:</strong> <span class="text-gray-100"><?php echo htmlspecialchars($laporan['nama_modul']); ?></span></p> <p class="mb-2"><strong class="text-gray-300">File Laporan:</strong>
            <a href="../laporan/<?php echo htmlspecialchars($laporan['file_laporan']); ?>" download class="text-blue-400 hover:underline hover:text-blue-300 transition-colors duration-200">
                Unduh Laporan
            </a>
        </p>
    </div>

    <form action="laporan_nilai.php?id=<?php echo $id_laporan; ?>" method="POST">
        <div class="mb-4">
            <label for="nilai" class="block text-gray-300 text-sm font-semibold mb-2">Nilai (0-100)</label> <input type="number" name="nilai" id="nilai" min="0" max="100" value="<?php echo htmlspecialchars($laporan['nilai'] ?? ''); ?>" class="shadow-sm appearance-none border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200" required> </div>
        <div class="mb-6">
            <label for="feedback" class="block text-gray-300 text-sm font-semibold mb-2">Feedback (Opsional)</label> <textarea name="feedback" id="feedback" rows="4" class="shadow-sm appearance-none border border-gray-600 rounded w-full py-2.5 px-3 bg-gray-900 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 transition-colors duration-200"><?php echo htmlspecialchars($laporan['feedback'] ?? ''); ?></textarea> </div>
        <div class="flex items-center justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-all duration-300 transform hover:scale-105"> Simpan Nilai
            </button>
        </div>
    </form>
</div>

<?php
$conn->close();
require_once 'templates/footer.php';
?>