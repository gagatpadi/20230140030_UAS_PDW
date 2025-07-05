<?php
$pageTitle = 'Manajemen Modul';
$activePage = 'manajemen_praktikum'; // Tetap aktif di manajemen praktikum
require_once 'templates/header.php';
require_once '../config.php';

// Ambil ID praktikum dari URL
$id_praktikum = $_GET['id_praktikum'] ?? null;
if (!$id_praktikum) {
    echo "<div class='text-red-400 text-center text-xl mt-10'>ID Praktikum tidak valid.</div>";
    exit;
}

// Ambil nama praktikum untuk judul halaman
$stmt_praktikum = $conn->prepare("SELECT nama FROM mata_praktikum WHERE id = ?");
$stmt_praktikum->bind_param("i", $id_praktikum);
$stmt_praktikum->execute();
$result_praktikum = $stmt_praktikum->get_result();
$praktikum = $result_praktikum->fetch_assoc();
$nama_praktikum = $praktikum['nama'] ?? 'Praktikum Tidak Ditemukan'; // Handle jika praktikum tidak ada

// Ambil semua modul yang terkait dengan praktikum ini
$stmt_modul = $conn->prepare("SELECT * FROM modul WHERE id_praktikum = ? ORDER BY created_at ASC");
$stmt_modul->bind_param("i", $id_praktikum);
$stmt_modul->execute();
$result_modul = $stmt_modul->get_result();

?>

<div class="bg-gray-800 p-8 rounded-xl shadow-2xl text-gray-100 border border-gray-700"> <a href="manajemen_praktikum.php" class="inline-flex items-center text-blue-400 hover:text-blue-300 mb-6 transition-colors duration-200"> &larr; Kembali ke Daftar Praktikum
    </a>

    <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-700"> <div>
            <h2 class="text-3xl font-bold text-gray-50 tracking-tight">Daftar Modul</h2> <p class="text-gray-400 mt-1">Untuk Praktikum: <span class="font-semibold text-gray-300"><?php echo htmlspecialchars($nama_praktikum); ?></span></p> </div>
        <a href="modul_tambah.php?id_praktikum=<?php echo $id_praktikum; ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105"> <svg class="w-5 h-5 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Modul Baru
        </a>
    </div>

    <?php if (isset($_GET['status'])): ?>
        <div class="mb-8 p-4 rounded-lg text-lg font-medium
            <?php
            if ($_GET['status'] == 'tambah_sukses' || $_GET['status'] == 'edit_sukses' || $_GET['status'] == 'hapus_sukses') {
                echo 'bg-green-800 text-green-100 border border-green-600'; // Notifikasi sukses hijau gelap elegan
            } else {
                echo 'bg-red-800 text-red-100 border border-red-600'; // Notifikasi gagal merah gelap elegan
            }
            ?>">
            <?php
            if ($_GET['status'] == 'tambah_sukses') echo 'Modul berhasil ditambahkan dengan sukses!';
            if ($_GET['status'] == 'edit_sukses') echo 'Modul berhasil diperbarui!';
            if ($_GET['status'] == 'hapus_sukses') echo 'Modul berhasil dihapus secara permanen.';
            if ($_GET['status'] == 'hapus_gagal') echo 'Terjadi kesalahan saat menghapus modul.';
            ?>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto rounded-lg shadow-xl border border-gray-700"> <table class="min-w-full bg-gray-900"> <thead class="bg-gray-950 text-gray-200 uppercase text-sm leading-normal border-b border-gray-700"> <tr>
                    <th class="w-1/3 text-left py-4 px-6 font-bold">Nama Modul</th> <th class="w-1/3 text-left py-4 px-6 font-bold">File Materi</th> <th class="text-left py-4 px-6 font-bold">Aksi</th> </tr>
            </thead>
            <tbody class="text-gray-300 divide-y divide-gray-700"> <?php if ($result_modul->num_rows > 0): ?>
                    <?php while($modul = $result_modul->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-700 transition-colors duration-200"> <td class="py-4 px-6 font-medium"><?php echo htmlspecialchars($modul['nama_modul']); ?></td> <td class="py-4 px-6"> <?php if (!empty($modul['file_materi'])): ?>
                                    <a href="../uploads/<?php echo htmlspecialchars($modul['file_materi']); ?>" target="_blank" class="text-blue-400 hover:underline hover:text-blue-300 transition-colors duration-200">
                                        <?php echo htmlspecialchars($modul['file_materi']); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-500">Belum diunggah</span> <?php endif; ?>
                            </td>
                            <td class="py-4 px-6 flex items-center space-x-3"> <a href="modul_edit.php?id=<?php echo $modul['id']; ?>" class="flex items-center bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-1.5 px-4 rounded-full transition-colors duration-300 shadow-md">Edit</a>
                                <a href="modul_hapus.php?id=<?php echo $modul['id']; ?>" class="flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold py-1.5 px-4 rounded-full transition-colors duration-300 shadow-md" onclick="return confirm('Yakin ingin menghapus modul ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center py-10 text-gray-400 text-xl font-semibold"> Belum ada modul untuk praktikum ini. Tambahkan yang pertama sekarang!
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$stmt_praktikum->close();
$stmt_modul->close();
$conn->close();
require_once 'templates/footer.php';
?>