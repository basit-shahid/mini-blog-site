<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Student Portal - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left Side: educational branding/image -->
        <div class="hidden md:flex md:w-1/2 bg-indigo-900 justify-center items-center relative overflow-hidden">
            <!-- Background overlay or subtle pattern -->
            <div class="absolute inset-0 opacity-20 bg-[url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071&auto=format&fit=crop')] bg-cover bg-center mix-blend-overlay"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-indigo-800 to-blue-900 opacity-90"></div>
            
            <div class="relative z-10 p-12 text-white flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mb-8 backdrop-blur-sm border border-white/20 shadow-xl">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-4 tracking-tight">University Portal</h1>
                <p class="text-lg text-indigo-100 max-w-md leading-relaxed">Your secure gateway to academic excellence. Access your courses, connect with teachers, and manage your student journey.</p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-white">
            <div class="w-full max-w-md">
                <div class="text-center mb-10 md:hidden">
                    <div class="inline-flex w-16 h-16 bg-indigo-100 rounded-full items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">University Portal</h2>
                </div>

                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Welcome Back</h2>
                    <p class="text-gray-500 font-medium">Please enter your credentials to log in.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700">Email Address</label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="block w-full pl-10 sm:text-sm border-gray-300 rounded-lg focus:ring-indigo-600 focus:border-indigo-600 py-3 transition duration-150 ease-in-out bg-gray-50 hover:bg-white" placeholder="admin@lms.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700">Password</label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password" class="block w-full pl-10 sm:text-sm border-gray-300 rounded-lg focus:ring-indigo-600 focus:border-indigo-600 py-3 transition duration-150 ease-in-out bg-gray-50 hover:bg-white" placeholder="••••••••" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded transition duration-150 ease-in-out">
                            <label for="remember_me" class="ml-2 block text-sm font-medium text-gray-700">
                                Remember my device
                            </label>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out shadow-indigo-600/30">
                            Sign In to Dashboard
                        </button>
                    </div>
                </form>
                
                <div class="mt-8 pt-8 border-t border-gray-200 text-center">
                    <p class="text-sm text-gray-500">Need an account? Please contact your institution's administrator.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
