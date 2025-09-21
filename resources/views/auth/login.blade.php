<x-guest-layout>
    <section class="bg-gray-900 h-screen">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto h-screen lg:py-0">
            <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-white">
                {{-- <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg"
                    alt="logo"> --}}
                PACE
            </a>
            <div class="w-full  rounded-lg shadow border md:mt-0 sm:max-w-md xl:p-0 bg-gray-80 border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight  md:text-2xl text-white">
                        Sign in to your account
                    </h1>
                    {{-- <form class="space-y-4 md:space-y-6" action="#"> --}}
                    <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-white">Your email</label>
                            <input type="email" name="email" id="email"
                                class="border rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
                                placeholder="name@company.com" required="">
                        </div>
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
                        <button type="submit"
                            class="w-full text-white  focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">Sign
                            in</button>
                        <p class="text-sm text-gray-50">
                            Don’t have an account yet? <a href="/register"
                                class="font-medium hover:underline text-blue-500">Sign up</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
