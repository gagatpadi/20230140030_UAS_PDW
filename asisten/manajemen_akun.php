<?php
$pageTitle = 'Manajemen Akun';
$activePage = 'manajemen_akun';
require_once 'templates/header.php'; // Pastikan header sudah bertema gelap
require_once '../config.php';

// Ambil semua pengguna dari database
$result = $conn->query("SELECT id, nama, email, role FROM users ORDER BY nama ASC");
?>

<div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700 text-gray-100"> <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-50">Daftar Akun Pengguna</h2> <a href="akun_tambah.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105">
            <svg class="w-5 h-5 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Akun Baru
        </a>
    </div>

    <?php if (isset($_GET['status'])): ?>
        <div class="mb-6 p-4 rounded-lg
            <?php
            if ($_GET['status'] == 'tambah_sukses' || $_GET['status'] == 'edit_sukses' || $_GET['status'] == 'hapus_sukses') {
                echo 'bg-green-700 text-white border border-green-500'; // Notifikasi sukses hijau gelap
            } else {
                echo 'bg-red-700 text-white border border-red-500'; // Notifikasi gagal merah gelap
            }
            ?>">
            <?php
            if ($_GET['status'] == 'tambah_sukses') echo 'Akun berhasil ditambahkan!';
            if ($_GET['status'] == 'edit_sukses') echo 'Akun berhasil diperbarui!';
            if ($_GET['status'] == 'hapus_sukses') echo 'Akun berhasil dihapus!';
            if ($_GET['status'] == 'hapus_gagal') echo 'Gagal menghapus akun. Mungkin Anda mencoba menghapus akun sendiri atau terjadi kesalahan.';
            ?>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-700"> <table class="min-w-full bg-gray-900"> <thead class="bg-gray-950 text-white uppercase text-sm leading-normal"> <tr>
                    <th class="py-3 px-6 text-left">Nama</th>
                    <th class="py-3 px-6 text-left">Email</th>
                    <th class="py-3 px-6 text-left">Role</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-300"> <?php while($user = $result->fetch_assoc()): ?>
                    <tr class="border-b border-gray-700 hover:bg-gray-700"> <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo htmlspecialchars($user['nama']); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td class="py-3 px-6 text-left">
                            <span class="capitalize px-3 py-1 text-xs font-bold rounded-full
                                <?php echo ($user['role'] == 'asisten') ? 'bg-purple-500 text-white' : 'bg-green-500 text-white'; ?>"> <?php echo htmlspecialchars($user['role']); ?>
                            </span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                <a href="akun_edit.php?id=<?php echo $user['id']; ?>" class="w-8 h-8 flex items-center justify-center rounded-full bg-yellow-600 hover:bg-yellow-700 text-white mr-2 transition-colors duration-300" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <?php if ($_SESSION['user_id'] != $user['id']): // Cegah admin menghapus dirinya sendiri ?>
                                    <a href="akun_hapus.php?id=<?php echo $user['id']; ?>" class="w-8 h-8 flex items-center justify-center rounded-full bg-red-600 hover:bg-red-700 text-white transition-colors duration-300" title="Hapus" onclick="return confirm('Yakin ingin menghapus akun <?php echo htmlspecialchars($user['nama']); ?>?');">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$conn->close();
require_once 'templates/footer.php';
?>