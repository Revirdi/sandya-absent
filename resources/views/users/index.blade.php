<x-app-layout>
    {{-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"> --}}
    <h1 class="text-2xl font-bold text-white mb-6">User List</h1>

    @if (session('success'))
        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded">
            {{ session('success') }}
        </div>
    @endif
    {{-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"> --}}
    <div class="overflow-x-auto bg-gray-400 shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-500 text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700 text-gray-200">
                @forelse($users as $index => $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('users.edit', $user->id) }}"
                                class="text-blue-400 hover:underline mr-4">Edit</a>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:underline"
                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- </div> --}}
    {{-- </div> --}}
</x-app-layout>
