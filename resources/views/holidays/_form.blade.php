<div>
    <label for="name" class="block mb-2 text-sm font-medium $location">Name</label>
    <input type="text" id="name" name="name" maxlength="50" required
        value="{{ old('name', $holiday->name ?? '') }}"
        class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5">
</div>
<div>
    <label for="holiday_date" class="block mb-2 text-sm font-medium $location">Name</label>
    <div class="relative max-w-sm">
        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
            </svg>
        </div>
        <input datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" id="holiday_date" type="text"
            name="holiday_date" value="{{ old('date', $holiday->holiday_date ?? '') }}"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Select date">
    </div>
</div>
<div>
    <label for="description" class="block mb-2 text-sm font-medium $location">Description</label>
    <textarea id="description"
        class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
        name="description">{{ old('description', $holiday->description ?? '') }}</textarea>
</div>
