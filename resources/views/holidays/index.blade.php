<x-app-layout>
    <h1 class="text-2xl font-bold text-white mb-6">Holidays Date</h1>
    <div class="flex justify-end">
        <a href="{{ url('/holidays/create') }}"
            class="text-white focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-blue-800">
            Create New Holidays
        </a>
    </div>
    <div class="overflow-x-auto bg-gray-400 shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-900 text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700 text-gray-200">
                @forelse($holidays as $index => $holiday)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $holiday->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                            {{ $holiday->holiday_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $holiday->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('holidays.edit', $holiday->id) }}"
                                class="text-blue-400 hover:underline mr-4">Edit</a>
                            <button type="button" data-modal-target="confirmDeleteModal{{ $holiday->id }}"
                                data-modal-toggle="confirmDeleteModal{{ $holiday->id }}"
                                class="text-red-400 hover:underline">
                                Delete
                            </button>
                            <div id="confirmDeleteModal{{ $holiday->id }}" tabindex="-1"
                                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                <div class="relative p-4 w-full max-w-md max-h-full">
                                    <div class="relative  rounded-lg shadow-sm bg-gray-700">
                                        <button type="button"
                                            class="absolute top-3 end-2.5 text-gray-400 bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center hover:bg-gray-600 hover:text-white"
                                            data-modal-hide="confirmDeleteModal{{ $holiday->id }}">
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
                                            <div class="break-words">
                                                <h3
                                                    class="mb-5 text-lg font-normal text-gray-400 break-words text-center leading-snug whitespace-normal">
                                                    Are you sure you want to delete this holidays date?
                                                </h3>
                                            </div>
                                            <form method="POST"
                                                action="{{ route('holidays.destroy', $holiday->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                    Yes, I'm sure
                                                </button>
                                                <button data-modal-hide="confirmDeleteModal{{ $holiday->id }}"
                                                    type="button"
                                                    class="py-2.5 px-5 ms-3 text-sm font-medium  focus:outline-none  rounded-lg border  focus:z-10 focus:ring-4 focus:ring-gray-700 bg-gray-800 text-gray-400 border-gray-600 hover:text-white hover:bg-gray-700">No,
                                                    cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No holidays found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
