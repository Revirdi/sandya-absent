<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- Toastify JS -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    <style>
        #gmap-link {
            display: none;
            text-align: center;
            margin: 1rem;
        }

        #gmap-link a {
            background-color: #3b82f6;
            color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: bold;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-700">
    {{-- <div class="min-h-screen bg-gray-100 dark:bg-gray-900"> --}}
    @include('layouts.sidebar')
    @if (session('success'))
        <script>
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#22c55e", // Tailwind green-500
                stopOnFocus: true
            }).showToast();
        </script>
    @endif

    @if (session('error'))
        <script>
            Toastify({
                text: "{{ session('error') }}",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#ef4444", // red
                stopOnFocus: true
            }).showToast();
        </script>
    @endif

    @if ($errors->any())
        <script>
            Toastify({
                text: `{!! implode('<br>', $errors->all()) !!}`,
                duration: 4000,
                gravity: "top",
                position: "right",
                backgroundColor: "#ef4444",
                stopOnFocus: true
            }).showToast();
        </script>
    @endif

    <!-- Page Heading -->
    {{-- @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset --}}

    <!-- Page Content -->
    <main>
        <div class="p-4 sm:ml-64">
            {{ $slot }}
        </div>
    </main>
    </div>
    <script>
        const togglePassword1 = document.querySelector('#togglePassword1');
        const togglePassword2 = document.querySelector('#togglePassword2');
        const password = document.querySelector('#password');
        const passwordConfirm = document.querySelector('#password_confirmation');

        togglePassword1 && togglePassword1.addEventListener('click', function(e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon1.classList.toggle('fa-eye');
            eyeIcon1.classList.toggle('fa-eye-slash');
        });
        togglePassword2 && togglePassword2.addEventListener('click', function(e) {
            const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirm.setAttribute('type', type);
            eyeIcon2.classList.toggle('fa-eye');
            eyeIcon2.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
