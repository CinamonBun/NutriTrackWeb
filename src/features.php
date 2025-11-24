<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriTrack - Features</title>
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

        .hero-food-image {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(50%, -50%) translateY(0px);
            }

            50% {
                transform: translate(50%, -50%) translateY(-20px);
            }
        }

        .feature-icon {
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
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
        <section class="w-full min-h-screen flex items-center relative overflow-hidden">

            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[100px] translate-x-1/3 -translate-y-1/4 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-secondary/5 rounded-full blur-[100px] -translate-x-1/3 translate-y-1/4 pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-6 relative z-10 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                    <div class="max-w-2xl">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight mb-6 leading-tight">
                            Powerful Features for Your <span class="text-primary bg-clip-text text-transparent bg-gradient-to-r from-primary to-primary/60">Health Journey</span>
                        </h1>
                        <p class="mt-4 text-lg md:text-xl opacity-80 leading-relaxed mb-8">
                            Everything you need to track, understand, and improve your diet. Discover tools that make healthy living effortless and enjoyable.
                        </p>

                        <div class="flex flex-wrap gap-4">
                            <a href="#features" class="inline-flex items-center justify-center gap-2 bg-primary text-white px-8 py-3.5 rounded-full font-semibold transition-all duration-300 hover:bg-primary/90 hover:-translate-y-1 hover:shadow-[0_10px_20px_rgba(61,204,199,0.3)]">
                                Explore Features
                            </a>
                            <a href="#how" class="inline-flex items-center justify-center gap-2 card opacity-80 px-6 py-3 rounded-full font-semibold backdrop-blur-sm transition duration-300 hover:bg-white/5">
                                How It Works
                            </a>
                        </div>
                    </div>

                    <div class="relative flex justify-center lg:justify-end group perspective-1000">

                        <div class="absolute inset-0 bg-primary/20 blur-[60px] rounded-full scale-75 z-0 animate-pulse"></div>

                        <div class="relative z-10 w-72 h-72 md:w-96 md:h-96 lg:w-[450px] lg:h-[450px] transition-transform duration-500 hover:scale-105">
                            <img
                                src="assets/img/food.png"
                                alt="Healthy Food Bowl"
                                class="w-full h-full object-cover rounded-full shadow-2xl relative z-10">

                            <div class="absolute top-10 -left-4 md:top-16 md:-left-10 z-20 backdrop-blur-md card p-3 pr-5 rounded-2xl shadow-xl flex items-center gap-3 animate-[bounce_3s_infinite]">
                                <div class="w-10 h-10 bg-orange-500/20 rounded-full flex items-center justify-center text-orange-500">
                                    <i class="fas fa-fire"></i>
                                </div>
                                <div>
                                    <p class="text-xs opacity-70 uppercase tracking-wider font-semibold">Calories</p>
                                    <p class="text-sm font-bold">320 Kcal</p>
                                </div>
                            </div>

                            <div class="absolute bottom-10 -right-4 md:bottom-16 md:-right-8 z-20 backdrop-blur-md card p-3 pr-5 rounded-2xl shadow-xl flex items-center gap-3 animate-[bounce_3s_infinite] delay-700">
                                <div class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center text-primary">
                                    <i class="fas fa-drumstick-bite"></i>
                                </div>
                                <div>
                                    <p class="text-xs opacity-70 uppercase tracking-wider font-semibold">Protein</p>
                                    <p class="text-sm font-bold">24g High</p>
                                </div>
                            </div>

                            <div class="absolute top-0 right-10 z-20 bg-green-500 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-check text-xs"></i>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Features Section -->
            <section id="features" class="py-16 sm:py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">
                            Fitur Unggulan NutriTrack
                        </h2>
                        <p class="mt-4 max-w-2xl mx-auto text-lg opacity-80">
                            Jelajahi kemampuan inti NutriTrack yang dirancang untuk membantu mencapai target kesehatan Anda dengan mudah dan efektif.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="feature-card card p-8 rounded-xl shadow-sm hover:border-[#0F9E99] transition-all duration-300 hover:shadow-lg flex flex-col h-full">
                            <div class="feature-icon w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mb-6">
                                <i class="fas fa-utensils text-primary text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Smart Food Tracking</h3>
                            <p class="opacity-80 mb-4">Catat makanan, minuman, dan aktivitas dengan mudah. Database lengkap dengan ribuan makanan lokal dan internasional.</p>

                            <ul class="space-y-2 text-sm opacity-70 mt-auto">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-primary"></i>
                                    <span>Barcode scanner</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-primary"></i>
                                    <span>Quick add favorites</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-primary"></i>
                                    <span>Meal photos</span>
                                </li>
                            </ul>
                        </div>

                        <div class="feature-card card p-8 rounded-xl shadow-sm hover:border-[#0F9E99] transition-all duration-300 hover:shadow-lg flex flex-col h-full">
                            <div class="feature-icon w-16 h-16 bg-secondary/10 rounded-2xl flex items-center justify-center mb-6">
                                <i class="fas fa-chart-line text-secondary text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Advanced Analytics</h3>
                            <p class="opacity-80 mb-4">Pahami tren nutrisi dan capai tujuan dengan visualisasi data yang komprehensif dan mudah dipahami.</p>

                            <ul class="space-y-2 text-sm opacity-70 mt-auto">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-secondary"></i>
                                    <span>Weekly/Monthly reports</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-secondary"></i>
                                    <span>Macro breakdown</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-secondary"></i>
                                    <span>Progress charts</span>
                                </li>
                            </ul>
                        </div>

                        <div class="feature-card card p-8 rounded-xl shadow-sm hover:border-[#0F9E99] transition-all duration-300 hover:shadow-lg flex flex-col h-full">
                            <div class="feature-icon w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center mb-6">
                                <i class="fas fa-user-cog text-accent text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">AI Personalization</h3>
                            <p class="opacity-80 mb-4">Dapatkan insight dan rekomendasi yang disesuaikan dengan kebutuhan, preferensi, dan tujuan kesehatan pribadi Anda.</p>

                            <ul class="space-y-2 text-sm opacity-70 mt-auto">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-accent"></i>
                                    <span>Custom meal plans</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-accent"></i>
                                    <span>Goal recommendations</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-accent"></i>
                                    <span>Dietary preferences</span>
                                </li>
                            </ul>
                        </div>

                        <div class="feature-card card p-8 rounded-xl shadow-sm hover:border-[#0F9E99] transition-all duration-300 hover:shadow-lg flex flex-col h-full">
                            <div class="feature-icon w-16 h-16 bg-[#FFC107]/10 rounded-2xl flex items-center justify-center mb-6">
                                <i class="fas fa-book-open text-[#FFC107] text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Healthy Recipes</h3>
                            <p class="opacity-80 mb-4">Temukan ribuan resep sehat dengan nutrisi yang sudah dihitung. Filter berdasarkan kalori, waktu masak, dan preferensi diet.</p>

                            <ul class="space-y-2 text-sm opacity-70 mt-auto">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-[#FFC107]"></i>
                                    <span>5000+ recipes</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-[#FFC107]"></i>
                                    <span>Step-by-step guides</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-[#FFC107]"></i>
                                    <span>Save favorites</span>
                                </li>
                            </ul>
                        </div>

                        <div class="feature-card card p-8 rounded-xl shadow-sm hover:border-[#0F9E99] transition-all duration-300 hover:shadow-lg flex flex-col h-full">
                            <div class="feature-icon w-16 h-16 bg-[#E91E63]/10 rounded-2xl flex items-center justify-center mb-6">
                                <i class="fas fa-bell text-[#E91E63] text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Smart Reminders</h3>
                            <p class="opacity-80 mb-4">Tetap on-track dengan pengingat cerdas untuk makan, minum air, dan olahraga yang disesuaikan dengan rutinitas harian Anda.</p>

                            <ul class="space-y-2 text-sm opacity-70 mt-auto">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-[#E91E63]"></i>
                                    <span>Meal reminders</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-[#E91E63]"></i>
                                    <span>Water intake alerts</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-[#E91E63]"></i>
                                    <span>Custom schedules</span>
                                </li>
                            </ul>
                        </div>

                        <div class="feature-card card p-8 rounded-xl shadow-sm hover:border-[#0F9E99] transition-all duration-300 hover:shadow-lg flex flex-col h-full">
                            <div class="feature-icon w-16 h-16 bg-[#9C27B0]/10 rounded-2xl flex items-center justify-center mb-6">
                                <i class="fas fa-sync-alt text-[#9C27B0] text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Device Sync</h3>
                            <p class="opacity-80 mb-4">Integrasikan dengan wearables dan aplikasi kesehatan favorit Anda untuk tracking yang lebih akurat dan komprehensif.</p>

                            <ul class="space-y-2 text-sm opacity-70 mt-auto">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-[#9C27B0]"></i>
                                    <span>Fitness trackers</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-[#9C27B0]"></i>
                                    <span>Health apps</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-[#9C27B0]"></i>
                                    <span>Cloud backup</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works -->
            <section id="how" class="py-16 sm:py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h3 class="text-3xl sm:text-4xl font-bold mb-4">Bagaimana Cara Kerjanya?</h3>
                        <p class="mt-3 text-lg opacity-80 max-w-2xl mx-auto">
                            Mulai perjalanan kesehatan Anda hanya dalam tiga langkah sederhana
                        </p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="card p-8 rounded-xl shadow-sm hover:border-[#0F9E99] transition-all duration-300 flex flex-col h-full">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-xl font-bold text-primary">1</span>
                                </div>
                                <h4 class="text-xl font-semibold">Set Your Goals</h4>
                            </div>
                            <div class="mb-6">
                                <p class="opacity-80 mb-4">
                                    Tentukan target kesehatan Anda - apakah ingin menurunkan berat badan, menambah massa otot, atau sekadar menjaga pola makan sehat.
                                </p>
                            </div>
                            <div class="card rounded-lg p-4 bg-gradient-to-br from-primary/5 to-transparent mt-auto">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="opacity-70">Target Weight</span>
                                        <span class="font-semibold text-primary">70 kg</span>
                                    </div>
                                    <div class="h-2 bg-primary/10 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary w-3/4 rounded-full"></div>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="opacity-70">Daily Calories</span>
                                        <span class="font-semibold text-primary">2000 kcal</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card p-8 rounded-xl shadow-sm hover:border-[#0F9E99] transition-all duration-300 flex flex-col h-full">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-secondary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-xl font-bold text-secondary">2</span>
                                </div>
                                <h4 class="text-xl font-semibold">Track Daily Intake</h4>
                            </div>
                            <div class="mb-6">
                                <p class="opacity-80 mb-4">
                                    Catat semua makanan dan minuman yang Anda konsumsi. Gunakan barcode scanner atau foto makanan untuk tracking yang lebih cepat dan mudah.
                                </p>
                            </div>
                            <div class="card rounded-lg p-4 bg-gradient-to-br from-secondary/5 to-transparent mt-auto">
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-secondary/20 rounded-lg"></div>
                                        <div class="flex-1">
                                            <div class="h-2 bg-secondary/30 rounded w-3/4 mb-1"></div>
                                            <div class="h-2 bg-secondary/20 rounded w-1/2"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-secondary">450 kcal</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-secondary/20 rounded-lg"></div>
                                        <div class="flex-1">
                                            <div class="h-2 bg-secondary/30 rounded w-3/4 mb-1"></div>
                                            <div class="h-2 bg-secondary/20 rounded w-1/2"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-secondary">320 kcal</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card p-8 rounded-xl shadow-sm hover:border-[#0F9E99] transition-all duration-300 flex flex-col h-full">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-accent/10 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-xl font-bold text-accent">3</span>
                                </div>
                                <h4 class="text-xl font-semibold">Achieve Your Goals</h4>
                            </div>
                            <div class="mb-6">
                                <p class="opacity-80 mb-4">
                                    Lihat progress Anda, dapatkan insight personal, dan ikuti rekomendasi untuk konsistensi. Rayakan setiap milestone yang dicapai!
                                </p>
                            </div>
                            <div class="card rounded-lg p-4 bg-gradient-to-br from-accent/5 to-transparent mt-auto">
                                <div class="text-center mb-3">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-accent/20 rounded-full mb-2">
                                        <i class="fas fa-trophy text-accent text-2xl"></i>
                                    </div>
                                    <div class="font-bold text-xl mb-1">Week 4</div>
                                    <div class="text-sm opacity-70">Progress Milestone</div>
                                </div>
                                <div class="flex justify-around text-center text-sm">
                                    <div>
                                        <div class="font-semibold text-accent">-3 kg</div>
                                        <div class="opacity-70 text-xs">Weight</div>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-accent">92%</div>
                                        <div class="opacity-70 text-xs">Consistency</div>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-accent">28</div>
                                        <div class="opacity-70 text-xs">Days</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ -->
            <section class="py-16 sm:py-24">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h3 class="text-3xl sm:text-4xl font-bold text-center mb-4">Frequently Asked Questions</h3>
                    <p class="text-center opacity-80 mb-12 max-w-2xl mx-auto">
                        Punya pertanyaan? Kami punya jawabannya! Temukan informasi yang Anda butuhkan di sini.
                    </p>
                    <div class="space-y-4">
                        <div class="card p-6 rounded-xl shadow-sm hover:border-[#0F9E99] transition-all duration-300">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-question text-primary text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-lg mb-2">Apakah NutriTrack gratis?</h4>
                                    <p class="opacity-80">
                                        Ya! NutriTrack menawarkan versi gratis dengan fitur-fitur inti yang lengkap. Untuk fitur advanced seperti analisis mendalam, meal planning personalized, dan sinkronisasi dengan lebih banyak device, tersedia versi Pro dengan harga terjangkau.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card p-6 rounded-xl shadow-sm hover:border-[#0F9E99] transition-all duration-300">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 bg-secondary/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-question text-secondary text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-lg mb-2">Apakah data saya aman?</h4>
                                    <p class="opacity-80">
                                        Keamanan dan privasi Anda adalah prioritas kami. Semua data kesehatan Anda dienkripsi dengan standar tinggi dan kami tidak pernah menjual data pengguna kepada pihak ketiga. Data Anda hanya milik Anda.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card p-6 rounded-xl shadow-sm hover:border-[#0F9E99] transition-all duration-300">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 bg-accent/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-question text-accent text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-lg mb-2">Apakah tersedia database makanan Indonesia?</h4>
                                    <p class="opacity-80">
                                        Tentu saja! NutriTrack memiliki database lengkap makanan lokal Indonesia, mulai dari nasi goreng, rendang, gado-gado, hingga jajanan pasar. Kami terus menambah dan update database untuk memastikan akurasi informasi nutrisi.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card p-6 rounded-xl shadow-sm hover:border-[#0F9E99] transition-all duration-300">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 bg-[#FFC107]/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-question text-[#FFC107] text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-lg mb-2">Bisakah saya menggunakan NutriTrack offline?</h4>
                                    <p class="opacity-80">
                                        Ya, sebagian besar fitur NutriTrack dapat digunakan secara offline. Data akan otomatis tersinkronisasi saat Anda terhubung kembali dengan internet. Ini sangat berguna saat Anda bepergian atau berada di area dengan koneksi terbatas.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card p-6 rounded-xl shadow-sm hover:border-[#0F9E99] transition-all duration-300">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 bg-[#E91E63]/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-question text-[#E91E63] text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-lg mb-2">Apakah cocok untuk semua jenis diet?</h4>
                                    <p class="opacity-80">
                                        Absolutely! NutriTrack mendukung berbagai jenis diet seperti keto, vegetarian, vegan, paleo, Mediterranean, dan lainnya. Anda bisa customize preferensi diet dan mendapatkan rekomendasi yang sesuai dengan gaya hidup Anda.
                                    </p>
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
                        <li><a href="index.php" class="opacity-80">Home</a>
                        </li>
                        <li><a href="features.php" class="opacity-80">Features</a>
                        </li>
                        <li><a href="#" class="opacity-80">Download</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-medium">Company</h4>
                    <ul class="mt-4 space-y-4 text-sm">
                        <li><a href="4ever-young.php" class="opacity-80">4Ever
                                Young</a></li>
                        <li><a href="#" class="opacity-80">Community</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-medium">What Our Users Say</h4>
                    <ul class="mt-4 space-y-4 text-sm">
                        <li><a href="riviews.php" class="opacity-80">Riviews</a>
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
                            class="hidden origin-top-right absolute right-0 mt-2 w-auto rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none fade-in"
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

        // === Smooth Scroll ===
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>