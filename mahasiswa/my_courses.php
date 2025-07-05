<?php
$pageTitle = 'Praktikum Saya';
$activePage = 'my_courses';
require_once 'templates/header_mahasiswa.php';
require_once '../config.php';

$id_mahasiswa = $_SESSION['user_id'];

// Query untuk mengambil praktikum yang sudah didaftarkan oleh mahasiswa yang login
$stmt = $conn->prepare("SELECT mp.* FROM mata_praktikum mp JOIN pendaftaran p ON mp.id = p.id_praktikum WHERE p.id_mahasiswa = ? ORDER BY mp.nama ASC");
$stmt->bind_param("i", $id_mahasiswa);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-gray-50 mb-6 border-b pb-3 border-gray-700">Praktikum yang Anda Ikuti</h1> <?php if (isset($_GET['status']) && $_GET['status'] == 'daftar_sukses'): ?>
        <div class="bg-green-800 text-green-100 p-4 rounded-lg mb-4 border border-green-600"> Anda berhasil terdaftar di praktikum!
        </div>
    <?php endif; ?>

    <div class="space-y-6">
        <?php if ($result->num_rows > 0): ?>
            <?php while($praktikum = $result->fetch_assoc()): ?>
                <div class="bg-gray-800 rounded-xl shadow-md p-6 flex flex-col md:flex-row items-center justify-between border border-gray-700"> <div class="flex-grow mb-4 md:mb-0">
                        <h3 class="text-xl font-bold text-gray-50"><?php echo htmlspecialchars($praktikum['nama']); ?></h3> <p class="text-sm text-gray-400">Kode: <?php echo htmlspecialchars($praktikum['kode_praktikum']); ?></p> </div>
                    <div class="flex-shrink-0">
                        <a href="course_detail.php?id_praktikum=<?php echo $praktikum['id']; ?>" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300 shadow-md"> Lihat Detail & Tugas
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="bg-gray-800 p-8 rounded-xl shadow-md text-center border border-gray-700"> <p class="text-gray-400 text-lg mb-4">Anda belum mendaftar di mata praktikum manapun.</p> <a href="courses.php" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md"> Cari Praktikum Sekarang
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
require_once 'templates/footer_mahasiswa.php';
?>