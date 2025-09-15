<x-app-layout>
    {{-- <x-slot name="sidebar">
    </x-slot> --}}
    <div id="map" class="relative z-0 w-full h-[70dvh]"></div>
    {{-- <div id="gmap-link">
        <a href="#" target="_blank" id="directionBtn">Buka di Google Maps</a>
    </div> --}}
    <div class="flex justify-center items-center gap-2 p-5">
        {{-- <a href="#" target="_blank" id="directionBtn"
            class="inline-flex items-center justify-center text-white focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-blue-800">
            Buka di Google Maps
        </a> --}}
        @if (!$attendance)
            <button id="checkin-btn"
                class="inline-flex items-center justify-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Check-In
            </button>
        @else
            <button id="checkout-btn" {{ $attendance->check_out_time ? 'disabled' : '' }}
                class="inline-flex items-center justify-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2.5 disabled:bg-gray-500">
                Check-Out
            </button>
        @endif
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
    <!-- JS -->
    <script src="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>
    <script>
        let userLat = null;
        let userLng = null;

        const map = L.map('map').setView([-6.2, 106.8], 14);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        navigator.geolocation.getCurrentPosition(async (pos) => {
            userLat = pos.coords.latitude;
            userLng = pos.coords.longitude;

            // Marker user
            const userIcon = L.divIcon({
                className: '',
                html: `<div class="w-4 h-4 bg-blue-500 rounded-full border-2 border-white shadow-md"></div>`,
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            });

            L.marker([userLat, userLng], {
                icon: userIcon
            }).addTo(map).openPopup();
            map.flyTo([userLat, userLng], 15);

            L.control.locate({
                position: 'topleft',
                strings: {
                    title: "Click to focus on your current location"
                },
                flyTo: true,
                keepCurrentZoomLevel: false
            }).addTo(map);

            try {
                // Ambil lokasi dari backend
                const res = await fetch(`/api/attendance-locations?lat=${userLat}&lng=${userLng}`);
                const json = await res.json();

                const lokasiList = json.data;
                const lokasiTerdekat = json.nearest;

                // Render semua lokasi
                lokasiList.forEach(loc => {
                    L.circle([loc.latitude, loc.longitude], {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.3,
                        radius: 40000 // jarak meter
                    }).addTo(map).bindPopup(`📍 ${loc.location_name}`);
                });

                // Lokasi terdekat
                if (lokasiTerdekat) {
                    const nearestIcon = L.icon({
                        iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32],
                    });

                    L.marker([lokasiTerdekat.latitude, lokasiTerdekat.longitude], {
                        icon: nearestIcon
                    }).addTo(map).bindPopup(
                        `✅ Lokasi Terdekat:<br><strong>${lokasiTerdekat.location_name}</strong><br>${lokasiTerdekat.distance?.toFixed(2) ?? '?'} km`
                    );
                    map.panTo([lokasiTerdekat.latitude, lokasiTerdekat.longitude]);
                    // Tambah event tombol
                    document.getElementById("checkin-btn")?.addEventListener("click", async () => {
                        const distance = lokasiTerdekat.distance;
                        if (distance > 40000) { //jarak km
                            Swal.fire({
                                title: 'Check-in failed',
                                text: `You're too far away (${(distance).toFixed(2)} km) from the nearest office (${lokasiTerdekat.location_name}).`,
                                icon: 'error',
                                confirmButtonText: 'Retry'
                            })
                            return;
                        }
                        const result = await fetch('/api/attendance/checkin', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                location_id: lokasiTerdekat.id
                            })
                        });

                        const res = await result.json();
                        if (res.success) {
                            Swal.fire({
                                title: "Success",
                                text: "Check-in success",
                                icon: "success"
                            });
                            window.location.reload
                        } else {
                            Swal.fire({
                                title: 'Check-in failed',
                                text: `Gagal check-in: ${res.message}`,
                                icon: 'error',
                                confirmButtonText: 'Retry'
                            })
                        }
                    });

                    document.getElementById("checkout-btn")?.addEventListener("click", async () => {
                        const distance = lokasiTerdekat.distance;
                        if (distance > 40) {
                            Swal.fire({
                                title: 'Check-out failed',
                                text: `You're too far away (${(distance).toFixed(2)} km) from the nearest office (${lokasiTerdekat.location_name}).`,
                                icon: 'error',
                                confirmButtonText: 'Retry'
                            })
                            return;
                        }
                        const result = await fetch('/api/attendance/checkout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            }
                        });

                        const res = await result.json();
                        if (res.success) {
                            Swal.fire({
                                title: "Success",
                                text: "Check-out success",
                                icon: "success"
                            });
                        } else {
                            Swal.fire({
                                title: 'Check-in failed',
                                text: `Gagal check-in: ${res.message}`,
                                icon: 'error',
                                confirmButtonText: 'Retry'
                            })
                        }
                    });
                }

            } catch (err) {
                console.error(err.message)
            }

        }, err => {
            console.error(err.message)
        });
    </script>




    {{-- <script>
        const tujuanLat = -6.20938;
        const tujuanLng = 106.85132;

        const map = L.map('map').setView([tujuanLat, tujuanLng], 15);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Tambahin lingkaran saja, tanpa marker tujuan
        L.circle([tujuanLat, tujuanLng], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.3,
            radius: 100
        }).addTo(map);

        // Geolocation user
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                const userLat = pos.coords.latitude;
                const userLng = pos.coords.longitude;

                const userIcon = L.divIcon({
                    className: '', // kosong biar kita handle full styling
                    html: `<div class="w-4 h-4 bg-blue-500 rounded-full border-2 border-white shadow-md"></div>`,
                    iconSize: [16, 16], // size sesuai ukuran HTML
                    iconAnchor: [8, 8] // posisi anchor ke tengah
                });

                L.marker([userLat, userLng], {
                        icon: userIcon
                    })
                    .addTo(map)
                    .bindPopup("📍 Your Location")
                    .openPopup();

                map.flyTo([userLat, userLng], 15);

                // Bikin link Google Maps arahkan ke tujuan
                const gmapUrl =
                    `https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLng}&destination=${tujuanLat},${tujuanLng}`;
                const btn = document.getElementById('directionBtn');
                btn.href = gmapUrl;
                document.getElementById('gmap-link').style.display = 'block';

            }, function(err) {
                alert("Gagal ambil lokasi kamu: " + err.message);
            });
        } else {
            alert("Browser kamu tidak mendukung geolocation.");
        }
    </script> --}}
    {{-- <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-dashed rounded-lg border-gray-700"> --}}
    {{-- <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="flex items-center justify-center h-24 rounded-sm bg-gray-800">
            <p class="text-2xl text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center h-24 rounded-sm bg-gray-800">
            <p class="text-2xl text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center h-24 rounded-sm bg-gray-800">
            <p class="text-2xl text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
    </div>
    <div class="flex items-center justify-center h-48 mb-4 rounded-sm bg-gray-800">
        <p class="text-2xl text-gray-500">
            <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 18 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 1v16M1 9h16" />
            </svg>
        </p>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="flex items-center justify-center rounded-sm h-28 bg-gray-800">
            <p class="text-2xl text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded-sm h-28 bg-gray-800">
            <p class="text-2xl text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded-sm h-28 bg-gray-800">
            <p class="text-2xl text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded-sm h-28 bg-gray-800">
            <p class="text-2xl text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
    </div>
    <div class="flex items-center justify-center h-48 mb-4 rounded-sm bg-gray-800">
        <p class="text-2xl text-gray-500">
            <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 18 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 1v16M1 9h16" />
            </svg>
        </p>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div class="flex items-center justify-center rounded-sm  h-28 bg-gray-800">
            <p class="text-2xl text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded-sm  h-28 bg-gray-800">
            <p class="text-2xl text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded-sm  h-28 bg-gray-800">
            <p class="text-2xl text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded-sm  h-28 bg-gray-800">
            <p class="text-2xl text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
    </div> --}}
    {{-- </div>
    </div> --}}
</x-app-layout>
