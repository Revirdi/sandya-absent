<x-app-layout>

    <h1 class="text-2xl font-bold text-white mb-6">Data Attendances {{ auth()->user()->name }}</h1>
    <div class="flex justify-between mb-2">
        <input id="monthPicker" placeholder="Select Month" required />

        <a href="#" id="downloadPdfBtn"
            class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Download PDF
        </a>
    </div>
    <div class="overflow-x-auto bg-gray-400 shadow rounded-lg">
        <table id="userAttendanceTable" class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-900 text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Date</th>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Location</th>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Check-In</th>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Check-Out</th>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Work Time</th>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Status</th>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Remarks</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700 text-gray-200">
                @foreach ($daysInMonth as $day)
                    @php
                        $isWeekend = $day['is_weekend'];
                        $isHoliday = $day['is_holiday']['status'];
                        $attendance = $day['attendance'];
                    @endphp
                    <tr class="@if ($isWeekend || $isHoliday) bg-red-500 @endif">
                        <td class="px-6 py-4 whitespace-nowrap text-xs md:text-sm lg:text-md text-gray-300">
                            {{ \Carbon\Carbon::parse($day['date'])->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs md:text-sm lg:text-md text-gray-300">
                            {{ $attendance->location->location_name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs md:text-sm lg:text-md text-gray-300">
                            {{ $attendance->check_in_time ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs md:text-sm lg:text-md text-gray-300">
                            {{ $attendance->check_out_time ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs md:text-sm lg:text-md text-gray-300">
                            @if (isset($day['working_minutes']))
                                @php
                                    $totalMinutes = floor($day['working_minutes']);
                                    $hours = floor($totalMinutes / 60);
                                    $minutes = $totalMinutes % 60;
                                @endphp

                                {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $minutes }}m
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs md:text-sm lg:text-md text-gray-300 ">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded text-xs md:text-sm lg:text-md font-medium">
                                @if ($attendance)
                                    Present
                                @elseif ($isHoliday)
                                    {{ $day['is_holiday']['name'] }}
                                @elseif ($isWeekend)
                                    Weekend
                                @else
                                    Absent
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs md:text-sm lg:text-md text-gray-300">
                            {{ $attendance->remarks ?? '-' }}
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="flex justify-end">
        {{ $daysInMonth->onEachSide(2)->links('vendor.pagination.tailwind') }}
    </div>
    {{-- JS FLATPICKR --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const monthParam = urlParams.get("month");
            const yearParam = urlParams.get("year");

            // Format: YYYY-MM
            let defaultValue;
            if (monthParam && yearParam) {
                defaultValue = `${yearParam}-${monthParam.padStart(2, '0')}`;
            } else {
                const now = new Date();
                defaultValue = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
            }

            const monthPicker = document.getElementById("monthPicker");
            monthPicker.value = defaultValue;
            flatpickr("#monthPicker", {
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true,
                        dateFormat: "Y-m", // format backend
                        altFormat: "F Y", // format tampilan
                        theme: "light"
                    })
                ],
                onChange: function(selectedDates, dateStr) {
                    if (dateStr) {
                        const [year, month] = dateStr.split("-");
                        const url = new URL(window.location.href);
                        url.searchParams.set("month", month);
                        url.searchParams.set("year", year);
                        url.searchParams.set("page", 1);
                        window.location.href = url.toString(); // Redirect langsung
                    }
                }
            });
            document.getElementById("downloadPdfBtn").addEventListener("click", function(e) {
                e.preventDefault();

                const dateStr = document.getElementById("monthPicker").value;
                if (!dateStr) {
                    alert("Please select a month first.");
                    return;
                }

                const [year, month] = dateStr.split("-");
                const url = `{{ route('attendance.pdf') }}?month=${month}&year=${year}`;
                window.location.href = url;
            });
        });
    </script>
</x-app-layout>
