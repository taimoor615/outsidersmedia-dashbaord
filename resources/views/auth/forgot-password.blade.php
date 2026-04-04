<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/images/favicon-96x96.png" sizes="96x96" />
    <title>Forgot Password - Outsidersmedia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-orange-50 via-white to-amber-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md mt-8 mb-8">

        <div class="text-center mb-8">
            <div class="logo-wrapper">
                <img src="{{ asset('images/logo-img.png') }}" alt="" class="img-fluid">
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2 mt-4">Forgot Password?</h1>
            <p class="text-gray-600">Enter your email and we'll send you a reset link.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('password.forgot.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="block w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent transition-all"
                            placeholder="your@email.com"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full text-white py-3 px-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all"
                    style="background:linear-gradient(135deg,#CD571B,#EC921A)"
                >
                    Send Reset Link
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm font-medium hover:underline" style="color:#CD571B">
                    &larr; Back to Login
                </a>
            </div>
        </div>

        <div class="text-center mt-8">
            <p class="text-sm text-gray-600">© 2026 Outsidersmedia. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
