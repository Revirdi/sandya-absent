<x-app-layout>
    <form action="{{ route('attendances.store') }}" method="POST" autocomplete="off"
        class="p-6 max-w-xl rounded-lg shadow-md space-y-2 bg-gray-200">
        @csrf
        @method('POST')
        <h2 class="text-2xl font-bold" style="margin-bottom: 20px">Create Attendance</h2>
        <div>
            <label for="user_id" class="block mb-2 text-sm font-medium">User</label>
            <select id="user_id" name="user_id"
                class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg w-full p-2.5">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="attendance_date" class="block mb-2 text-sm font-medium">Date</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                    </svg>
                </div>
                <input datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" id="attendance_date" type="text"
                    name="attendance_date" value="{{ old('attendance_date') }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Select date">
            </div>
        </div>
        <div>
            <label for="location_id" class="block mb-2 text-sm font-medium">Location</label>
            <select id="location_id" name="location_id"
                class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg w-full p-2.5">
                @foreach ($attendanceLocations as $loc)
                    <option value="{{ $loc->id }}">
                        {{ $loc->location_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="check_in_time" class="block mb-2 text-sm font-medium">Check-in</label>
            <input type="time" id="check_in_time" name="check_in_time"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                onclick="this.showPicker()">
        </div>
        <div>
            <label for="check_out_time" class="block mb-2 text-sm font-medium">Check-out</label>
            <input type="time" id="check_out_time" name="check_out_time"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                onclick="this.showPicker()">
        </div>
        <div>
            <label for="status" class="block mb-2 text-sm font-medium">Status</label>
            <select id="status" name="status"
                class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg w-full p-2.5">
                <option value="present">
                    Present
                </option>
                <option value="absent">
                    Absent
                </option>
            </select>
        </div>
        <div>
            <label for="remarks" class="block mb-2 text-sm font-medium">Remarks</label>
            <textarea id="remarks"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                name="remarks"></textarea>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit"
                class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Submit
            </button>
        </div>
    </form>
</x-app-layout>
