<?php
$pageTitle = 'Manajemen Praktikum';
$activePage = 'manajemen_praktikum'; // Kita akan tambahkan ini di navigasi nanti
require_once 'templates/header.php';
require_once '../config.php';

// Logika untuk mengambil semua data praktikum dari database
$result = $conn->query("SELECT * FROM mata_praktikum ORDER BY nama ASC");

?>

<div class="bg-gray-800 p-8 rounded-xl shadow-2xl text-gray-100 border border-gray-700"> <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-700"> <h2 class="text-4xl font-extrabold text-gray-50 tracking-tight">Manajemen Mata Praktikum</h2> <a href="praktikum_tambah.php" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-300 transform hover:-translate-y-1 shadow-lg"> <svg class="w-5 h-5 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Praktikum Baru
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
            if ($_GET['status'] == 'tambah_sukses') echo 'Mata praktikum berhasil ditambahkan dengan sukses!';
            if ($_GET['status'] == 'edit_sukses') echo 'Perubahan pada mata praktikum berhasil disimpan.';
            if ($_GET['status'] == 'hapus_sukses') echo 'Mata praktikum berhasil dihapus secara permanen.';
            if ($_GET['status'] == 'hapus_gagal') echo 'Terjadi kesalahan saat menghapus mata praktikum.';
            ?>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto rounded-lg shadow-xl border border-gray-700"> <table class="min-w-full bg-gray-900"> <thead class="bg-gray-950 text-gray-200 uppercase text-sm leading-normal border-b border-gray-700"> <tr>
                    <th class="text-left py-4 px-6 font-bold">Kode</th> <th class="text-left py-4 px-6 font-bold">Nama Praktikum</th> <th class="text-left py-4 px-6 font-bold">Aksi</th> </tr>
            </thead>
            <tbody class="text-gray-300 divide-y divide-gray-700"> <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-700 transition-colors duration-200"> <td class="py-4 px-6 font-medium whitespace-nowrap"><?php echo htmlspecialchars($row['kode_praktikum']); ?></td> <td class="py-4 px-6"><?php echo htmlspecialchars($row['nama']); ?></td> <td class="py-4 px-6 flex items-center space-x-3"> <a href="modul.php?id_praktikum=<?php echo $row['id']; ?>" class="flex items-center bg-green-600 hover:bg-green-700 text-white font-semibold py-1.5 px-4 rounded-full transition-colors duration-300 shadow-md">Modul</a>
                                <a href="praktikum_edit.php?id=<?php echo $row['id']; ?>" class="flex items-center bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-1.5 px-4 rounded-full transition-colors duration-300 shadow-md">Edit</a>
                                <a href="praktikum_hapus.php?id=<?php echo $row['id']; ?>" class="flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold py-1.5 px-4 rounded-full transition-colors duration-300 shadow-md" onclick="return confirm('Apakah Anda yakin ingin menghapus praktikum ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center py-10 text-gray-400 text-xl font-semibold"> Belum ada data mata praktikum yang tersedia. Tambahkan yang pertama sekarang!
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$conn->close();
require_once 'templates/footer.php';
?>