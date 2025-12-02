<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar User • Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Header Admin -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Admin Panel</h1>
            <div class="relative">
                <button id="menuButton" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 font-medium">
                    <span>Admin</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border z-50">
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto mt-12 px-6 pb-20">
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">

            <!-- Header Card -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-10 py-10">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-5xl font-bold text-white">Daftar Akun User</h2>
                        <p class="text-indigo-100 mt-3 text-lg">
                            Total user terdaftar: 
                            <span class="text-3xl font-black">{{ $total }}</span> orang
                        </p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-10 py-5 bg-white/20 backdrop-blur-sm text-white font-bold text-lg rounded-full hover:bg-white/30 transition shadow-lg transform hover:-translate-y-1">
                        ← Dashboard
                    </a>
                </div>
            </div>

            <!-- Tabel User -->
            <div class="p-10">
                @if($users->where('role', 'user')->count() > 0)
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                                <tr>
                                    <th class="px-8 py-6 text-left text-sm font-bold uppercase tracking-wider">No</th>
                                    <th class="px-8 py-6 text-left text-sm font-bold uppercase tracking-wider">Nama Lengkap</th>
                                    <th class="px-8 py-6 text-left text-sm font-bold uppercase tracking-wider">Email</th>
                                    <th class="px-8 py-6 text-left text-sm font-bold uppercase tracking-wider">Bergabung</th>
                                    <th class="px-8 py-6 text-center text-sm font-bold uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $no = 1 + ($users->currentPage() - 1) * $users->perPage();
                                @endphp

                                @foreach($users as $user)
                                    @if($user->role === 'user')
                                        <tr class="hover:bg-gray-50 transition duration-200">
                                            <td class="px-8 py-6 text-center font-medium text-gray-700">
                                                {{ $no++ }}
                                            </td>
                                            <td class="px-8 py-6">
                                                <div class="flex items-center space-x-4">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                                        {{ Str::substr(Str::upper($user->name), 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                                        <div class="text-sm text-gray-500">User biasa</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-8 py-6 text-gray-700 font-medium">{{ $user->email }}</td>
                                            <td class="px-8 py-6 text-gray-600">
                                                {{ $user->created_at->format('d M Y') }}
                                                <span class="block text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                                            </td>
                                            <td class="px-8 py-6 text-center">
                                                <span class="px-5 py-2 bg-green-100 text-green-800 rounded-full text-sm font-bold">
                                                    Aktif
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        {{ $users->links('pagination::tailwind') }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-20">
                        <div class="text-8xl mb-6 text-gray-300">No Users</div>
                        <h3 class="text-3xl font-bold text-gray-600 mb-4">Belum Ada User Terdaftar</h3>
                        <p class="text-gray-500 max-w-md mx-auto text-lg">
                            Saat ini belum ada akun user yang terdaftar di sistem.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Script -->
    <script>
        // Dropdown logout
        document.getElementById('menuButton').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('dropdown').classList.toggle('hidden');
        });
        document.addEventListener('click', function() {
            document.getElementById('dropdown').classList.add('hidden');
        });
    </script>
</body>
</html>