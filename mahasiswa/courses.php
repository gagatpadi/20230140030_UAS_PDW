<?php
$pageTitle = 'Cari Praktikum';
$activePage = 'courses';
require_once 'templates/header_mahasiswa.php';

// --- DATA STATIS (DUMMY) ---
// Nantinya, data ini akan diambil dari database melalui query SQL.
$daftarPraktikum = [
    [
        'id' => 1,
        'nama' => 'Pemrograman Web',
        'deskripsi' => 'Mempelajari dasar-dasar pengembangan web dengan HTML, CSS, PHP, dan MySQL.',
        'asisten' => 'Andi Budiman, S.Kom.'
    ],
    [
        'id' => 2,
        'nama' => 'Jaringan Komputer',
        'deskripsi' => 'Praktikum untuk memahami konsep dasar jaringan, topologi, dan konfigurasi perangkat.',
        'asisten' => 'Citra Lestari, M.T.'
    ],
    [
        'id' => 3,
        'nama' => 'Struktur Data',
        'deskripsi' => 'Implementasi struktur data seperti linked list, stack, queue, dan tree dalam bahasa C++.',
        'asisten' => 'Doni Firmansyah, S.T.'
    ],
    [
        'id' => 4,
        'nama' => 'Basis Data Lanjutan',
        'deskripsi' => 'Mempelajari optimasi query, stored procedure, dan trigger pada sistem database Oracle.',
        'asisten' => 'Elisa Putri, S.Kom.'
    ]
];

?>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Katalog Mata Praktikum</h1>
    <p class="text-gray-600 mb-8">Berikut adalah daftar semua mata praktikum yang tersedia. Klik tombol "Daftar" untuk mendaftar.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($daftarPraktikum as $praktikum) : ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($praktikum['nama']); ?></h3>
                    <p class="text-gray-600 text-sm mb-4">Asisten: <?php echo htmlspecialchars($praktikum['asisten']); ?></p>
                    <p class="text-gray-700 mb-6 h-20"><?php echo htmlspecialchars($praktikum['deskripsi']); ?></p>
                    
                    <a href="daftar_action.php?id_praktikum=<?php echo $praktikum['id']; ?>" class="w-full text-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300 block">
                        Daftar Praktikum
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
require_once 'templates/footer_mahasiswa.php';
?>