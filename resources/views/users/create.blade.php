<x-app-layout>
    {{-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"> --}}
    {{-- <h1 class="text-2xl font-bold text-white mb-6">Create User</h1> --}}
    <form action="{{ route('users.store') }}" method="POST" autocomplete="off"
        class="p-6 max-w-xl rounded-lg shadow-md space-y-2">
        @csrf

        <h2 class="text-2xl font-bold text-white">Create New User</h2>

        <!-- Name -->
        <div>
            <label for="name" class="block mb-2 text-sm font-medium text-white">Name</label>
            <input type="text" id="name" name="name" maxlength="50" required
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5">
        </div>

        <!-- Position -->
        <div>
            <label for="position" class="block mb-2 text-sm font-medium text-white">Position</label>
            <input type="text" id="position" name="position" maxlength="50"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5">
        </div>

        <!-- Department -->
        <div>
            <label for="departmen" class="block mb-2 text-sm font-medium text-white">Department</label>
            <input type="text" id="departmen" name="departmen" maxlength="100"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5">
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-white">Email</label>
            <input type="email" id="email" name="email" maxlength="150" required autocomplete="false"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5">
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block mb-2 text-sm font-medium text-white">Phone</label>
            <input type="text" id="phone" name="phone" maxlength="20"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5">
        </div>

        <!-- Password -->
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

        <!-- Role -->
        <div>
            <label for="role" class="block mb-2 text-sm font-medium text-white">Role</label>
            <select id="role" name="role"
                class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg w-full p-2.5">
                <option value="employee" selected>Employee</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block mb-2 text-sm font-medium text-white">Status</label>
            <select id="status" name="status"
                class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg w-full p-2.5">
                <option value="active" selected>Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 flex justify-end">
            <button type="submit"
                class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Submit
            </button>
        </div>
    </form>

</x-app-layout>
