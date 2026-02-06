<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="w-full max-w-md bg-white border border-gray-200 rounded-xl shadow-md p-8">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">
            Register
        </h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm text-gray-600 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500
                    @error('name') border-red-500 @enderror">
                @error('name')
                    <small class="text-red-500 text-xs">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500
                    @error('email') border-red-500 @enderror">
                @error('email')
                    <small class="text-red-500 text-xs">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password"
                        class="w-full px-3 py-2 pr-12 border rounded-lg focus:ring-2 focus:ring-indigo-500
                        @error('password') border-red-500 @enderror">

                    <button type="button"
                        onclick="togglePassword('password','eye1','eyeSlash1')"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">

                        <svg id="eye1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>

                        <svg id="eyeSlash1" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Confirm Password</label>
                <div class="relative">
                    <input type="password" id="confirm" name="password_confirmation"
                        class="w-full px-3 py-2 pr-12 border rounded-lg focus:ring-2 focus:ring-indigo-500">

                    <button type="button"
                        onclick="togglePassword('confirm','eye2','eyeSlash2')"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">

                        <svg id="eye2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>

                        <svg id="eyeSlash2" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Jenis Kelamin</label>
                <select name="gender" value="{{ old('gender') }}"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500
                    @error('gender') border-red-500 @enderror">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender')
                    <small class="text-red-500 text-xs">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Alamat</label>
                <textarea name="address" rows="3"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500
                    @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                @error('address')
                    <small class="text-red-500 text-xs">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit"
                class="w-full py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">
                Register
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">
                Login
            </a>
        </p>
    </div>

    <script>
        function togglePassword(inputId, eyeId, eyeSlashId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            const eyeSlash = document.getElementById(eyeSlashId);

            if (input.type === "password") {
                // Ubah jadi text (show password)
                input.type = "text";
                eye.classList.add("hidden");      // Sembunyikan mata terbuka
                eyeSlash.classList.remove("hidden"); // Tampilkan mata tertutup
            } else {
                // Kembalikan jadi password (hide password)
                input.type = "password";
                eye.classList.remove("hidden");   // Tampilkan mata terbuka
                eyeSlash.classList.add("hidden");    // Sembunyikan mata tertutup
            }
        }
    </script>

</body>
</html>
