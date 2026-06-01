<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manga AI Search</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-5 text-center">Pencarian Komik Berbasis AI</h1>
        
        <form action="{{ route('manga.index') }}" method="GET" class="mb-10 flex gap-2">
            <input type="text" name="search" value="{{ $query }}" 
                   placeholder="Cari alur cerita... (contoh: anak laki-laki topi jerami)" 
                   class="w-full p-3 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 shadow">Cari</button>
        </form>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 gap-4">
            @foreach($mangas as $manga)
            <div class="bg-white p-5 rounded shadow hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <h2 class="text-xl font-bold text-blue-800">{{ $manga->title }}</h2>
                    @if(isset($manga->score))
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            Skor AI: {{ number_format($manga->score * 100, 1) }}%
                        </span>
                    @endif
                </div>
                <p class="text-gray-500 text-sm mb-2 italic">{{ $manga->genre }}</p>
                <p class="text-gray-700">{{ $manga->synopsis }}</p>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>