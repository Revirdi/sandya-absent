<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4 text-white">Compare Location</h1>
        <button id="findNearest"
            class="mb-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800">
            Compare
        </button>

        <div class="relative overflow-x-auto shadow-md">
            <table id="resultTable" class="w-full text-sm text-center text-gray-400 border-collapse">
                <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                    <tr>
                        {{-- <th class="px-6 py-3 border border-white">ID</th> --}}
                        <th class="px-6 py-3 border border-white">Location Name</th>
                        <th class="px-6 py-3 border border-white">User Latitude</th>
                        <th class="px-6 py-3 border border-white">User Longitude</th>
                        <th class="px-6 py-3 border border-white">Location Latitude</th>
                        <th class="px-6 py-3 border border-white">Location Longitude</th>
                        <th class="px-6 py-3 border border-white">Haversine (km)</th>
                        <th class="px-6 py-3 border border-white">Equirect (km)</th>
                        <th class="px-6 py-3 border border-white">Difference (km)</th>
                        <th class="px-6 py-3 border border-white">Time Haversine (ms)</th>
                        <th class="px-6 py-3 border border-white">Time Equirect (ms)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Injected by JS -->
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.getElementById('findNearest').addEventListener('click', function() {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    fetch(`/api/compare?lat=${lat}&lng=${lng}`)
                        .then(res => res.json())
                        .then(data => {
                            const tbody = document.querySelector('#resultTable tbody');
                            tbody.innerHTML = '';

                            data.comparison.forEach(item => {
                                tbody.innerHTML += `
                                    <tr class="bg-gray-800 hover:bg-gray-600">
                                        <td class="px-6 py-4 border border-white">${item.location_name}</td>
                                        <td class="px-6 py-4 border border-white">${item.user_lat}</td>
                                        <td class="px-6 py-4 border border-white">${item.user_lng}</td>
                                        <td class="px-6 py-4 border border-white">${item.latitude}</td>
                                        <td class="px-6 py-4 border border-white">${item.longitude}</td>
                                        <td class="px-6 py-4 border border-white">${item.haversine_km}</td>
                                        <td class="px-6 py-4 border border-white">${item.equirect_km}</td>
                                        <td class="px-6 py-4 border border-white">${item.difference_km}</td>
                                        <td class="px-6 py-4 border border-white">${item.time_haversine_ms}</td>
                                        <td class="px-6 py-4 border border-white">${item.time_equirect_ms}</td>
                                    </tr>
                                `;
                            });
                        });
                },
                function(error) {
                    console.error("Tidak bisa ambil lokasi:", error);
                }
            );
        });
    </script>
</x-app-layout>
