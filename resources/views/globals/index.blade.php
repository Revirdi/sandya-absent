<x-app-layout>
    <h1 class="text-2xl font-bold text-white mb-6">Attendance Distance</h1>
        <div class="flex justify-end">
        <a href="{{ url('/globals/create') }}"
            class="text-white focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-blue-800">
            Create New Settings
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm divide-y divide-gray-700 border">
            <thead class="bg-gray-800 text-white border">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Value</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700 text-white">
                @forelse($settings as $setting)
                    <tr>
                        <td class="px-6 py-4">{{ $setting->id }}</td>
                        <td class="px-6 py-4">{{ $setting->name }}</td>
                        <td class="px-6 py-4">{{ $setting->value }}</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <a href="{{ route('globals.edit', $setting) }}"
                                class="px-2 py-1 bg-blue-500 hover:bg-blue-600 rounded text-white">Edit</a>
                            <form action="{{ route('globals.destroy', $setting) }}" method="POST"
                                onsubmit="return confirm('Delete this setting?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-2 py-1 bg-red-600 hover:bg-red-700 rounded">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-400">No data found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
