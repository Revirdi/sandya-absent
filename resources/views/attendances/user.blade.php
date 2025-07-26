<x-app-layout>

    <h1 class="text-2xl font-bold text-white mb-6">Data Attendances {{ auth()->user()->name }}</h1>
    <div class="flex justify-between mb-2">
        <a href="{{ route('attendance.pdf') }}"
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
                                {{ rtrim(rtrim(number_format($day['working_minutes'], 2, '.', ''), '0'), '.') }} minutes
                            @else
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
</x-app-layout>
