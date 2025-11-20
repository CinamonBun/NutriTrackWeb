<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriTrack - Landing Page</title>
    <link href="./output.css" rel="stylesheet">
    <style>
        body {
            /* font-family: 'Inter', sans-serif; */
            font-family: 'Plus Jakarta Sans', sans-serif;
            /* font-family: "Geist", sans-serif; */
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hero Animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes pulse-custom {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(61, 204, 199, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(61, 204, 199, 0);
            }
        }

        .animate-float-1 {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-2 {
            animation: float 8s ease-in-out infinite reverse;
        }

        .animate-pulse-custom {
            animation: pulse-custom 2s infinite;
        }

        .gradient-text {
            background: linear-gradient(90deg, #3dccc7, #4CAF50);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .phone-3d {
            transform: perspective(1000px) rotateY(-15deg) rotateX(10deg);
            transition: transform 0.5s ease;
        }

        .phone-3d:hover {
            transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
        }

        /* Marquee Animations */
        .marquee-track {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            will-change: transform;
            animation: marquee-up 25s linear infinite;
            padding-top: 0;
            padding-bottom: 0;
        }

        .marquee-track.reverse {
            animation-name: marquee-down;
        }

        @keyframes marquee-up {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-50%);
            }
        }

        @keyframes marquee-down {
            0% {
                transform: translateY(-50%);
            }

            100% {
                transform: translateY(0);
            }
        }

        .marquee-col:hover .marquee-track {
            animation-play-state: paused;
        }

        @media (prefers-reduced-motion: reduce) {
            .marquee-track {
                animation: none !important;
            }
        }

        /* Animation Keyframes */
        @keyframes float-up-down {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes float-delayed {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .animate-float {
            animation: float-up-down 6s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float-delayed 7s ease-in-out infinite 1s;
        }

        .gradient-text {
            background: linear-gradient(to right, #3dccc7, #4CAF50);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Grid Pattern Background */
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .phone-container {
            transform: perspective(1000px) rotateY(-12deg) rotateX(5deg);
            transition: transform 0.5s ease-out;
            transform-style: preserve-3d;
        }

        .phone-container:hover {
            transform: perspective(1000px) rotateY(-5deg) rotateX(2deg);
        }

        /* Mobile Menu */
        .mobile-menu-panel {
            transform-origin: top right;
        }

        .mobile-menu-panel.animate-open {
            animation: mobileMenuIn 0.25s ease forwards;
        }

        .mobile-menu-panel.animate-close {
            animation: mobileMenuOut 0.2s ease forwards;
        }

        @keyframes mobileMenuIn {
            from {
                opacity: 0;
                transform: translateY(-12px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes mobileMenuOut {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            to {
                opacity: 0;
                transform: translateY(-8px) scale(0.95);
            }
        }

        #menu-toggle-btn svg {
            transition: transform 0.2s ease;
        }

        #menu-toggle-btn[aria-expanded="true"] svg {
            transform: rotate(90deg);
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <script src="https://kit.fontawesome.com/45b50d7995.js" crossorigin="anonymous"></script>
</head>

<body>

    <!-- Header -->
    <header id="sticky-header" class="fixed z-50 w-full transition-all duration-300 ease-in-out py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="relative flex justify-between items-center">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold">NutriTrack+</h1>
                </div>
                <ul class="hidden md:flex items-center space-x-8">
                    <li><a href="index.php" class="transition duration-200 transform">Home</a></li>
                    <li><a href="about.php" class="transition duration-200 transform">About Us</a></li>
                    <li><a href="features.php"
                            class="transition duration-200 transform">Features</a>
                    </li>
                    <li><a href="riviews.php" class="transition duration-200 transform">Riviews</a></li>
                    <li><a href="#" class="transition duration-200 transform">Download</a></li>
                </ul>
                <div class="hidden md:flex items-center space-x-3">
                    <a href="signin.php"
                        class="whitespace-nowrap transition duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none">
                        Sign In
                    </a>
                    <a href="signup.php"
                        class="inline-flex justify-center gap-2 text-white bg-[#3dccc7] hover:bg-[#68d8d6] px-4 py-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50">
                        Sign Up
                    </a>
                </div>
                <div class="md:hidden">
                    <button id="menu-toggle-btn" type="button" aria-expanded="false" aria-controls="mobile-menu"
                        aria-label="Toggle navigation" class="p-2 rounded-lg transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#3dccc7]">
                        <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </nav>
            <div id="mobile-menu" class="md:hidden hidden mt-3">
                <div class="mobile-menu-panel card shadow-lg rounded-xl p-6 space-y-4">
                    <div class="flex flex-col space-y-3">
                        <a href="index.php" class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Home</a>
                        <a href="about.php" class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">About Us</a>
                        <a href="features.php" class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Features</a>
                        <a href="riviews.php" class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Riviews</a>
                        <a href="#" class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Download</a>
                    </div>
                    <div class="flex flex-col gap-3 py-3 border-t border-neutral-200 dark:border-neutral-700">
                        <a href="signin.php"
                            class="inline-flex justify-center items-center gap-2 text-sm font-medium rounded-md py-2 px-4 card transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#3dccc7]">Sign
                            In</a>
                        <a href="signup.php"
                            class="inline-flex justify-center items-center gap-2 text-sm font-medium rounded-md py-2 px-4 text-white bg-[#3dccc7] hover:bg-[#68d8d6] transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#3dccc7]">Sign
                            Up</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main>
        <!-- Hero Section -->
        <section class="relative min-h-screen flex flex-col lg:flex-row items-center lg:items-center justify-center lg:justify-between px-[5%] overflow-hidden pt-32 pb-20 lg:py-0">

            <div class="hidden md:block absolute top-0 right-0 md:w-[380px] md:h-[380px] lg:w-[600px] lg:h-[600px] bg-primary/10 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="hidden md:block absolute bottom-0 left-0 md:w-[320px] md:h-[320px] lg:w-[500px] lg:h-[500px] bg-secondary/5 rounded-full blur-[100px] pointer-events-none"></div>

            <div class="relative z-10 w-full max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16">

                <div class="flex-1 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full card text-sm text-primary mb-6 animate-pulse">
                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                        New Feature: Chat Bot AI
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-7xl font-extrabold leading-tight mb-6">
                        Mulai <span class="gradient-text">Gaya Hidup</span> <br> Lebih Sehat.
                    </h1>

                    <p class="text-lg md:text-xl opacity-80 leading-relaxed mb-10 max-w-2xl mx-auto lg:mx-0">
                        Pantau kalori, nutrisi, dan aktivitas harianmu dalam satu aplikasi cerdas. Data akurat untuk hasil yang nyata.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-10">
                        <a href="#" class="inline-flex items-center justify-center gap-2 bg-primary text-white px-6 py-3 rounded-full font-semibold transition-all duration-300 hover:bg-primary-dark hover:-translate-y-1 hover:shadow-[0_10px_20px_rgba(61,204,199,0.3)]">
                            <i class="fab fa-google-play"></i>
                            <span>Google Play</span>
                        </a>
                        <a href="#" class="inline-flex items-center justify-center gap-2 card opacity-80 px-6 py-3 rounded-full font-semibold backdrop-blur-sm transition-all duration-300 hover:border-primary hover:text-primary hover:-translate-y-1">
                            <i class="fab fa-apple"></i>
                            <span>App Store</span>
                        </a>
                    </div>
                </div>

                <div class="flex-1 relative flex justify-center items-center perspective-container">

                    <div class="absolute top-[20%] -left-[5%] z-20 glass-card p-4 rounded-2xl animate-float-delayed hidden md:block w-40">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-full bg-orange-500/20 flex items-center justify-center text-orange-500">
                                <i class="fas fa-fire text-sm"></i>
                            </div>
                            <span class="text-xs opacity-90 font-semibold">Burned</span>
                        </div>
                        <div class="text-xl font-bold">840 <span class="text-xs font-normal opacity-70">kcal</span></div>
                        <div class="w-full h-1.5 bg-gray-700 rounded-full mt-2 overflow-hidden">
                            <div class="h-full bg-orange-500 w-[70%]"></div>
                        </div>
                    </div>

                    <div class="absolute bottom-[25%] -right-[5%] z-20 glass-card p-4 rounded-2xl animate-float hidden md:block w-40">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center text-red-500">
                                <i class="fas fa-heartbeat text-sm"></i>
                            </div>
                            <span class="text-xs opacity-90 font-semibold">Heart Rate</span>
                        </div>
                        <div class="text-xl font-bold">98 <span class="text-xs font-normal opacity-70">bpm</span></div>
                        <div class="mt-2 flex gap-1 items-end h-6 opacity-50">
                            <div class="w-1 bg-red-500 h-[40%] rounded-sm"></div>
                            <div class="w-1 bg-red-500 h-[70%] rounded-sm"></div>
                            <div class="w-1 bg-red-500 h-[50%] rounded-sm"></div>
                            <div class="w-1 bg-red-500 h-[100%] rounded-sm"></div>
                            <div class="w-1 bg-red-500 h-[60%] rounded-sm"></div>
                        </div>
                    </div>

                    <div class="phone-container relative w-[300px] h-[600px] bg-[#151515] rounded-[45px] shadow-[0_0_0_8px_#252525,0_50px_100px_rgba(0,0,0,0.6)] overflow-hidden">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[120px] h-[25px] bg-[#252525] rounded-b-2xl z-30"></div>

                        <div class="w-full h-full bg-gradient p-6 pt-12 flex flex-col relative">

                            <div class="flex justify-between items-center mb-8">
                                <div>
                                    <p class="opacity-70 text-xs">Hello, Alex</p>
                                    <h3 class="font-bold text-lg">Daily Progress</h3>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                                    <img src="assets/img/me.png" alt="User" class="w-8 h-8">
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl card mb-4">
                                <div class="flex justify-between items-end h-[100px] gap-2 mb-2">
                                    <div class="w-full bg-primary/20 rounded-t-md relative group h-[40%] hover:bg-primary/40 transition-all"></div>
                                    <div class="w-full bg-primary/20 rounded-t-md relative group h-[60%] hover:bg-primary/40 transition-all"></div>
                                    <div class="w-full bg-primary rounded-t-md relative group h-[85%] shadow-[0_0_20px_rgba(61,204,199,0.4)]"></div>
                                    <div class="w-full bg-primary/20 rounded-t-md relative group h-[50%] hover:bg-primary/40 transition-all"></div>
                                    <div class="w-full bg-primary/20 rounded-t-md relative group h-[70%] hover:bg-primary/40 transition-all"></div>
                                </div>
                                <p class="text-center text-xs text-gray-400 mt-2">Calories Intake vs Goal</p>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center gap-3 p-3 rounded-xl card">
                                    <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center text-green-500">
                                        <i class="fas fa-apple-alt"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold">Breakfast</h4>
                                        <p class="text-xs opacity-70">Oatmeal & Berries</p>
                                    </div>
                                    <span class="text-sm font-bold">320</span>
                                </div>

                                <div class="flex items-center gap-3 p-3 rounded-xl card">
                                    <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-500">
                                        <i class="fas fa-glass-whiskey"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold">Hydration</h4>
                                        <p class="text-xs opacity-70">6/8 Glasses</p>
                                    </div>
                                    <span class="text-sm font-bold">75%</span>
                                </div>

                                <div class="flex items-center gap-3 p-3 rounded-xl card">
                                    <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center text-purple-500">
                                        <i class="fas fa-running"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold">Evening Run</h4>
                                        <p class="text-xs opacity-70">Scheduled 6:00 PM</p>
                                    </div>
                                    <span class="text-xs bg-purple-500/20 text-purple-400 px-2 py-1 rounded">Soon</span>
                                </div>
                            </div>

                            <div class="mt-auto flex justify-between opacity-70 pt-4 border-t">
                                <i class="fas fa-home text-primary"></i>
                                <i class="fas fa-chart-bar hover:text-white transition"></i>
                                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center -mt-8 shadow-lg text-white border-2 border-primary">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <i class="fas fa-book hover:text-white transition"></i>
                                <i class="fas fa-user hover:text-white transition"></i>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why NutriTrack Section -->
        <section class="relative text-center py-16 sm:py-32 overflow-hidden">
            <div class="relative z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-6 md:mb-0">
                        <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">
                            Why NutriTrack ?
                        </h2>
                        <p class="mt-3 text-lg dark:opacity-80 max-w-xl mx-auto">
                            Get expert nutritional guidance and personalized meal plans specifically for your journey.
                        </p>
                    </div>

                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="mt-12 max-w-6xl mx-auto rounded-lg shadow-md card hover:border-[#0F9E99] overflow-hidden">
                            <div class="grid md:grid-cols-2 gap-0">

                                <div class="p-8 md:p-12 flex flex-col justify-center">
                                    <div class="space-y-6">

                                        <div class="flex items-start gap-4">
                                            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-brain text-primary text-xl"></i>
                                            </div>
                                            <div class="text-left">
                                                <h3 class="font-semibold text-lg mb-2">Smart AI Recommendations</h3>
                                                <p class="opacity-80 text-sm">Dapatkan rekomendasi makanan cerdas berdasarkan kebutuhan nutrisi dan preferensi Anda dengan teknologi AI.</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4">
                                            <div class="w-12 h-12 bg-secondary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-chart-pie text-secondary text-xl"></i>
                                            </div>
                                            <div class="text-left">
                                                <h3 class="font-semibold text-lg mb-2">Detailed Analytics</h3>
                                                <p class="opacity-80 text-sm">Visualisasi lengkap dari asupan nutrisi harian, mingguan, dan bulanan dalam grafik yang mudah dipahami.</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4">
                                            <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-users text-accent text-xl"></i>
                                            </div>
                                            <div class="text-left">
                                                <h3 class="font-semibold text-lg mb-2">Community Support</h3>
                                                <p class="opacity-80 text-sm">Bergabung dengan komunitas pengguna yang saling mendukung dalam perjalanan hidup sehat mereka.</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4">
                                            <div class="w-12 h-12 bg-[#FFC107]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-shield-alt text-[#FFC107] text-xl"></i>
                                            </div>
                                            <div class="text-left">
                                                <h3 class="font-semibold text-lg mb-2">Data Security</h3>
                                                <p class="opacity-80 text-sm">Data kesehatan Anda tersimpan aman dengan enkripsi tingkat tinggi dan privasi terjaga.</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="bg-gradient-to-br from-primary/5 to-secondary/5 p-8 md:p-12 flex items-center justify-center">
                                    <div class="relative w-full max-w-sm">
                                        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 rounded-full blur-3xl"></div>
                                        <div class="absolute bottom-0 left-0 w-32 h-32 bg-secondary/20 rounded-full blur-3xl"></div>

                                        <div class="relative card rounded-2xl p-6 shadow-xl">
                                            <div class="text-center mb-6">
                                                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary rounded-2xl mb-4">
                                                    <i class="fas fa-star text-white text-2xl"></i>
                                                </div>
                                                <h4 class="font-bold text-2xl mb-2">10,000+</h4>
                                                <p class="opacity-80">Happy Users</p>
                                            </div>

                                            <div class="space-y-3">
                                                <div class="flex items-center justify-between card p-3 rounded-xl">
                                                    <span class="text-sm">User Rating</span>
                                                    <div class="flex items-center gap-1">
                                                        <i class="fas fa-star text-[#FFC107] text-xs"></i>
                                                        <span class="font-semibold">4.8</span>
                                                    </div>
                                                </div>

                                                <div class="flex items-center justify-between card p-3 rounded-xl">
                                                    <span class="text-sm">Meals Tracked</span>
                                                    <span class="font-semibold">500K+</span>
                                                </div>

                                                <div class="flex items-center justify-between card p-3 rounded-xl">
                                                    <span class="text-sm">Success Rate</span>
                                                    <span class="font-semibold text-primary">92%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- See NutriTrack in Action Section -->
            <section class="py-16 sm:py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row items-center justify-between mb-12 sm:mb-16">
                        <div class="text-center md:text-left mb-6 md:mb-0">
                            <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">
                                See NutriTrack in Action</h2>
                            <p class="mt-3 text-lg max-w-xl dark:opacity-80">
                                Dive into our core functionalities tailored for your goals.
                            </p>
                        </div>
                        <a href="#"
                            class="text-white px-6 py-3 rounded-lg font-medium bg-[#3dccc7] hover:bg-[#68d8d6] transition-colors duration-200">
                            See more features
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                        <!-- Feature Card 1 -->
                        <div class="rounded-lg shadow-md card hover:border-[#0F9E99] p-6 flex flex-col h-full">
                            <div class="mb-4">
                                <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mb-4">
                                    <i class="fas fa-camera text-primary text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-bold mb-2">Scan Makanan</h3>
                                <p class="opacity-80 text-sm mb-4">Foto makananmu dan AI kami akan mengidentifikasi serta menghitung nutrisinya secara otomatis.</p>
                            </div>
                            <div class="mt-auto">
                                <div class="card rounded-lg p-4 bg-gradient-to-br from-primary/5 to-transparent">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 rounded-full bg-primary/20"></div>
                                        <div class="flex-1">
                                            <div class="h-3 bg-primary/20 rounded w-3/4 mb-2"></div>
                                            <div class="h-2 bg-primary/10 rounded w-1/2"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="flex-1 h-8 bg-primary/20 rounded"></div>
                                        <div class="flex-1 h-8 bg-primary/20 rounded"></div>
                                        <div class="flex-1 h-8 bg-primary/20 rounded"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Feature Card 2 -->
                        <div class="rounded-lg shadow-md card hover:border-[#0F9E99] p-6 flex flex-col h-full">
                            <div class="mb-4">
                                <div class="w-16 h-16 bg-secondary/10 rounded-2xl flex items-center justify-center mb-4">
                                    <i class="fas fa-chart-line text-secondary text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-bold mb-2">Progress Tracking</h3>
                                <p class="opacity-80 text-sm mb-4">Monitor perkembangan berat badan, kalori, dan nutrisi dengan grafik yang detail dan mudah dipahami.</p>
                            </div>
                            <div class="mt-auto">
                                <div class="card rounded-lg p-4 bg-gradient-to-br from-secondary/5 to-transparent">
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs opacity-80">Protein</span>
                                            <span class="text-xs font-semibold">75%</span>
                                        </div>
                                        <div class="h-2 bg-secondary/10 rounded-full overflow-hidden">
                                            <div class="h-full bg-secondary w-3/4 rounded-full"></div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs opacity-80">Karbo</span>
                                            <span class="text-xs font-semibold">60%</span>
                                        </div>
                                        <div class="h-2 bg-secondary/10 rounded-full overflow-hidden">
                                            <div class="h-full bg-secondary w-3/5 rounded-full"></div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs opacity-80">Lemak</span>
                                            <span class="text-xs font-semibold">85%</span>
                                        </div>
                                        <div class="h-2 bg-secondary/10 rounded-full overflow-hidden">
                                            <div class="h-full bg-secondary w-5/6 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Feature Card 3 -->
                        <div class="rounded-lg shadow-md card hover:border-[#0F9E99] p-6 flex flex-col h-full">
                            <div class="mb-4">
                                <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center mb-4">
                                    <i class="fas fa-book-open text-accent text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-bold mb-2">Meal Planning</h3>
                                <p class="opacity-80 text-sm mb-4">Rencanakan menu makanan mingguan dengan rekomendasi resep sehat yang disesuaikan dengan kebutuhanmu.</p>
                            </div>
                            <div class="mt-auto">
                                <div class="card rounded-lg p-4 bg-gradient-to-br from-accent/5 to-transparent">
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-3 p-2 bg-accent/10 rounded-lg">
                                            <div class="w-8 h-8 bg-accent/20 rounded"></div>
                                            <div class="flex-1">
                                                <div class="h-2 bg-accent/30 rounded w-3/4 mb-1"></div>
                                                <div class="h-2 bg-accent/20 rounded w-1/2"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 p-2 bg-accent/10 rounded-lg">
                                            <div class="w-8 h-8 bg-accent/20 rounded"></div>
                                            <div class="flex-1">
                                                <div class="h-2 bg-accent/30 rounded w-3/4 mb-1"></div>
                                                <div class="h-2 bg-accent/20 rounded w-1/2"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 p-2 bg-accent/10 rounded-lg">
                                            <div class="w-8 h-8 bg-accent/20 rounded"></div>
                                            <div class="flex-1">
                                                <div class="h-2 bg-accent/30 rounded w-3/4 mb-1"></div>
                                                <div class="h-2 bg-accent/20 rounded w-1/2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Riview Section -->
            <section class="py-16 sm:py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-6">
                        <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Reviews</h2>
                        <p class="mt-3 text-lg max-w-xl mx-auto dark:opacity-80">
                            What are they saying about us?
                        </p>
                    </div>
                    <div class="relative mt-12 max-w-6xl mx-auto overflow-hidden rounded-lg h-[550px]">
                        <div class="absolute inset-x-0 top-0 h-16 pointer-events-none fade-top z-10">
                        </div>
                        <div class="absolute inset-x-0 bottom-0 h-16 pointer-events-none fade-bottom z-10">
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 h-full">

                            <!-- 1 -->
                            <div class="marquee-col h-full overflow-y-hidden">
                                <div class="marquee-track reverse px-2 py-4">
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/34373b/ffffff?text=A"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Sarah K.</div>
                                                <div class="text-xs">@sarahk</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">NutriTrack changed my life!
                                            So easy to track meals.</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/f87171/ffffff?text=B"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Mike T.</div>
                                                <div class="text-xs">@miket</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Best nutrition app I've
                                            ever used. Highly recommend!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/34d399/ffffff?text=C"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Lina R.</div>
                                                <div class="text-xs">@linar</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Love the personalized meal
                                            plans. So accurate!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/a78bfa/ffffff?text=D"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">James L.</div>
                                                <div class="text-xs">@jamesl</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Perfect for my fitness
                                            journey. Thank you!</p>
                                    </div>
                                    <!-- Duplikat -->
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/34373b/ffffff?text=A"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Sarah K.</div>
                                                <div class="text-xs">@sarahk</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">NutriTrack changed my life!
                                            So easy to track meals.</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/f87171/ffffff?text=B"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Mike T.</div>
                                                <div class="text-xs">@miket</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Best nutrition app I've
                                            ever used. Highly recommend!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/34d399/ffffff?text=C"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Lina R.</div>
                                                <div class="text-xs">@linar</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Love the personalized meal
                                            plans. So accurate!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/a78bfa/ffffff?text=D"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">James L.</div>
                                                <div class="text-xs">@jamesl</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Perfect for my fitness
                                            journey. Thank you!</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 2 -->
                            <div class="marquee-col h-full overflow-y-hidden">
                                <div class="marquee-track px-2 py-4">
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/60a5fa/ffffff?text=E"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Emma W.</div>
                                                <div class="text-xs">@emmaw</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">So intuitive and beautiful
                                            UI. Love it!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/fbbf24/ffffff?text=F"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">David H.</div>
                                                <div class="text-xs">@davidh</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Helped me lose 10kg in 3
                                            months. Amazing!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/ec4899/ffffff?text=G"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Anna P.</div>
                                                <div class="text-xs">@annap</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">The calorie tracker is spot
                                            on. Great job!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/8b5cf6/ffffff?text=H"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Tom B.</div>
                                                <div class="text-xs">@tomb</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Worth every penny. Best
                                            health app ever.</p>
                                    </div>
                                    <!-- Duplikat -->
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/60a5fa/ffffff?text=E"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Emma W.</div>
                                                <div class="text-xs">@emmaw</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">So intuitive and beautiful
                                            UI. Love it!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/fbbf24/ffffff?text=F"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">David H.</div>
                                                <div class="text-xs">@davidh</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Helped me lose 10kg in 3
                                            months. Amazing!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/ec4899/ffffff?text=G"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Anna P.</div>
                                                <div class="text-xs">@annap</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">The calorie tracker is spot
                                            on. Great job!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/8b5cf6/ffffff?text=H"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Tom B.</div>
                                                <div class="text-xs">@tomb</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Worth every penny. Best
                                            health app ever.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 3 -->
                            <div class="marquee-col h-full overflow-y-hidden hidden lg:block">
                                <div class="marquee-track reverse px-2 py-4">
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/10b981/ffffff?text=I"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Chris M.</div>
                                                <div class="text-xs">@chrism</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Game changer for my diet.
                                            Thank you!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/f43f5e/ffffff?text=J"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Nina S.</div>
                                                <div class="text-xs">@ninas</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">So easy to use. I track
                                            everything now.</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/10b981/ffffff?text=I"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Chris M.</div>
                                                <div class="text-xs">@chrism</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Game changer for my diet.
                                            Thank you!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/f43f5e/ffffff?text=J"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Nina S.</div>
                                                <div class="text-xs">@ninas</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">So easy to use. I track
                                            everything now.</p>
                                    </div>
                                    <!-- Duplikat -->
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/34373b/ffffff?text=A"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Sarah K.</div>
                                                <div class="text-xs">@sarahk</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">NutriTrack changed my life!
                                            So easy to track meals.</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/f87171/ffffff?text=B"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Mike T.</div>
                                                <div class="text-xs">@miket</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Best nutrition app I've
                                            ever used. Highly recommend!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/34d399/ffffff?text=C"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Lina R.</div>
                                                <div class="text-xs">@linar</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Love the personalized meal
                                            plans. So accurate!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/a78bfa/ffffff?text=D"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">James L.</div>
                                                <div class="text-xs">@jamesl</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Perfect for my fitness
                                            journey. Thank you!</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 4 -->
                            <div class="marquee-col h-full overflow-y-hidden hidden lg:block">
                                <div class="marquee-track px-2 py-4">
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/0ea5e9/ffffff?text=K"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Ali R.</div>
                                                <div class="text-xs">@alir</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Perfect for busy people
                                            like me!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/84cc16/ffffff?text=L"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Maya T.</div>
                                                <div class="text-xs">@mayat</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Love the clean design and
                                            accuracy.</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/0ea5e9/ffffff?text=K"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Ali R.</div>
                                                <div class="text-xs">@alir</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Perfect for busy people
                                            like me!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/84cc16/ffffff?text=L"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Maya T.</div>
                                                <div class="text-xs">@mayat</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Love the clean design and
                                            accuracy.</p>
                                    </div>
                                    <!-- Duplikat -->
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/60a5fa/ffffff?text=E"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Emma W.</div>
                                                <div class="text-xs">@emmaw</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">So intuitive and beautiful
                                            UI. Love it!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/fbbf24/ffffff?text=F"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">David H.</div>
                                                <div class="text-xs">@davidh</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Helped me lose 10kg in 3
                                            months. Amazing!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/ec4899/ffffff?text=G"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Anna P.</div>
                                                <div class="text-xs">@annap</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">The calorie tracker is spot
                                            on. Great job!</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-lg shadow-md card hover:border-[#0F9E99]">
                                        <div class="flex items-center gap-3 mb-2">
                                            <img src="https://placehold.co/40x40/8b5cf6/ffffff?text=H"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <div class="font-medium">Tom B.</div>
                                                <div class="text-xs">@tomb</div>
                                            </div>
                                        </div>
                                        <p class="text-sm">Worth every penny. Best
                                            health app ever.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="my-24 sm:py-24">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 items-start">
                <div class="space-y-4">
                    <a href="mailto:hi@nutritrack.com"
                        class="text-lg hover:underline block">hi@nutritrack.com</a>
                    <div class="flex space-x-4">
                        <a href="#" class="">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </a>
                        <a href="#" class="">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                            </svg>
                        </a>
                        <a href="#" class="">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium">Product</h4>
                    <ul class="mt-4 space-y-4 text-sm">
                        <li><a href="#" class="opacity-80">Home</a>
                        </li>
                        <li><a href="#" class="opacity-80">Features</a>
                        </li>
                        <li><a href="#" class="opacity-80">Download</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-medium">Company</h4>
                    <ul class="mt-4 space-y-4 text-sm">
                        <li><a href="#" class="opacity-80">4Ever
                                Young</a></li>
                        <li><a href="#" class="opacity-80">Community</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-medium">What Our Users Say</h4>
                    <ul class="mt-4 space-y-4 text-sm">
                        <li><a href="#" class="opacity-80">Riviews</a>
                        </li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <div class="relative inline-block text-left w-full">
                        <div>
                            <button id="dropdownButton" type="button"
                                class="inline-flex justify-start w-full rounded-md card shadow-sm px-4 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#000000]"
                                aria-expanded="true" aria-haspopup="true">
                                Language
                                <svg class="-mr-1 ml-auto h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <div id="dropdownMenu"
                            class="hidden absolute right-0 mt-2 w-44 rounded-md shadow-lg ring-1 ring-black/10 focus:outline-none fade-in"
                            role="menu" aria-orientation="vertical" aria-labelledby="dropdownButton">
                            <div class="py-1" role="none">
                                <a href="#" class="block px-4 py-2 text-sm" role="menuitem">English</a>
                                <a href="#" class="block px-4 py-2 text-sm" role="menuitem">Bahasa Indonesia</a>
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-2">
                        <div id="theme-switcher" class="flex p-1 rounded-full card shadow-sm">
                            <button id="system-btn"
                                class="flex items-center justify-center p-2 rounded-full transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                                </svg>
                            </button>

                            <button id="light-btn"
                                class="flex items-center justify-center p-2 rounded-full transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                                </svg>
                            </button>

                            <button id="dark-btn"
                                class="flex items-center justify-center p-2 rounded-full transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-20 pt-20 dark:text-gray-400 text-md">
                <p>© 2025 Made By 4Ever Young</p>
            </div>
        </div>
    </footer>

    <script>
        // === Mobile Menu Logic ===
        const menuToggleBtn = document.getElementById('menu-toggle-btn');
        const menuIconPath = document.querySelector('#menu-icon path');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuPanel = mobileMenu ? mobileMenu.querySelector('.mobile-menu-panel') : null;

        if (menuToggleBtn && menuIconPath && mobileMenu && mobileMenuPanel) {
            const MOBILE_MENU_ICONS = {
                open: 'M4 6h16M4 12h16m-7 6h7',
                close: 'M6 18L18 6M6 6l12 12'
            };

            const setMenuIcon = (state) => {
                menuIconPath.setAttribute('d', state === 'open' ? MOBILE_MENU_ICONS.close : MOBILE_MENU_ICONS.open);
            };

            const openMobileMenu = () => {
                mobileMenu.classList.remove('hidden');
                mobileMenuPanel.classList.remove('animate-close');
                mobileMenuPanel.classList.remove('animate-open');
                void mobileMenuPanel.offsetWidth;
                mobileMenuPanel.classList.add('animate-open');
                menuToggleBtn.setAttribute('aria-expanded', 'true');
                setMenuIcon('open');
                document.body.style.overflow = 'hidden';
            };

            const closeMobileMenu = ({
                focusToggle = false
            } = {}) => {
                mobileMenuPanel.classList.remove('animate-open');
                mobileMenuPanel.classList.add('animate-close');
                menuToggleBtn.setAttribute('aria-expanded', 'false');
                setMenuIcon('close');
                document.body.style.overflow = '';
                if (focusToggle) {
                    menuToggleBtn.focus();
                }
            };

            mobileMenuPanel.addEventListener('animationend', (event) => {
                if (event.animationName === 'mobileMenuOut') {
                    mobileMenu.classList.add('hidden');
                    mobileMenuPanel.classList.remove('animate-close');
                }
            });

            menuToggleBtn.addEventListener('click', () => {
                const isExpanded = menuToggleBtn.getAttribute('aria-expanded') === 'true';
                if (isExpanded) {
                    closeMobileMenu();
                } else {
                    openMobileMenu();
                }
            });

            mobileMenu.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => closeMobileMenu());
            });

            document.addEventListener('click', (event) => {
                const isClickInsideMenu = mobileMenu.contains(event.target) || menuToggleBtn.contains(event.target);
                if (!isClickInsideMenu && menuToggleBtn.getAttribute('aria-expanded') === 'true') {
                    closeMobileMenu();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && menuToggleBtn.getAttribute('aria-expanded') === 'true') {
                    closeMobileMenu({
                        focusToggle: true
                    });
                }
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768 && menuToggleBtn.getAttribute('aria-expanded') === 'true') {
                    closeMobileMenu();
                    mobileMenu.classList.add('hidden');
                    mobileMenuPanel.classList.remove('animate-close');
                }
            });
        }

        // === Dropdown Menu Logic ===
        const dropdownButton = document.getElementById('dropdownButton');
        const dropdownMenu = document.getElementById('dropdownMenu');

        dropdownButton.addEventListener('click', () => {
            const expanded = dropdownButton.getAttribute('aria-expanded') === 'true' || false;
            dropdownButton.setAttribute('aria-expanded', !expanded);
            dropdownMenu.classList.toggle('hidden');
        });

        window.addEventListener('click', (event) => {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
                dropdownButton.setAttribute('aria-expanded', 'false');
            }
        });

        // === Theme Switcher Logic ===
        const body = document.body;
        const systemBtn = document.getElementById('system-btn');
        const lightBtn = document.getElementById('light-btn');
        const darkBtn = document.getElementById('dark-btn');
        const buttons = [systemBtn, lightBtn, darkBtn];

        const getActiveTheme = () => {
            if (localStorage.theme === 'dark') return 'dark';
            if (localStorage.theme === 'light') return 'light';
            return 'system';
        };

        const applyTheme = (theme) => {
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            } else if (theme === 'light') {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                localStorage.removeItem('theme');
            }
            updateButtonStyles(theme);
        };

        const updateButtonStyles = (activeTheme) => {
            buttons.forEach(btn => {
                btn.classList.remove('btn-active', 'btn-inactive');
                if (btn.id.includes(activeTheme)) {
                    btn.classList.add('btn-active');
                } else {
                    btn.classList.add('btn-inactive');
                }
            });
        };

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (!('theme' in localStorage)) {
                applyTheme('system');
            }
        });

        systemBtn.addEventListener('click', () => applyTheme('system'));
        lightBtn.addEventListener('click', () => applyTheme('light'));
        darkBtn.addEventListener('click', () => applyTheme('dark'));

        // Initialize theme on page load
        const initialTheme = getActiveTheme();
        applyTheme(initialTheme);

        // === Sticky Header Logic ===
        const header = document.getElementById('sticky-header');
        const scrollThreshold = 50;

        window.addEventListener('scroll', () => {
            if (window.scrollY > scrollThreshold) {
                header.classList.add('bg-light-bg', 'dark:bg-dark-bg', 'shadow-lg', 'backdrop-blur-sm', 'bg-opacity-80', 'py-4');
                header.classList.remove('py-6');
            } else {
                header.classList.remove('bg-light-bg', 'dark:bg-dark-bg', 'shadow-lg', 'backdrop-blur-sm', 'bg-opacity-80', 'py-4');
                header.classList.add('py-6');
            }
        });
    </script>

</body>

</html>