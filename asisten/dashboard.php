<?php
// 1. Definisi Variabel untuk Template
$pageTitle = 'Dashboard';
$activePage = 'dashboard';

// 2. Panggil Header
require_once 'templates/header.php';
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    <div class="bg-gray-800 p-6 rounded-lg shadow-md flex items-center space-x-4 text-gray-100"> <div class="bg-blue-800 p-3 rounded-full"> <svg class="w-6 h-6 text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
        </div>
        <div>
            <p class="text-sm text-gray-300">Total Modul Diajarkan</p> <p class="text-2xl font-bold text-gray-50">12</p> </div>
    </div>

    <div class="bg-gray-800 p-6 rounded-lg shadow-md flex items-center space-x-4 text-gray-100"> <div class="bg-green-800 p-3 rounded-full"> <svg class="w-6 h-6 text-green-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div>
            <p class="text-sm text-gray-300">Total Laporan Masuk</p> <p class="text-2xl font-bold text-gray-50">152</p> </div>
    </div>

    <div class="bg-gray-800 p-6 rounded-lg shadow-md flex items-center space-x-4 text-gray-100"> <div class="bg-yellow-800 p-3 rounded-full"> <svg class="w-6 h-6 text-yellow-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div>
            <p class="text-sm text-gray-300">Laporan Belum Dinilai</p> <p class="text-2xl font-bold text-gray-50">18</p> </div>
    </div>
</div>

<div class="bg-gray-800 p-6 rounded-lg shadow-md mt-8"> <h3 class="text-xl font-bold text-gray-50 mb-4">Aktivitas Laporan Terbaru</h3> <div class="space-y-4">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center mr-4"> <span class="font-bold text-gray-300">BS</span> </div>
            <div>
                <p class="text-gray-100"><strong>Budi Santoso</strong> mengumpulkan laporan untuk <strong>Modul 2</strong></p> <p class="text-sm text-gray-400">10 menit lalu</p> </div>
        </div>
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center mr-4"> <span class="font-bold text-gray-300">CL</span> </div>
            <div>
                <p class="text-gray-100"><strong>Citra Lestari</strong> mengumpulkan laporan untuk <strong>Modul 2</strong></p> <p class="text-sm text-gray-400">45 menit lalu</p> </div>
        </div>
    </div>
</div>

<?php
// 3. Panggil Footer
require_once 'templates/footer.php';
?>