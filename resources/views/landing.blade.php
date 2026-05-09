<!-- resources/views/landing.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Edukasi Literasi Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">
    <nav class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="text-xl font-bold text-blue-600">EduDigital</div>
            <div class="hidden md:flex space-x-6 text-sm font-medium">
                <a href="#" class="hover:text-blue-600">Artikel</a>
                <a href="#" class="hover:text-blue-600">Panduan</a>
                <a href="#" class="hover:text-blue-600">Tentang Kami</a>
            </div>
        </div>
    </nav>

    <header class="py-16 bg-white">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Pahami Keamanan Transaksi Digital Anda</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Membangun ekosistem keuangan yang aman, transparan, dan terpercaya di era ekonomi internet.</p>
        </div>
    </header>

    <main class="container mx-auto px-6 py-12">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-blue-100 rounded-lg mb-4 flex items-center justify-center text-blue-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="text-lg font-bold mb-2">Keamanan Data</h3>
                <p class="text-gray-600 text-sm">Pelajari cara melindungi kredensial Anda dari serangan siber yang merugikan di ruang digital.</p>
            </div>
            <!-- Duplikasi kotak ini untuk konten lainnya -->
        </div>
    </main>

    <footer class="bg-gray-100 py-8 mt-12 border-t">
        <div class="container mx-auto px-6 text-center text-gray-500 text-sm">
            &copy; 2026 EduDigital. Semua Hak Dilindungi.
        </div>
    </footer>
</body>
</html>