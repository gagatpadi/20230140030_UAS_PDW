<?php

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
require_once 'templates/header_mahasiswa.php';

?>


<div class="bg-gray-800 text-white p-10 rounded-2xl shadow-xl mb-10 transform transition-all duration-300 hover:scale-[1.01] border border-gray-700"> <h1 class="text-4xl font-extrabold leading-tight text-gray-50">Selamat Datang Kembali, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h1> <p class="mt-4 text-lg opacity-90 text-gray-300">Terus semangat dalam menyelesaikan semua modul praktikummu. Mari kita raih hasil terbaik!</p> </div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">

    <div class="bg-gray-800 p-8 rounded-2xl shadow-lg flex flex-col items-center justify-center transform transition-transform duration-300 hover:scale-105 hover:shadow-xl border border-gray-700"> <div class="text-6xl font-extrabold text-purple-400 mb-2">3</div> <div class="mt-2 text-xl font-semibold text-gray-300">Praktikum Diikuti</div> </div>

    <div class="bg-gray-800 p-8 rounded-2xl shadow-lg flex flex-col items-center justify-center transform transition-transform duration-300 hover:scale-105 hover:shadow-xl border border-gray-700"> <div class="text-6xl font-extrabold text-green-400 mb-2">8</div> <div class="mt-2 text-xl font-semibold text-gray-300">Tugas Selesai</div> </div>

    <div class="bg-gray-800 p-8 rounded-2xl shadow-lg flex flex-col items-center justify-center transform transition-transform duration-300 hover:scale-105 hover:shadow-xl border border-gray-700"> <div class="text-6xl font-extrabold text-orange-400 mb-2">4</div> <div class="mt-2 text-xl font-semibold text-gray-300">Tugas Menunggu</div> </div>

</div>

<div class="bg-gray-800 p-8 rounded-2xl shadow-lg border border-gray-700"> <h3 class="text-3xl font-bold text-gray-50 mb-6 border-b-2 pb-3 border-gray-700">Notifikasi Terbaru</h3> <ul class="space-y-6">

        <li class="flex items-start p-4 bg-gray-900 rounded-lg transition-colors duration-200 hover:bg-gray-700 border border-gray-800"> <span class="text-2xl mr-5 text-blue-400">🔔</span> <div>
                <p class="text-lg text-gray-100">Nilai untuk <a href="#" class="font-bold text-blue-500 hover:underline">Modul 1: HTML & CSS</a> telah diberikan. Segera cek!</p> <span class="text-sm text-gray-400">Beberapa detik yang lalu</span> </div>
        </li>

        <li class="flex items-start p-4 bg-gray-900 rounded-lg transition-colors duration-200 hover:bg-gray-700 border border-gray-800"> <span class="text-2xl mr-5 text-yellow-400">⏳</span> <div>
                <p class="text-lg text-gray-100">Batas waktu pengumpulan laporan untuk <a href="#" class="font-bold text-blue-500 hover:underline">Modul 2: PHP Native</a> adalah besok! Jangan sampai terlewat.</p> <span class="text-sm text-gray-400">1 hari yang lalu</span> </div>
        </li>

        <li class="flex items-start p-4 bg-gray-900 rounded-lg transition-colors duration-200 hover:bg-gray-700 border border-gray-800"> <span class="text-2xl mr-5 text-green-400">✅</span> <div>
                <p class="text-lg text-gray-100">Anda berhasil mendaftar pada mata praktikum <a href="#" class="font-bold text-blue-500 hover:underline">Jaringan Komputer</a>. Selamat bergabung!</p> <span class="text-sm text-gray-400">3 hari yang lalu</span> </div>
        </li>

    </ul>
</div>


<?php
// Panggil Footer
require_once 'templates/footer_mahasiswa.php';
?>