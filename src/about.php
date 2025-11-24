<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriTrack - About</title>
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
        <!-- Hero / Intro -->
        <section class="relative w-full min-h-screen flex items-center overflow-hidden px-[5%] py-20">

            <div class="absolute inset-0 
                bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] 
                bg-[size:24px_24px] 
                [mask-image:linear-gradient(to_bottom,white_10%,transparent_90%)]">
            </div>
            <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="relative z-10 max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center">

                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full card text-sm text-primary mb-6">
                        <i class="fas fa-leaf"></i>
                        <span>Our Philosophy</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                        Bridging <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#3dccc7] to-green-400">Technology</span> <br> with Biology.
                    </h1>

                    <p class="text-lg opacity-70 mb-8 leading-relaxed">
                        Kami adalah tim nutrisionis dan engineer yang percaya bahwa kesehatan tidak harus rumit. Misi kami adalah mendemokratisasi akses ke gizi personal melalui kecerdasan buatan.
                    </p>

                    <div class="flex items-center gap-8 border-t border-white/10 pt-8">
                        <div>
                            <h4 class="text-3xl font-bold">3+</h4>
                            <p class="text-sm opacity-60">Years Journey</p>
                        </div>
                        <div class="w-px h-10 border-r border-white/10"></div>
                        <div>
                            <h4 class="text-3xl font-bold">50+</h4>
                            <p class="text-sm opacity-60">Team Members</p>
                        </div>
                        <div class="w-px h-10 border-l border-white/10"></div>
                        <div>
                            <h4 class="text-3xl font-bold">1M+</h4>
                            <p class="text-sm opacity-60">Meals Tracked</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 relative">
                    <div class="absolute -inset-4 bg-gradient-to-tr from-primary/20 to-transparent rounded-full blur-2xl -z-10"></div>

                    <div class="space-y-4 mt-8">
                        <div class="h-40 bg-gray-800 rounded-2xl overflow-hidden border border-white/10 shadow-lg transform hover:scale-105 transition duration-500">
                            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition">
                        </div>
                        <div class="h-56 bg-gray-800 rounded-2xl overflow-hidden border border-white/10 shadow-lg transform hover:scale-105 transition duration-500">
                            <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="h-56 bg-gray-800 rounded-2xl overflow-hidden border border-white/10 shadow-lg transform hover:scale-105 transition duration-500">
                            <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition">
                        </div>
                        <div class="h-40 bg-gray-800 rounded-2xl overflow-hidden border border-white/10 shadow-lg transform hover:scale-105 transition duration-500">
                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition">
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- About Us Section -->
        <section id="values" class="py-24 px-[5%] relative">
            <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-[#3dccc7]/5 rounded-full blur-[100px] pointer-events-none"></div>

            <div class="relative z-10 max-w-7xl mx-auto">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-[#3dccc7] font-bold tracking-wide uppercase text-sm mb-3">Who We Are</h2>
                    <h1 class="text-3xl md:text-5xl font-bold mb-6">
                        We don't just track calories. <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#3dccc7] to-green-400">We decode lifestyle.</span>
                    </h1>
                    <p class="opacity-80 text-lg">
                        Menggabungkan ilmu gizi klinis dengan kecerdasan buatan untuk membantu Anda hidup lebih lama dan lebih baik.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div class="md:col-span-2 card rounded-3xl p-8 hover:border-[#3dccc7]/30 shadow-sm hover:shadow-lg transition duration-300 group">
                        <div class="w-12 h-12 bg-[#3dccc7]/10 dark:bg-[#3dccc7]/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                            <i class="fas fa-bullseye text-[#3dccc7] text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Our Mission</h3>
                        <p class="opacity-80 leading-relaxed">
                            Misi kami adalah mendemokratisasi akses ke ahli gizi pribadi. Kami percaya bahwa setiap orang berhak mendapatkan panduan kesehatan yang akurat, terjangkau, dan dipersonalisasi—bukan sekadar saran umum dari internet.
                        </p>
                    </div>

                    <div class="card rounded-3xl p-8 hover:border-[#3dccc7]/30 shadow-sm hover:shadow-lg transition duration-300 group">
                        <div class="w-12 h-12 bg-purple-500/10 dark:bg-purple-500/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                            <i class="fas fa-flask text-purple-500 dark:text-purple-400 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-4">Backed by Science</h3>
                        <p class="opacity-80 text-sm">
                            Setiap algoritma di NutriTrack divalidasi oleh jurnal medis terkemuka dan tim nutrisionis bersertifikat.
                        </p>
                    </div>

                    <div class="card rounded-3xl p-8 hover:border-[#3dccc7]/30 shadow-sm hover:shadow-lg transition duration-300 group">
                        <div class="w-12 h-12 bg-green-500/10 dark:bg-green-500/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                            <i class="fas fa-shield-alt text-green-500 dark:text-green-400 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-4">Privacy First</h3>
                        <p class="opacity-80 text-sm">
                            Data kesehatan Anda adalah milik Anda. Kami menggunakan enkripsi end-to-end dan tidak pernah menjual data ke pihak ketiga.
                        </p>
                    </div>

                    <div class="md:col-span-2 relative overflow-hidden bg-gradient card rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between gap-6 group cursor-pointer">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-[#3dccc7]/10 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/2 group-hover:bg-[#3dccc7]/20 transition duration-500"></div>

                        <div class="relative z-10 text-center md:text-left">
                            <h3 class="text-2xl font-bold mb-2">Built by Dreamers</h3>
                            <p class="opacity-80 text-sm md:text-base max-w-md">
                                Kenalan dengan tim "4Ever Young" dibalik baris kode NutriTrack. Lihat bagaimana kami membangun ini dari nol.
                            </p>
                        </div>

                        <a href="4ever-young.php" class="relative z-10 px-6 py-3 bg-white text-black font-bold rounded-full hover:bg-[#3dccc7] hover:text-white transition duration-300 flex items-center gap-2 shadow-lg">
                            Meet the Creators
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                </div>
            </div>
        </section>
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
    </script>
</body>

</html>