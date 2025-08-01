<x-app-layout>
    <form action="{{ route('attendance-location.store') }}" method="POST" autocomplete="off"
        class="p-6 max-w-xl rounded-lg shadow-md space-y-2 bg-gray-200">
        @csrf
        <h2 class="text-2xl font-bold" style="margin-bottom: 20px">Create New Location</h2>
        @include('attendance_locations._form')
        <div class="pt-4 flex justify-end">
            <button type="submit"
                class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Submit
            </button>
        </div>
    </form>
</x-app-layout>
