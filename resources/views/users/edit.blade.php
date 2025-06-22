<x-app-layout>
    <h1 class="text-2xl font-bold text-white mb-6">Edit User {{ $user->name }}</h1>
    <form action="{{ route('users.update', $user->id) }}" method="POST" autocomplete="off" class="max-w-xl">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-white">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                maxlength="50" required>
        </div>

        {{-- Position --}}
        <div class="mb-4">
            <label for="position" class="block text-sm font-medium text-white">Position</label>
            <input type="text" name="position" id="position" value="{{ old('position', $user->position) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                maxlength="50">
        </div>

        {{-- Departmen --}}
        <div class="mb-4">
            <label for="departmen" class="block text-sm font-medium text-white">Departmen</label>
            <input type="text" name="departmen" id="departmen" value="{{ old('departmen', $user->departmen) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                maxlength="100">
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-white">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                maxlength="150" required>
        </div>

        {{-- Phone --}}
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-white">Phone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                maxlength="20">
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-white">Password</label>
            <div class="relative">
                <input id="password" type="password" name="password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <button type="button" id="togglePassword1" tabindex="-1"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                    <i class="fas fa-eye dark:text-white" id="eyeIcon1"></i>
                </button>
            </div>
        </div>

        <div>
            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-white">Confirm
                Password</label>
            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <button type="button" id="togglePassword2" tabindex="-1"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                    <i class="fas fa-eye dark:text-white" id="eyeIcon2"></i>
                </button>
            </div>
        </div>

        {{-- Role --}}
        <div class="mb-4">
            <label for="role" class="block text-sm font-medium text-white">Role</label>
            <select name="role" id="role" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="employee" {{ old('role', $user->role) === 'employee' ? 'selected' : '' }}>Employee
                </option>
            </select>
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-white">Status</label>
            <select name="status" id="status" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive
                </option>
            </select>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end mt-6">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Update User
            </button>
        </div>
    </form>
</x-app-layout>
