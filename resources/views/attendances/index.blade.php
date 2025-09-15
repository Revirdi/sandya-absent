<x-app-layout>

    <h1 class="text-2xl font-bold text-white mb-2">Attendances Users</h1>
    <div class="flex justify-between">
        <form method="GET" action="{{ route('attendances.index') }}" class="mb-4">
            <input type="text" name="name" placeholder="Search by name" value="{{ request('name') }}"
                class="border rounded p-2" />
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
        </form>
        <div class="flex justify-end mb-4">
            <a href="{{ route('attendances.create') }}"
                class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd"></path>
                </svg>
                Create Attendance
            </a>
        </div>

    </div>
    <div class="overflow-x-auto bg-gray-400 shadow rounded-lg">
        <table id="userAttendanceTable" class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-900 text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Date</th>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Name</th>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Location</th>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Check-In</th>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Check-Out</th>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Worktime</th>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Status</th>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Remarks</th>
                    <th class="px-6 py-3 text-left text-xs md:text-sm lg:text-md font-medium uppercase tracking-wider">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700 text-gray-200">
                @forelse($attendances as $index => $attendance)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-xs md:text-sm lg:text-md text-gray-300">
                            {{ \Carbon\Carbon::parse($attendance['attendance_date'])->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs md:text-sm lg:text-md text-gray-300">
                            {{ $attendance->user->name ?? '-' }}
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
                        @php
                            $checkIn = $attendance->check_in_time;
                            $checkOut = $attendance->check_out_time;

                            $minutes =
                                $checkIn && $checkOut
                                    ? \Carbon\Carbon::parse($checkIn)->diffInMinutes(\Carbon\Carbon::parse($checkOut))
                                    : null;

                            $hours = $minutes !== null ? floor($minutes / 60) : null;
                            $remainingMinutes = $minutes !== null ? $minutes % 60 : null;
                        @endphp

                        <td class="px-6 py-4 whitespace-nowrap text-xs md:text-sm lg:text-md text-gray-300">
                            @if ($minutes !== null)
                                {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $remainingMinutes }}m
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs md:text-sm lg:text-md">
                            {{ $attendance->status ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs md:text-sm lg:text-md text-gray-300">
                            {{ $attendance->remarks ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs md:text-sm lg:text-md text-gray-300">
                            <a href="{{ route('attendances.edit', $attendance->id) }}"
                                class="text-blue-400 hover:underline mr-4">Edit</a>

                            <!-- Tombol Delete (trigger modal) -->
                            <button type="button" data-modal-target="confirmDeleteModal{{ $attendance->id }}"
                                data-modal-toggle="confirmDeleteModal{{ $attendance->id }}"
                                class="text-red-400 hover:underline">
                                Delete
                            </button>

                            <!-- Modal Konfirmasi Delete -->
                            <div id="confirmDeleteModal{{ $attendance->id }}" tabindex="-1"
                                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                <div class="relative p-4 w-full max-w-md max-h-full">
                                    <div class="relative rounded-lg shadow-sm bg-gray-700">
                                        <button type="button"
                                            class="absolute top-3 end-2.5 text-gray-400 bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center hover:bg-gray-600 hover:text-white"
                                            data-modal-hide="confirmDeleteModal{{ $attendance->id }}">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                        <div class="p-4 md:p-5 text-center">
                                            <svg class="mx-auto mb-4 w-12 h-12 text-gray-200" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <h3 class="mb-5 text-lg font-normal text-gray-400 leading-snug">
                                                Are you sure you want to delete this attendance record?
                                            </h3>
                                            <form method="POST"
                                                action="{{ route('attendances.destroy', $attendance->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                    Yes, I'm sure
                                                </button>
                                                <button data-modal-hide="confirmDeleteModal{{ $attendance->id }}"
                                                    type="button"
                                                    class="py-2.5 px-5 ms-3 text-sm font-medium focus:outline-none rounded-lg border focus:z-10 focus:ring-4 focus:ring-gray-700 bg-gray-800 text-gray-400 border-gray-600 hover:text-white hover:bg-gray-700">
                                                    No, cancel
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="flex justify-end">
        {{ $attendances->onEachSide(2)->links('vendor.pagination.tailwind') }}
    </div>
</x-app-layout>
