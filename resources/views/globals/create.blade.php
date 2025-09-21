<x-app-layout>
    <form action="{{ route('globals.store') }}" method="POST" autocomplete="off"
        class="p-6 max-w-xl rounded-lg shadow-md space-y-4 bg-gray-200">
        @csrf
        <h2 class="text-2xl font-bold" style="margin-bottom: 20px">Create New Global Setting</h2>

        <!-- Name -->
        <div>
            <label class="block mb-1 font-medium">Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                class="w-full px-3 py-2 rounded bg-gray-100 border border-gray-300 focus:outline-none focus:border-blue-500"
                required>
            @error('name')
                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Value -->
        <div>
            <label class="block mb-1 font-medium">Value (meter)</label>
            <input type="text" name="value" value="{{ old('value') }}"
                class="w-full px-3 py-2 rounded bg-gray-100 border border-gray-300 focus:outline-none focus:border-blue-500"
                required>
            @error('value')
                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <div class="pt-4 flex justify-end">
            <button type="submit"
                class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Submit
            </button>
        </div>
    </form>
</x-app-layout>
