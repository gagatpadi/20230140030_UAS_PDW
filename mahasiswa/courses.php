<?php
$pageTitle = 'Cari Praktikum';
$activePage = 'courses';
require_once 'templates/header_mahasiswa.php';
require_once '../config.php'; // Tambahkan ini untuk koneksi database

// --- Ganti bagian DATA STATIS ini dengan query database ---
$daftarPraktikum = []; // Inisialisasi array kosong
$sql = "SELECT id, nama, kode_praktikum, deskripsi FROM mata_praktikum ORDER BY nama ASC";
$result_db = $conn->query($sql);

if ($result_db->num_rows > 0) {
    while($row = $result_db->fetch_assoc()) {
        $daftarPraktikum[] = $row;
    }
}
$conn->close(); // Tutup koneksi setelah selesai mengambil data
// --- Akhir bagian penggantian ---
?>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-gray-50 mb-6 border-b pb-3 border-gray-700">Katalog Mata Praktikum</h1>
    <p class="text-gray-300 mb-8">Berikut adalah daftar semua mata praktikum yang tersedia. Klik tombol "Daftar" untuk mendaftar.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if (!empty($daftarPraktikum)): // Tambahkan kondisi ini ?>
            <?php foreach ($daftarPraktikum as $praktikum) : ?>
                <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition-transform duration-300 border border-gray-700">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-50 mb-2"><?php echo htmlspecialchars($praktikum['nama']); ?></h3>
                        <p class="text-gray-400 text-sm mb-4">Kode: <?php echo htmlspecialchars($praktikum['kode_praktikum']); ?></p>
                        <p class="text-gray-300 mb-6 h-20"><?php echo htmlspecialchars($praktikum['deskripsi']); ?></p>

                        <a href="daftar_action.php?id_praktikum=<?php echo $praktikum['id']; ?>" class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300 block shadow-md">
                            Daftar Praktikum
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: // Tampilan jika tidak ada praktikum di database ?>
             <div class="col-span-full bg-gray-800 p-8 rounded-xl shadow-md text-center border border-gray-700">
                <p class="text-gray-400 text-lg mb-4">Tidak ada praktikum yang tersedia saat ini.</p>
                <p class="text-gray-400 text-sm">Silakan hubungi asisten untuk informasi lebih lanjut.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'templates/footer_mahasiswa.php'; ?>