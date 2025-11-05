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
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
</head>

<body>

    <!-- Header -->
    <header id="sticky-header" class="fixed z-50 w-full transition-all duration-300 ease-in-out py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex mx-auto justify-between items-center px-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold">Logo</h1>
                </div>
                <ul class="hidden md:flex items-center space-x-8">
                    <li><a href="index.php" class="transition duration-200 transform text-hover-light">Home</a>
                    </li>
                    <li><a href="about.php" class="transition duration-200 transform hover:scale-105">About
                            Us</a></li>
                    <li><a href="features.php"
                            class="text-gray-600 hover:text-gray-950 dark:text-gray-300 dark:hover:text-white transition duration-200 transform hover:scale-105">Features</a>
                    </li>
                    <li><a href="riviews.php" class="transition duration-200 transform hover:scale-105">Riviews</a>
                    </li>
                    <li><a href="#" class="transition duration-200 transform hover:scale-105">Download</a>
                    </li>
                </ul>
                <div class="hidden md:flex items-center space-x-3">
                    <a href="signin.php"
                        class="dark:text-dark-text whitespace-nowrap transition duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none w-full">
                        Sign In
                    </a>
                    <a href="signup.php"
                        class="inline-flex justify-center gap-2 text-white dark:bg-[#0a0a0a] dark:hover:bg-[#525252] dark:dark:bg-[#34373b] px-4 py-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 w-full">
                        Sign Up
                    </a>
                </div>
                <div class="md:hidden">
                    <button class="text-gray-800 dark:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main -->
    <main>
        <!-- Hero / Intro -->
        <section class="min-h-screen relative overflow-hidden flex items-center shadow-sm">
            <div class="absolute inset-0 opacity-60"></div>
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="max-w-3xl">
                    <h1 class="text-4xl md:text-5xl font-bold tracking-tight">Powerful Features</h1>
                    <p class="mt-4 text-lg opacity-80">Everything you need to track, understand, and improve your diet.
                    </p>
                    <div class="mt-8 flex gap-3">
                        <a href="#features" class="px-5 py-3 rounded-md text-sm font-medium text-white dark:hover:bg-[#08D2CB] dark:dark:bg-[#07bab4]">Explore Features</a>
                        <a href="#how" class="px-5 py-3 rounded-md text-sm font-medium card">How It Works</a>
                    </div>
                </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Features Section -->
            <section id="features" class="py-16 sm:py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl sm:text-4xl font-bold">
                            Features
                        </h2>
                        <p class="mt-4 max-w-2xl mx-auto text-lg opacity-80">
                            Explore the core capabilities of NutriTrack.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="card p-6 h-[280px] rounded-xl shadow-sm card hover:border-[#0F9E99]">
                            <h3 class="text-xl font-semibold mb-2">Tracking</h3>
                            <p class="opacity-80">Log meals, water, and activity with ease.</p>
                        </div>
                        <div class="card p-6 h-[280px] rounded-xl shadow-sm card hover:border-[#0F9E99]">
                            <h3 class="text-xl font-semibold mb-2">Analytics</h3>
                            <p class="opacity-80">Understand trends and reach your goals.</p>
                        </div>
                        <div class="card p-6 h-[280px] rounded-xl shadow-sm card hover:border-[#0F9E99]">
                            <h3 class="text-xl font-semibold mb-2">Personalization</h3>
                            <p class="opacity-80">Get insights tailored to your needs.</p>
                        </div>
                        <div class="card p-6 h-[280px] rounded-xl shadow-sm card hover:border-[#0F9E99]">
                            <h3 class="text-xl font-semibold mb-2">Recipes</h3>
                            <p class="opacity-80">Discover healthy meals to try.</p>
                        </div>
                        <div class="card p-6 h-[280px] rounded-xl shadow-sm card hover:border-[#0F9E99]">
                            <h3 class="text-xl font-semibold mb-2">Reminders</h3>
                            <p class="opacity-80">Stay on track with smart nudges.</p>
                        </div>
                        <div class="card p-6 h-[280px] rounded-xl shadow-sm card hover:border-[#0F9E99]">
                            <h3 class="text-xl font-semibold mb-2">Sync</h3>
                            <p class="opacity-80">Integrate with wearables and apps.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works -->
            <section id="how" class="py-16 sm:py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h3 class="text-2xl sm:text-3xl font-bold">How It Works</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="card p-6 h-[530px] rounded-xl shadow-sm hover:border-[#0F9E99]">
                            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">Step 1</div>
                            <h4 class="mt-1 font-semibold">Log Your Meals</h4>
                            <p class="mt-2 opacity-80">Log your meals, water, and activity with ease.</p>
                        </div>
                        <div class="card p-6 h-[530px] rounded-xl shadow-sm hover:border-[#0F9E99]">
                            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">Step 2</div>
                            <h4 class="mt-1 font-semibold">See Insights</h4>
                            <p class="mt-2 opacity-80">Get insights and reach your goals.</p>
                        </div>
                        <div class="card p-6 h-[530px] rounded-xl shadow-sm hover:border-[#0F9E99]">
                            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">Step 3</div>
                            <h4 class="mt-1 font-semibold">Reach Goals</h4>
                            <p class="mt-2 opacity-80">Follow simple recommendations for consistency.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ -->
            <section class="py-16 sm:py-24">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h3 class="text-2xl sm:text-3xl font-bold text-center">FAQs</h3>
                    <div class="mt-8 space-y-4">
                        <div class="card p-5 rounded-xl shadow-sm hover:border-[#0F9E99]">
                            <h4 class="font-semibold">Is NutriTrack free?</h4>
                            <p class="mt-2 opacity-80">There is a free version with core features. The pro version adds advanced insights.</p>
                        </div>
                        <div class="card p-5 rounded-xl shadow-sm hover:border-[#0F9E99]">
                            <h4 class="font-semibold">Is my data safe?</h4>
                            <p class="mt-2 opacity-80">We prioritize privacy and do not sell your data.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="sm:py-24">
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
                        <li><a href="#" class=" opacity-80">Home</a>
                        </li>
                        <li><a href="#" class=" opacity-80">Features</a>
                        </li>
                        <li><a href="#" class=" opacity-80">Download</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-medium">Company</h4>
                    <ul class="mt-4 space-y-4 text-sm">
                        <li><a href="#" class=" opacity-80">4Ever
                                Young</a></li>
                        <li><a href="#" class=" opacity-80">Community</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-medium text-light-text dark:text-dark-text">What Our Users Say</h4>
                    <ul class="mt-4 space-y-4 text-sm">
                        <li><a href="#" class="text-light-text dark:text-dark-text opacity-80">Riviews</a>
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