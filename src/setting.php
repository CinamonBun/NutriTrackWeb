<?php
session_start();

if (!isset($_SESSION['username'])) {
	header("Location: signin.php");
	exit;
}

include 'config.php';

$username = $_SESSION['username'];

// Check if user is admin
requireAdmin($username);

$user = getUserByUsername($username);

$profileSuccess = '';
$profileError = '';
$passwordSuccess = '';
$passwordError = '';

if (!$user) {
	$profileError = 'We could not load your profile from Supabase.';
	$user = [
		'fullname' => $username,
		'email' => '',
		'phone' => '',
		'password' => ''
	];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['update_profile'])) {
		$fullname = trim($_POST['fullname'] ?? '');
		$email = trim($_POST['email'] ?? '');
		$phone = trim($_POST['phone'] ?? '');

		if (strlen($fullname) < 3) {
			$profileError = 'Full name must be at least 3 characters.';
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$profileError = 'Please enter a valid email address.';
		} else {
			$updateData = [
				'fullname' => $fullname,
				'email' => $email,
				'phone' => $phone
			];

			$response = updateUser($username, $updateData);

			if ($response['status'] === 200) {
				$profileSuccess = 'Profile updated successfully.';
				$user = array_merge($user, $updateData);
			} else {
				$profileError = 'Failed to update profile. Please try again.';
			}
		}
	}

	if (isset($_POST['update_password'])) {
		$currentPassword = trim($_POST['current_password'] ?? '');
		$newPassword = trim($_POST['new_password'] ?? '');
		$confirmPassword = trim($_POST['confirm_password'] ?? '');

		if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
			$passwordError = 'Please fill in all password fields.';
		} elseif ($currentPassword !== ($user['password'] ?? '')) {
			$passwordError = 'Current password is incorrect.';
		} elseif (strlen($newPassword) < 6) {
			$passwordError = 'New password must be at least 6 characters.';
		} elseif ($newPassword !== $confirmPassword) {
			$passwordError = 'New password and confirmation do not match.';
		} else {
			$response = updateUser($username, ['password' => $newPassword]);

			if ($response['status'] === 200) {
				$passwordSuccess = 'Password updated successfully.';
				$user['password'] = $newPassword;
			} else {
				$passwordError = 'Failed to update password. Please try again.';
			}
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en" class="">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>NutriTrack - Settings</title>
	<link href="./output.css" rel="stylesheet">
	<style>
		body {
			font-family: 'Plus Jakarta Sans', sans-serif;
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

<body class="min-h-screen">
	<header id="sticky-header" class="fixed z-50 w-full transition-all duration-300 ease-in-out py-6">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<nav class="relative flex justify-between items-center">
				<div class="flex items-center">
					<h1 class="text-2xl font-bold">NutriTrack+</h1>
				</div>
				<ul class="hidden md:flex items-center space-x-8">
					<li><a href="dashboard.php" class="transition duration-200 transform hover:scale-105">Dashboard</a>
					</li>
					<li><a href="user.php" class="transition duration-200 transform hover:scale-105">User</a></li>
					<li><a href="season.php" class="transition duration-200 transform hover:scale-105">Season</a></li>
					<li><a href="meal.php" class="transition duration-200 transform hover:scale-105">Meal</a></li>
					<li><a href="food.php" class="transition duration-200 transform hover:scale-105">Food</a></li>
					<li><a href="daily.php" class="transition duration-200 transform hover:scale-105">Daily</a></li>
				</ul>
				<div class="hidden md:flex items-center space-x-3">
					<span class="dark:text-dark-text whitespace-nowrap">Hello,
						<?php echo htmlspecialchars($username); ?></span>
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
						<a href="dashboard.php"
							class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Dashboard</a>
						<a href="user.php"
							class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">User</a>
						<a href="food.php"
							class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Food</a>
						<a href="meal.php"
							class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Meal</a>
						<a href="daily.php"
							class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Daily</a>
						<a href="setting.php"
							class="block text-base font-semibold text-[#3dccc7] transition-colors duration-200">Settings</a>
					</div>
					<div class="flex flex-col gap-3 py-3 border-t border-neutral-200 dark:border-neutral-700">
						<span class="text-sm opacity-70">Hello, <?php echo htmlspecialchars($username); ?></span>
						<a href="logout.php"
							class="inline-flex justify-center items-center gap-2 text-sm font-medium rounded-md py-2 px-4 text-white bg-[#3dccc7] hover:bg-[#68d8d6] transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#3dccc7]">Logout</a>
					</div>
				</div>
			</div>
		</div>
	</header>

	<main class="pt-28 md:pt-36 pb-12">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
			<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
				<div>
					<p class="text-sm uppercase tracking-widest opacity-60">Settings</p>
					<h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Control center</h1>
					<p class="mt-2 text-base opacity-80">Update your account details, privacy preferences, and daily
						habits in one place.</p>
				</div>
				<div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 w-full md:w-auto">
					<div class="card rounded-xl p-4 shadow-sm">
						<p class="text-xs uppercase tracking-widest opacity-60">Account status</p>
						<p class="text-lg font-semibold mt-1">Active</p>
						<p class="text-xs opacity-60 mt-1">Last synced:
							<?php echo date('M j, Y g:i A'); ?>
						</p>
					</div>
					<!-- <div class="card rounded-xl p-4 shadow-sm">
						<p class="text-xs uppercase tracking-widest opacity-60">Plan</p>
						<p class="text-lg font-semibold mt-1">Personal</p>
						<p class="text-xs opacity-60 mt-1">Unlimited logs</p>
					</div>
					<div class="card rounded-xl p-4 shadow-sm">
						<p class="text-xs uppercase tracking-widest opacity-60">Streak</p>
						<p class="text-lg font-semibold mt-1">7 days</p>
						<p class="text-xs opacity-60 mt-1">Keep it going!</p>
					</div> -->
				</div>
			</div>

			<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
				<div class="lg:col-span-2 space-y-8">
					<section class="card rounded-2xl shadow-lg p-6 space-y-6">
						<div class="flex items-start justify-between gap-4 flex-wrap">
							<div>
								<h2 class="text-2xl font-semibold">Profile information</h2>
								<p class="text-sm opacity-70 mt-2">Keep your contact details up to date so we can tailor
									your plan.</p>
							</div>
							<div class="text-right">
								<p class="text-xs uppercase tracking-widest opacity-70">Member since</p>
								<p class="text-sm font-medium">
									<?php echo date('F Y'); ?>
								</p>
							</div>
						</div>

						<?php if ($profileSuccess) { ?>
							<div class="rounded-lg bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 px-4 py-3 text-sm text-emerald-600 dark:text-emerald-200 fade-in">
								<?php echo htmlspecialchars($profileSuccess); ?>
							</div>
						<?php } ?>

						<?php if ($profileError) { ?>
							<div class="rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 px-4 py-3 text-sm text-red-600 dark:text-red-200 fade-in">
								<?php echo htmlspecialchars($profileError); ?>
							</div>
						<?php } ?>

						<form method="POST" action="setting.php" class="space-y-5">
							<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
								<div>
									<label for="fullname" class="block text-sm font-medium mb-2">Full name</label>
									<input type="text" id="fullname" name="fullname"
										value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>" required
										class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7] transition" />
								</div>
								<div>
									<label for="email" class="block text-sm font-medium mb-2">Email address</label>
									<input type="email" id="email" name="email"
										value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required
										class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7] transition" />
								</div>
							</div>
							<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
								<div>
									<label for="username" class="block text-sm font-medium mb-2">Username</label>
									<input type="text" id="username" value="<?php echo htmlspecialchars($username); ?>"
										disabled
										class="w-full card px-4 py-3 rounded-lg opacity-60 cursor-not-allowed" />
								</div>
								<div>
									<label for="phone" class="block text-sm font-medium mb-2">Phone number (optional)</label>
									<input type="tel" id="phone" name="phone"
										value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
										class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7] transition"
										placeholder="+62 812-3456-7890" />
								</div>
							</div>
							<div class="flex flex-wrap gap-3 justify-end">
								<button type="reset"
									class="px-5 py-2 rounded-lg border border-neutral-200 dark:border-neutral-700 text-sm font-medium transition hover:border-[#3dccc7]">Reset</button>
								<button type="submit" name="update_profile"
									class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold text-white bg-[#3dccc7] hover:bg-[#68d8d6] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#3dccc7]">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M5 13l4 4L19 7" />
									</svg>
									Save changes
								</button>
							</div>
						</form>
					</section>

					<section class="card rounded-2xl shadow-lg p-6 space-y-6">
						<div class="flex items-start justify-between flex-wrap gap-4">
							<div>
								<h2 class="text-2xl font-semibold">Security</h2>
								<p class="text-sm opacity-70 mt-2">Protect your account with a strong password.</p>
							</div>
							<div class="text-right">
								<p class="text-xs uppercase tracking-widest opacity-70">Password strength</p>
								<p class="text-sm font-semibold text-emerald-500">Secure</p>
							</div>
						</div>

						<?php if ($passwordSuccess) { ?>
							<div class="rounded-lg bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 px-4 py-3 text-sm text-emerald-600 dark:text-emerald-200 fade-in">
								<?php echo htmlspecialchars($passwordSuccess); ?>
							</div>
						<?php } ?>

						<?php if ($passwordError) { ?>
							<div class="rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 px-4 py-3 text-sm text-red-600 dark:text-red-200 fade-in">
								<?php echo htmlspecialchars($passwordError); ?>
							</div>
						<?php } ?>

						<form method="POST" action="setting.php" class="space-y-5">
							<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
								<div class="md:col-span-3">
									<label for="current_password" class="block text-sm font-medium mb-2">Current password</label>
									<input type="password" id="current_password" name="current_password" required
										class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7] transition" />
								</div>
								<div>
									<label for="new_password" class="block text-sm font-medium mb-2">New password</label>
									<input type="password" id="new_password" name="new_password" required
										class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7] transition" />
								</div>
								<div>
									<label for="confirm_password" class="block text-sm font-medium mb-2">Confirm password</label>
									<input type="password" id="confirm_password" name="confirm_password" required
										class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7] transition" />
								</div>
								<div class="flex items-center card rounded-lg border border-amber-200 bg-amber-50/60 dark:bg-amber-900/20 px-4 py-3 gap-3">
									<svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
									</svg>
									<p class="text-xs opacity-80">We currently store passwords as plain text. Use a unique password for NutriTrack.</p>
								</div>
							</div>
							<div class="flex flex-wrap gap-3 justify-end">
								<button type="submit" name="update_password"
									class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold text-white bg-[#3dccc7] hover:bg-[#68d8d6] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#3dccc7]">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M12 11c0-1.105.672-2 1.5-2S15 9.895 15 11v2m-3 4v-2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2h-1V9a4 4 0 00-8 0v2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
									</svg>
									Update password
								</button>
							</div>
						</form>
					</section>
				</div>

				<div class="space-y-8">
					<section class="card rounded-2xl shadow-lg p-6 space-y-4">
						<div>
							<h2 class="text-xl font-semibold">Data & privacy</h2>
							<p class="text-sm opacity-70 mt-1">Control how NutriTrack uses your data.</p>
						</div>
						<ul class="space-y-4 text-sm">
							<li class="flex justify-between items-center">
								<div>
									<p class="font-medium">Download activity</p>
									<p class="opacity-70 text-xs">Export your logs as CSV.</p>
								</div>
								<button id="export-btn"
									class="px-4 py-2 rounded-lg border border-neutral-200 dark:border-neutral-700 text-xs font-semibold hover:border-[#3dccc7] transition">Export</button>
							</li>
							<li class="flex justify-between items-center">
								<div>
									<p class="font-medium">Session timeout</p>
									<p class="opacity-70 text-xs">Auto-sign out after inactivity.</p>
								</div>
								<select id="timeout-select"
									class="card rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3dccc7]">
									<option value="15">15 minutes</option>
									<option value="30" selected>30 minutes</option>
									<option value="60">1 hour</option>
								</select>
							</li>
							<li class="flex justify-between items-center">
								<div>
									<p class="font-medium">Account deletion</p>
									<p class="opacity-70 text-xs">Permanently remove your data.</p>
								</div>
								<button id="delete-request"
									class="px-4 py-2 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 transition">Request</button>
							</li>
						</ul>
					</section>
				</div>
			</div>
		</div>
	</main>

	<!-- Theme Switcher -->
	<div class="flex space-x-2">
		<div id="theme-switcher" class="fixed bottom-6 left-6 z-50 flex flex-col p-1 rounded-full card shadow-sm">
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
		// === Mobile menu ===
		const menuToggleBtn = document.getElementById('menu-toggle-btn');
		const mobileMenu = document.getElementById('mobile-menu');
		if (menuToggleBtn && mobileMenu) {
			menuToggleBtn.addEventListener('click', () => {
				const expanded = menuToggleBtn.getAttribute('aria-expanded') === 'true';
				menuToggleBtn.setAttribute('aria-expanded', (!expanded).toString());
				if (!expanded) {
					mobileMenu.classList.remove('hidden');
					mobileMenu.classList.add('block');
					mobileMenu.querySelector('.mobile-menu-panel')?.classList.add('animate-open');
				} else {
					mobileMenu.querySelector('.mobile-menu-panel')?.classList.remove('animate-open');
					mobileMenu.querySelector('.mobile-menu-panel')?.classList.add('animate-close');
					setTimeout(() => {
						mobileMenu.classList.add('hidden');
						mobileMenu.classList.remove('block');
						mobileMenu.querySelector('.mobile-menu-panel')?.classList.remove('animate-close');
					}, 200);
				}
			});
		}

		// === Theme switcher ===
		const systemBtn = document.getElementById('system-btn');
		const lightBtn = document.getElementById('light-btn');
		const darkBtn = document.getElementById('dark-btn');
		const themeButtons = [systemBtn, lightBtn, darkBtn];

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
			themeButtons.forEach(btn => {
				if (!btn) return;
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
		applyTheme(getActiveTheme());

		// === Sticky header ===
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

		// === Preferences (local) ===
		const PREF_KEY = 'nutritrack_preferences';
		const preferenceForm = document.getElementById('preference-form');
		const messageEl = document.getElementById('preference-message');
		const calorieGoal = document.getElementById('calorie_goal');
		const waterGoal = document.getElementById('water_goal');
		const reminderToggle = document.getElementById('reminder_toggle');
		const reminderTime = document.getElementById('reminder_time');
		const weeklyReport = document.getElementById('weekly_report');
		const timeoutSelect = document.getElementById('timeout-select');

		const defaultPreferences = {
			calorieGoal: 2100,
			waterGoal: 8,
			reminderEnabled: true,
			reminderTime: '09:00',
			weeklyReport: true,
			sessionTimeout: '30'
		};

		const loadPreferences = () => {
			try {
				return JSON.parse(localStorage.getItem(PREF_KEY)) || {
					...defaultPreferences
				};
			} catch (error) {
				return {
					...defaultPreferences
				};
			}
		};

		const savePreferences = (prefs) => {
			localStorage.setItem(PREF_KEY, JSON.stringify(prefs));
		};

		const renderPreferences = () => {
			const prefs = loadPreferences();
			if (calorieGoal) calorieGoal.value = prefs.calorieGoal;
			if (waterGoal) waterGoal.value = prefs.waterGoal;
			if (reminderToggle) reminderToggle.checked = prefs.reminderEnabled;
			if (reminderTime) reminderTime.value = prefs.reminderTime;
			if (weeklyReport) weeklyReport.checked = prefs.weeklyReport;
			if (timeoutSelect) timeoutSelect.value = prefs.sessionTimeout;
		};

		preferenceForm?.addEventListener('submit', (event) => {
			event.preventDefault();
			const newPrefs = {
				calorieGoal: Number(calorieGoal.value) || defaultPreferences.calorieGoal,
				waterGoal: Number(waterGoal.value) || defaultPreferences.waterGoal,
				reminderEnabled: reminderToggle.checked,
				reminderTime: reminderTime.value || defaultPreferences.reminderTime,
				weeklyReport: weeklyReport.checked,
				sessionTimeout: timeoutSelect.value
			};
			savePreferences(newPrefs);
			if (messageEl) {
				messageEl.textContent = 'Preferences saved locally.';
				messageEl.classList.remove('text-red-500');
				messageEl.classList.add('text-emerald-500');
				setTimeout(() => {
					messageEl.textContent = '';
				}, 3000);
			}
		});

		renderPreferences();

		timeoutSelect?.addEventListener('change', () => {
			const prefs = loadPreferences();
			prefs.sessionTimeout = timeoutSelect.value;
			savePreferences(prefs);
		});

		document.getElementById('export-btn')?.addEventListener('click', () => {
			alert('Export will soon be available. For now, request data via support.');
		});

		document.getElementById('delete-request')?.addEventListener('click', () => {
			if (confirm('Send a request to delete your account? We will follow up via email.')) {
				alert('Deletion request submitted. Our team will contact you soon.');
			}
		});
	</script>
</body>

</html>