<x-layouts.app>
    <x-bread-crumbs :breadcrumbs="$breadcrumbs" />

    <div class="container mx-auto p-4">
        <div class="grid gap-4">
            <div class="overflow-hidden rounded-lg bg-white p-4 shadow-md">
                <h2 class="mb-2 text-xl font-bold">Nuestra Historia</h2>

                <p class="text-primary-700">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent vel urna quis urna fermentum
                    bibendum.
                </p>
            </div>

            <div class="overflow-hidden rounded-lg bg-white p-4 shadow-md">
                <h2 class="mb-2 text-xl font-bold">Nuestro Equipo</h2>
                <p class="text-primary-700">Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div>

            <div class="container mx-auto rounded-lg bg-white p-4 shadow-lg">
                <h2 class="mb-2 text-xl font-bold">About Us</h2>

                <p class="text-primary-700 mb-4">
                    We are a company dedicated to providing the best products and services to our customers...
                </p>

                <div class="flex space-x-4">
                    <img src="path/to/image1.jpg" alt="Team member 1" class="w-1/4 rounded-lg" />
                    <img src="path/to/image2.jpg" alt="Team member 2" class="w-1/4 rounded-lg" />
                    <img src="path/to/image3.jpg" alt="Team member 3" class="w-1/4 rounded-lg" />
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
