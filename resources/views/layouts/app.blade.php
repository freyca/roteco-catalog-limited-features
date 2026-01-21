<!DOCTYPE html>
<html lang="es">

<x-head.head />

<body id="app-container" class="bg-white-100 text-primary-900 min-h-screen m-0 flex flex-col">
    <x-navbar.navbar />

    <main class="relative container mx-auto mt-4 sm:p-4 ">
        {{ $slot }}
    </main>

    <x-footer.footer />

    @vite('resources/js/app.js')
    @filamentScripts
    @livewireScripts
</body>

</html>
