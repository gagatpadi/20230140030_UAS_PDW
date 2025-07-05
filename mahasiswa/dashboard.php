<?php

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
require_once 'templates/header_mahasiswa.php';

?>


<div class="bg-gradient-to-r from-purple-500 to-indigo-600 text-white p-10 rounded-2xl shadow-xl mb-10 transform transition-all duration-300 hover:scale-[1.01]">
    <h1 class="text-4xl font-extrabold leading-tight">Selamat Datang Kembali, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h1>
    <p class="mt-4 text-lg opacity-90">Terus semangat dalam menyelesaikan semua modul praktikummu. Mari kita raih hasil terbaik!</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">

    <div class="bg-white p-8 rounded-2xl shadow-lg flex flex-col items-center justify-center transform transition-transform duration-300 hover:scale-105 hover:shadow-xl">
        <div class="text-6xl font-extrabold text-purple-600 mb-2">3</div>
        <div class="mt-2 text-xl font-semibold text-gray-700">Praktikum Diikuti</div>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-lg flex flex-col items-center justify-center transform transition-transform duration-300 hover:scale-105 hover:shadow-xl">
        <div class="text-6xl font-extrabold text-green-500 mb-2">8</div>
        <div class="mt-2 text-xl font-semibold text-gray-700">Tugas Selesai</div>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-lg flex flex-col items-center justify-center transform transition-transform duration-300 hover:scale-105 hover:shadow-xl">
        <div class="text-6xl font-extrabold text-orange-500 mb-2">4</div>
        <div class="mt-2 text-xl font-semibold text-gray-700">Tugas Menunggu</div>
    </div>

</div>

<div class="bg-white p-8 rounded-2xl shadow-lg">
    <h3 class="text-3xl font-bold text-gray-800 mb-6 border-b-2 pb-3 border-gray-200">Notifikasi Terbaru</h3>
    <ul class="space-y-6">

        <li class="flex items-start p-4 bg-gray-50 rounded-lg transition-colors duration-200 hover:bg-gray-100">
            <span class="text-2xl mr-5 text-blue-500">🔔</span>
            <div>
                <p class="text-lg text-gray-800">Nilai untuk <a href="#" class="font-bold text-blue-600 hover:underline">Modul 1: HTML & CSS</a> telah diberikan. Segera cek!</p>
                <span class="text-sm text-gray-500">Beberapa detik yang lalu</span>
            </div>
        </li>

        <li class="flex items-start p-4 bg-gray-50 rounded-lg transition-colors duration-200 hover:bg-gray-100">
            <span class="text-2xl mr-5 text-yellow-500">⏳</span>
            <div>
                <p class="text-lg text-gray-800">Batas waktu pengumpulan laporan untuk <a href="#" class="font-bold text-blue-600 hover:underline">Modul 2: PHP Native</a> adalah besok! Jangan sampai terlewat.</p>
                <span class="text-sm text-gray-500">1 hari yang lalu</span>
            </div>
        </li>

        <li class="flex items-start p-4 bg-gray-50 rounded-lg transition-colors duration-200 hover:bg-gray-100">
            <span class="text-2xl mr-5 text-green-500">✅</span>
            <div>
                <p class="text-lg text-gray-800">Anda berhasil mendaftar pada mata praktikum <a href="#" class="font-bold text-blue-600 hover:underline">Jaringan Komputer</a>. Selamat bergabung!</p>
                <span class="text-sm text-gray-500">3 hari yang lalu</span>
            </div>
        </li>

    </ul>
</div>


<?php
// Panggil Footer
require_once 'templates/footer_mahasiswa.php';
?>