<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TenaTracker - Track Your Progress</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="antialiased bg-gray-50">
    <nav x-data="{ isOpen: false }" class="fixed z-50 w-full border-b border-gray-100 bg-white/80 backdrop-blur-sm">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex items-center flex-shrink-0">
                        <span class="text-2xl font-bold text-indigo-600">TenaTracker</span>
                    </div>
                </div>
                <div class="hidden space-x-4 sm:ml-6 sm:flex sm:items-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:text-indigo-600">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:text-indigo-600">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white transition bg-indigo-600 rounded-md hover:bg-indigo-700">Register</a>
                    @endauth
                </div>
                <div class="flex items-center -mr-2 sm:hidden">
                    <button @click="isOpen = !isOpen" class="inline-flex items-center justify-center p-2 text-gray-400 rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-6 h-6" x-show="!isOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="w-6 h-6" x-show="isOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div x-show="isOpen" class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:text-indigo-600">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:text-indigo-600">Login</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:text-indigo-600">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <section class="relative min-h-screen pt-16 bg-gradient-to-br from-blue-400 to-purple-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[calc(100vh-4rem)] flex items-center">
            <div class="w-full text-center lg:text-left">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl md:text-5xl lg:text-6xl">
                    <span class="block mb-2">Track Your Progress</span>
                    <span class="block text-white">Achieve Your Goals</span>
                </h1>
                <p class="mt-3 text-base text-white/90 sm:mt-5 sm:text-lg md:text-xl lg:max-w-xl">
                    Join our community of achievers. Set challenges, track your progress, and celebrate your success with like-minded individuals.
                </p>
                <div class="mt-8 space-y-4 sm:space-y-0 sm:mt-10">
                    <a href="{{ route('register') }}" class="inline-block w-full sm:w-auto px-8 py-3 font-medium text-white transition bg-indigo-600 rounded-md shadow hover:bg-indigo-700">
                        Get Started Today
                    </a>
                    <a href="#features" class="inline-block w-full sm:w-auto px-8 py-3 sm:ml-4 font-medium text-white transition rounded-md bg-white/20 hover:bg-white/30">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <section id="features" class="py-20 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Features that Empower You
                </h2>
                <p class="mt-4 text-xl text-gray-600">
                    Everything you need to stay motivated and achieve your goals
                </p>
            </div>
            <div class="grid grid-cols-1 gap-8 mt-12 sm:mt-20 md:grid-cols-2 lg:grid-cols-3">
                <!-- Progress Tracking -->
                <div class="relative p-6 transition bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md">
                    <div class="absolute p-3 bg-blue-500 rounded-lg -top-4 left-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="mt-8 text-xl font-semibold text-gray-900">Progress Tracking</h3>
                    <p class="mt-3 text-gray-500">Monitor your daily achievements and visualize your growth with detailed progress analytics.</p>
                </div>

                <!-- Challenge Creation -->
                <div class="relative p-6 transition bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md">
                    <div class="absolute p-3 bg-purple-500 rounded-lg -top-4 left-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h3 class="mt-8 text-xl font-semibold text-gray-900">Create Challenges</h3>
                    <p class="mt-3 text-gray-500">Design personalized challenges that align with your goals and inspire others to join.</p>
                </div>

                <!-- Community -->
                <div class="relative p-6 transition bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md">
                    <div class="absolute p-3 bg-pink-500 rounded-lg -top-4 left-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-8 text-xl font-semibold text-gray-900">Join the Community</h3>
                    <p class="mt-3 text-gray-500">Connect with like-minded individuals, share experiences, and grow together.</p>
                </div>
    
    </div>
</div>
</section>

<!-- Why TenaTracker Section -->
<section class="py-20 bg-gray-50">
<div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Why TenaTracker?</h2>
        <p class="max-w-2xl mx-auto mt-4 text-xl text-gray-500">Transform your goals into achievements with our powerful tracking platform</p>
    </div>
    <div class="grid gap-6 mt-8 sm:gap-8 sm:mt-12 md:grid-cols-2 lg:grid-cols-3">
        <div class="p-8 bg-white shadow-sm rounded-xl">
            <div class="mb-4 text-blue-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold">Stay Accountable</h3>
            <p class="mt-2 text-gray-500">Track your progress daily and maintain consistency with our intuitive tools.</p>
        </div>
        <div class="p-8 bg-white shadow-sm rounded-xl">
            <div class="mb-4 text-purple-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold">Boost Motivation</h3>
            <p class="mt-2 text-gray-500">Join challenges that inspire you and keep you motivated throughout your journey.</p>
        </div>
        <div class="p-8 bg-white shadow-sm rounded-xl">
            <div class="mb-4 text-pink-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold">Community Support</h3>
            <p class="mt-2 text-gray-500">Connect with others who share your goals and support each other's progress.</p>
        </div>
    </div>
</div>
</section>

<!-- CTA Section -->
<section class="py-12 sm:py-20 bg-gradient-to-r from-blue-500 to-purple-600">
    <div class="px-4 mx-auto text-center max-w-7xl sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-white lg:text-4xl">Ready to Start Your Journey?</h2>
        <p class="mt-4 text-lg sm:text-xl text-white/90">Join thousands of others who are achieving their goals with TenaTracker</p>
        <div class="mt-8">
            <a href="{{ route('register') }}" class="inline-block w-full sm:w-auto px-8 py-3 font-semibold text-blue-600 transition bg-white rounded-md hover:bg-blue-50">
            Get Started Free
        </a>
    </div>
</div>
</section>
    
    <!-- Footer -->
    <footer class="py-12 bg-gray-900">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="text-2xl font-bold text-white">TenaTracker</span>
                <p class="mt-4 text-gray-400">&copy; 2025 TenaTracker. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
