<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/images/favicon-96x96.png" sizes="96x96" />
    <title>Reset Password - Outsidersmedia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-orange-50 via-white to-amber-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md mt-8 mb-8">

        <div class="text-center mb-8">
            <div class="logo-wrapper">
                <img src="{{ asset('images/logo-img.png') }}" alt="" class="img-fluid">
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2 mt-4">Reset Password</h1>
            <p class="text-gray-600">Enter your new password below.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('password.reset.post') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <input type="text" value="{{ $email }}" disabled class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-500 text-sm">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">New Password <span class="text-red-500">*</span></label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent transition-all"
                        placeholder="Minimum 8 characters"
                        required
                        autofocus
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent transition-all"
                        placeholder="Re-enter your password"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="w-full text-white py-3 px-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all"
                    style="background:linear-gradient(135deg,#CD571B,#EC921A)"
                >
                    Reset Password
                </button>
            </form>
        </div>

        <div class="text-center mt-8">
            <p class="text-sm text-gray-600">© 2026 Outsidersmedia. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
