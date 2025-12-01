<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit;
}

$username = $_SESSION['username'];
// Pastikan Anda sudah menyertakan file koneksi database
include 'config.php';
// WAJIB: Sertakan file fungsi database
include 'db-functions.php'; 

// Check if user is admin (Asumsi fungsi ini sudah didefinisikan)
requireAdmin($username);

// 1. Get User Data
$user = getUserByUsername($username); // (Asumsi ini mengambil id, fullname, dll. dari tabel users)
$id_user = $user['id'] ?? 0;
$fullname = $user['fullname'] ?? $username;

// Calculate statistics for today
$today = date('Y-m-d');

$totalCalories = 0;
$foodsCount = 0;
$mealsCount = 0;

if ($id_user > 0) {
    // 2. Query untuk mendapatkan semua log diary hari ini (meal dan food)
    
    $safe_id_user = escape($id_user);
    $safe_today = escape($today);

    // Ambil log diary, lakukan JOIN ke meals dan foods untuk mendapatkan kalori
    $sql_today_logs = "
        SELECT 
            d.id_meals,
            d.id_foods,
            m.calories AS meal_calories, 
            f.calories_per_unit AS food_calories
        FROM 
            diary d
        LEFT JOIN 
            meals m ON d.id_meals = m.id
        LEFT JOIN 
            foods f ON d.id_foods = f.id
        WHERE 
            d.id_user = '$safe_id_user' 
            AND d.date = '$safe_today'
    ";

    $result_today_logs = dbQuery($sql_today_logs);
    
    if ($result_today_logs) {
        while ($log = mysqli_fetch_assoc($result_today_logs)) {
            // Log Makanan Jadi (Meal)
            // Cek apakah id_meals tidak kosong dan kalori berhasil diambil dari tabel meals
            if (!empty($log['id_meals']) && $log['meal_calories'] !== null) {
                $totalCalories += floatval($log['meal_calories']);
                $mealsCount++;
            }
            
            // Log Bahan Makanan (Food)
            // Cek apakah id_foods tidak kosong dan kalori berhasil diambil dari tabel foods
            if (!empty($log['id_foods']) && $log['food_calories'] !== null) {
                // Catatan: Karena tidak ada kolom serving size di tabel diary, 
                // kami mengasumsikan satu entri food di diary mewakili 1 unit/serving dari foods.
                $totalCalories += floatval($log['food_calories']); 
                $foodsCount++;
            }
        }
        mysqli_free_result($result_today_logs);
    }
}

// Total items logged today
$totalItemsLogged = $mealsCount + $foodsCount; 

// 3. Get total foods count (all time) - Menghitung semua makanan yang tersedia di sistem
$totalFoodsCount = 0;
$sql_total_foods = "SELECT COUNT(id) AS total_count FROM foods";
$result_total_foods = dbQuery($sql_total_foods);

if ($result_total_foods && mysqli_num_rows($result_total_foods) > 0) {
    $data = mysqli_fetch_assoc($result_total_foods);
    $totalFoodsCount = intval($data['total_count']);
    mysqli_free_result($result_total_foods);
}

// Variabel-variabel ($fullname, $totalCalories, $totalItemsLogged, $mealsCount, $foodsCount, $totalFoodsCount)
// sekarang siap digunakan di bagian HTML di bawahnya.
?>

<!DOCTYPE html>
<html lang="en" class="">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>NutriTrack - Dashboard</title>
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
					<li><a href="dashboard.php" class="font-semibold text-[#3dccc7]">Dashboard</a>
					</li>
					<li><a href="user.php" class="transition duration-200 hover:scale-105">User</a></li>
					<li><a href="season.php" class="transition duration-200 hover:scale-105">Season</a></li>
					<li><a href="meal.php" class="transition duration-200 hover:scale-105">Meal</a></li>
					<li><a href="food.php" class="transition duration-200 hover:scale-105">Food</a></li>
					<li><a href="daily.php" class="transition duration-200 hover:scale-105">Daily</a></li>
				</ul>
				<div class="hidden md:flex items-center space-x-3">
					<span class="dark:text-dark-text whitespace-nowrap">Hello,
						<?php echo htmlspecialchars($_SESSION['username']); ?></span>
					<a href="logout.php"
						class="inline-flex justify-center gap-2 text-white bg-[#3dccc7] hover:bg-[#68d8d6] px-4 py-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 w-full">Logout</a>
				</div>
				<div class="md:hidden">
					<button id="menu-toggle-btn" type="button" aria-expanded="false" aria-controls="mobile-menu"
						aria-label="Toggle navigation"
						class="p-2 rounded-lg transition text-gray-800 dark:text-gray-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#3dccc7]">
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
						<a href="dashboard.php" class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Dashboard</a>
						<a href="user.php" class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">User</a>
						<a href="food.php" class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Food</a>
						<a href="meal.php" class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Meal</a>
						<a href="staple.php" class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Staple</a>
						<a href="daily.php" class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Daily</a>
					</div>
					<div class="flex flex-col gap-3 py-3 border-t border-neutral-200 dark:border-neutral-700">
						<span class="text-sm opacity-70">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
						<a href="logout.php"
							class="inline-flex justify-center items-center gap-2 text-sm font-medium rounded-md py-2 px-4 text-white bg-[#3dccc7] hover:bg-[#68d8d6] transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#3dccc7]">Logout</a>
					</div>
				</div>
			</div>
		</div>
	</header>

	<!-- Main -->
	<main>
		<section class="pt-28 pb-12 md:pt-36 min-h-[60vh]">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="mb-8">
					<h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Dashboard</h1>
					<p class="mt-2 text-lg dark:opacity-80">Welcome back, <span
							class="font-semibold"><?php echo htmlspecialchars($fullname); ?></span>.</p>
				</div>

				<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
					<div class="p-6 rounded-lg shadow-md card hover:border-[#0F9E99] dark:hover:border-[#0F9E99] transition-all duration-200">
						<div class="text-sm opacity-80 mb-1">Calories Today</div>
						<div class="mt-2 text-3xl font-semibold text-[#3dccc7]"><?php echo number_format($totalCalories, 0); ?></div>
						<div class="text-xs opacity-60 mt-1">kcal</div>
					</div>
					<div class="p-6 rounded-lg shadow-md card hover:border-[#0F9E99] dark:hover:border-[#0F9E99] transition-all duration-200">
						<div class="text-sm opacity-80 mb-1">Items Logged</div>
						<div class="mt-2 text-3xl font-semibold text-[#3dccc7]"><?php echo $totalItemsLogged; ?></div>
						<div class="text-xs opacity-60 mt-1"><?php echo $mealsCount; ?> meals, <?php echo $foodsCount; ?> foods</div>
					</div>
					<div class="p-6 rounded-lg shadow-md card hover:border-[#0F9E99] dark:hover:border-[#0F9E99] transition-all duration-200">
						<div class="text-sm opacity-80 mb-1">Total Foods</div>
						<div class="mt-2 text-3xl font-semibold text-[#3dccc7]"><?php echo $totalFoodsCount; ?></div>
						<div class="text-xs opacity-60 mt-1">all time</div>
					</div>
				</div>
			</div>
		</section>
	</main>

	<!-- Theme Switcher -->
	<div class="fixed bottom-6 right-6 z-50 flex flex-col items-center space-y-4">
		<div class="p-1 rounded-full card shadow-md transition-all duration-300">
			<a href="setting.php" id="settings-btn"
				class="flex items-center justify-center p-2 rounded-full transition-colors duration-200">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
					stroke="currentColor" class="w-6 h-6">
					<path stroke-linecap="round" stroke-linejoin="round"
						d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.591 1.042c1.523-.878 3.25.848 2.372 2.372a1.724 1.724 0 001.042 2.591c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.042 2.591c.878 1.523-.849 3.25-2.372 2.372a1.724 1.724 0 00-2.591 1.042c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.591-1.042c-1.523.878-3.25-.849-2.372-2.372a1.724 1.724 0 00-1.042-2.591c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.042-2.591c-.878-1.524.849-3.25 2.372-2.372a1.724 1.724 0 002.591-1.042z" />
					<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
				</svg>
			</a>
		</div>

		<div id="theme-switcher"
			class="flex flex-col p-1 rounded-full card transition-all duration-300">
			<button id="system-btn"
				class="flex items-center justify-center p-2 rounded-full transition-colors duration-200">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
					stroke="currentColor" class="w-6 h-6">
					<path stroke-linecap="round" stroke-linejoin="round"
						d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
				</svg>
			</button>
			<button id="light-btn"
				class="flex items-center justify-center p-2 rounded-full transition-colors duration-200">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
					stroke="currentColor" class="w-6 h-6">
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
		if (dropdownButton && dropdownMenu) {
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
		}

		// === Theme Switcher Logic ===
		const systemBtn = document.getElementById('system-btn');
		const lightBtn = document.getElementById('light-btn');
		const darkBtn = document.getElementById('dark-btn');
		const buttons = [systemBtn, lightBtn, darkBtn].filter(Boolean);

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

		if (window.matchMedia) {
			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
				if (!('theme' in localStorage)) {
					applyTheme('system');
				}
			});
		}

		systemBtn && systemBtn.addEventListener('click', () => applyTheme('system'));
		lightBtn && lightBtn.addEventListener('click', () => applyTheme('light'));
		darkBtn && darkBtn.addEventListener('click', () => applyTheme('dark'));

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