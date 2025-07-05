<?php
$pageTitle = 'Laporan Masuk';
$activePage = 'laporan';
require_once 'templates/header.php'; // Pastikan header sudah bertema gelap
require_once '../config.php';

// Bangun query dasar
$sql = "SELECT l.id, l.tgl_kumpul, l.status, u.nama as nama_mahasiswa, m.nama_modul
        FROM laporan l
        JOIN users u ON l.id_mahasiswa = u.id
        JOIN modul m ON l.id_modul = m.id";

$whereClauses = [];
$params = [];
$types = '';

// Logika filter
if (!empty($_GET['id_mahasiswa'])) {
    $whereClauses[] = "l.id_mahasiswa = ?";
    $params[] = $_GET['id_mahasiswa'];
    $types .= 'i';
}
if (!empty($_GET['id_modul'])) {
    $whereClauses[] = "l.id_modul = ?";
    $params[] = $_GET['id_modul'];
    $types .= 'i';
}
if (!empty($_GET['status'])) {
    $whereClauses[] = "l.status = ?";
    $params[] = $_GET['status'];
    $types .= 's';
}

if (!empty($whereClauses)) {
    $sql .= " WHERE " . implode(" AND ", $whereClauses);
}
$sql .= " ORDER BY l.tgl_kumpul DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result_laporan = $stmt->get_result();

// Ambil data untuk filter dropdown
$mahasiswas = $conn->query("SELECT id, nama FROM users WHERE role = 'mahasiswa' ORDER BY nama");
$moduls = $conn->query("SELECT id, nama_modul FROM modul ORDER BY nama_modul");

?>

<div class="bg-gray-800 p-8 rounded-xl shadow-2xl text-gray-100 border border-gray-700"> <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-700">
        <h2 class="text-4xl font-extrabold text-gray-50 tracking-tight">Manajemen Laporan Masuk</h2> </div>

    <?php if (isset($_GET['status_aksi'])): // Menggunakan status_aksi untuk menghindari konflik dengan filter status ?>
        <div class="mb-8 p-4 rounded-lg text-lg font-medium
            <?php
            if ($_GET['status_aksi'] == 'nilai_sukses') {
                echo 'bg-green-800 text-green-100 border border-green-600'; // Notifikasi sukses hijau gelap elegan
            } else if ($_GET['status_aksi'] == 'nilai_gagal') {
                echo 'bg-red-800 text-red-100 border border-red-600'; // Notifikasi gagal merah gelap elegan
            }
            ?>">
            <?php
            if ($_GET['status_aksi'] == 'nilai_sukses') echo 'Nilai laporan berhasil disimpan!';
            if ($_GET['status_aksi'] == 'nilai_gagal') echo 'Gagal menyimpan nilai laporan.';
            ?>
        </div>
    <?php endif; ?>

    <h3 class="text-2xl font-bold text-gray-50 mb-6">Filter Laporan</h3> <form action="" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 p-6 bg-gray-900 rounded-lg shadow-inner border border-gray-700"> <div>
            <label for="id_mahasiswa" class="block text-sm font-semibold text-gray-300 mb-1">Mahasiswa</label>
            <select name="id_mahasiswa" id="id_mahasiswa" class="mt-1 block w-full py-2.5 px-3 border border-gray-600 bg-gray-800 text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="" class="bg-gray-800">Semua Mahasiswa</option>
                <?php $mahasiswas->data_seek(0); // Reset pointer for second loop ?>
                <?php while($row = $mahasiswas->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php echo (($_GET['id_mahasiswa'] ?? '') == $row['id']) ? 'selected' : ''; ?> class="bg-gray-800"><?php echo htmlspecialchars($row['nama']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div>
            <label for="id_modul" class="block text-sm font-semibold text-gray-300 mb-1">Modul</label>
            <select name="id_modul" id="id_modul" class="mt-1 block w-full py-2.5 px-3 border border-gray-600 bg-gray-800 text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="" class="bg-gray-800">Semua Modul</option>
                 <?php $moduls->data_seek(0); // Reset pointer for second loop ?>
                 <?php while($row = $moduls->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php echo (($_GET['id_modul'] ?? '') == $row['id']) ? 'selected' : ''; ?> class="bg-gray-800"><?php echo htmlspecialchars($row['nama_modul']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div>
            <label for="status" class="block text-sm font-semibold text-gray-300 mb-1">Status</label>
            <select name="status" id="status" class="mt-1 block w-full py-2.5 px-3 border border-gray-600 bg-gray-800 text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="" class="bg-gray-800">Semua Status</option>
                <option value="Terkumpul" <?php echo (($_GET['status'] ?? '') == 'Terkumpul') ? 'selected' : ''; ?> class="bg-gray-800">Belum Dinilai</option>
                <option value="Dinilai" <?php echo (($_GET['status'] ?? '') == 'Dinilai') ? 'selected' : ''; ?> class="bg-gray-800">Sudah Dinilai</option>
            </select>
        </div>
        <div class="self-end">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105">
                <svg class="w-5 h-5 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4h18v10a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm4 3h4v4H7v-4zm6 0h4v4h-4v-4z"></path></svg>
                Filter Laporan
            </button>
        </div>
    </form>

    <div class="overflow-x-auto rounded-lg shadow-xl border border-gray-700"> <table class="min-w-full bg-gray-900"> <thead class="bg-gray-950 text-gray-200 uppercase text-sm leading-normal border-b border-gray-700"> <tr>
                    <th class="py-4 px-6 text-left font-bold">Mahasiswa</th> <th class="py-4 px-6 text-left font-bold">Modul</th> <th class="py-4 px-6 text-left font-bold">Tgl Kumpul</th> <th class="py-4 px-6 text-left font-bold">Status</th> <th class="py-4 px-6 text-center font-bold">Aksi</th> </tr>
            </thead>
            <tbody class="text-gray-300 divide-y divide-gray-700"> <?php if ($result_laporan->num_rows > 0): ?>
                    <?php while($laporan = $result_laporan->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-700 transition-colors duration-200"> <td class="py-4 px-6 whitespace-nowrap"><?php echo htmlspecialchars($laporan['nama_mahasiswa']); ?></td> <td class="py-4 px-6"><?php echo htmlspecialchars($laporan['nama_modul']); ?></td> <td class="py-4 px-6"><?php echo date('d M Y, H:i', strtotime($laporan['tgl_kumpul'])); ?></td> <td class="py-4 px-6">
                                <?php if ($laporan['status'] == 'Dinilai'): ?>
                                    <span class="bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">Sudah Dinilai</span> <?php else: ?>
                                    <span class="bg-yellow-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">Belum Dinilai</span> <?php endif; ?>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <a href="laporan_nilai.php?id=<?php echo $laporan['id']; ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-1.5 px-4 rounded-lg text-xs transition-colors duration-300 shadow-md">
                                    <?php echo ($laporan['status'] == 'Dinilai') ? 'Lihat/Edit Nilai' : 'Beri Nilai'; ?>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-400 text-xl font-semibold"> Tidak ada laporan yang cocok dengan filter yang diterapkan.
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