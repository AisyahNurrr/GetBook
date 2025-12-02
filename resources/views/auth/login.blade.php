<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login • GetBook</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-6">

    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-10 py-8 text-center">
                <h1 class="text-3xl font-bold text-white">GetBook</h1>
                <p class="text-indigo-100 mt-2">Masuk ke akun Anda</p>
            </div>

            <!-- Form -->
            <div class="px-10 py-10">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Password</label>
                        <input type="password" name="password" required
                               class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember + Submit -->
                    <div class="flex items-center justify-between mb-8">
                        <label class="flex items-center text-gray-600">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="mr-3 rounded">
                            <span class="text-sm">Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold text-lg rounded-xl hover:shadow-lg hover:from-indigo-700 hover:to-purple-700 transition duration-200">
                        Masuk
                    </button>
                </form>

                <!-- Links -->
                <div class="mt-8 text-center text-gray-600">
                    <p class="text-sm">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                            Daftar di sini
                        </a>
                    </p>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-gray-700 block mt-3">
                            Lupa password?
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer kecil -->
        <p class="text-center text-gray-500 text-sm mt-10">
            © {{ date('Y') }} GetBook — Get Your Book
        </p>
    </div>

    <!-- SweetAlert kalau ada error umum -->
    @if(session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                confirmButtonColor: '#6366f1'
            });
        </script>
    @endif
</body>
</html>