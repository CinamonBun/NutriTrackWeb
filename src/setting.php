<?php
session_start();

if (!isset($_SESSION['username'])) {
	header("Location: signin.php");
	exit;
}

include 'config.php';
include 'db-functions.php';

$authUsername = $_SESSION['username'];
$user = getUserByUsername($authUsername);

if (!$user) {
	header("Location: signin.php");
	exit;
}

$error = '';
$success = '';
$formData = [
	'fullname' => $user['fullname'] ?? '',
	'email' => $user['email'] ?? '',
	'phone' => $user['phone'] ?? '',
	'height' => $user['height'] ?? '',
	'weight' => $user['weight'] ?? '',
	'age' => $user['age'] ?? '',
	'gender' => $user['gender'] ?? '',
	'daily_calories_target' => $user['daily_calories_target'] ?? 2000,
	'waist_size' => $user['waist_size'] ?? '',
	'bmi' => $user['bmi'] ?? ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';

	if ($action === 'update_profile') {
		$fullname = trim($_POST['fullname'] ?? '');
		$email = trim($_POST['email'] ?? '');
		$phone = trim($_POST['phone'] ?? '');
		$height = trim($_POST['height'] ?? '');
		$weight = trim($_POST['weight'] ?? '');
		$age = trim($_POST['age'] ?? '');
		$gender = trim($_POST['gender'] ?? '');
		$daily_calories_target = trim($_POST['daily_calories_target'] ?? '');
		$waist_size = trim($_POST['waist_size'] ?? '');

		// Validation
		if (strlen($fullname) < 3) {
			$error = 'Full name must be at least 3 characters.';
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = 'Please provide a valid email address.';
		} else {
			// Check if email is already used by another user
			if ($email !== ($user['email'] ?? '')) {
				$safe_email = escape($email);
				$safe_username = escape($authUsername);
				$sql_email_check = "SELECT username FROM users WHERE email = '$safe_email' AND username != '$safe_username' LIMIT 1";
				$result_email_check = dbQuery($sql_email_check);

				if ($result_email_check && mysqli_num_rows($result_email_check) > 0) {
					$error = 'Email already in use by another user.';
				}
			}

			if (!$error) {
				// Calculate BMI if height and weight are provided
				$bmi = null;
				if ($height > 0 && $weight > 0) {
					$heightInMeters = $height / 100; // Convert cm to meters
					$bmi = round($weight / ($heightInMeters * $heightInMeters), 2);
				}

				$updateData = [
					'fullname' => $fullname,
					'email' => $email,
					'phone' => $phone ? (int)$phone : null,
					'height' => $height ? (int)$height : null,
					'weight' => $weight ? (int)$weight : null,
					'age' => $age ? (int)$age : null,
					'gender' => $gender ?: null,
					'daily_calories_target' => $daily_calories_target ? (int)$daily_calories_target : null,
					'waist_size' => $waist_size ? (float)$waist_size : null,
					'bmi' => $bmi
				];

				// Build update query with proper handling of NULL values
				$updates = [];
				foreach ($updateData as $key => $value) {
					if ($value === null) {
						$updates[] = "$key = NULL";
					} else {
						$val = escape($value);
						$updates[] = "$key = '$val'";
					}
				}

				$safe_username = escape($authUsername);
				$sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE username = '$safe_username'";

				if (dbQuery($sql)) {
					$success = 'Profile updated successfully.';
					// Refresh user data
					$user = getUserByUsername($authUsername);
					$formData = [
						'fullname' => $user['fullname'] ?? '',
						'email' => $user['email'] ?? '',
						'phone' => $user['phone'] ?? '',
						'height' => $user['height'] ?? '',
						'weight' => $user['weight'] ?? '',
						'age' => $user['age'] ?? '',
						'gender' => $user['gender'] ?? '',
						'daily_calories_target' => $user['daily_calories_target'] ?? 2000,
						'waist_size' => $user['waist_size'] ?? '',
						'bmi' => $user['bmi'] ?? ''
					];
				} else {
					global $conn;
					$error = 'Failed to update profile. Error: ' . ($conn ? mysqli_error($conn) : 'Database connection error');
				}
			}
		}
	} elseif ($action === 'change_password') {
		$currentPassword = trim($_POST['current_password'] ?? '');
		$newPassword = trim($_POST['new_password'] ?? '');
		$confirmPassword = trim($_POST['confirm_password'] ?? '');

		// Check current password (support both plain text and hashed passwords)
		$passwordMatch = false;
		if ($currentPassword === $user['password']) {
			// Plain text match (existing system)
			$passwordMatch = true;
		} elseif (password_verify($currentPassword, $user['password'])) {
			// Hashed password match (if passwords were hashed)
			$passwordMatch = true;
		}

		if (!$passwordMatch) {
			$error = 'Current password is incorrect.';
		} elseif (strlen($newPassword) < 6) {
			$error = 'New password must be at least 6 characters.';
		} elseif ($newPassword !== $confirmPassword) {
			$error = 'New password and confirm password do not match.';
		} else {
			$updateData = ['password' => $newPassword];
			$response = updateUser($authUsername, $updateData);

			if ($response['status'] === 200) {
				$success = 'Password changed successfully.';
			} else {
				$error = 'Failed to change password.';
			}
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">

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

		.sr-only {
			position: absolute;
			width: 1px;
			height: 1px;
			padding: 0;
			margin: -1px;
			overflow: hidden;
			clip: rect(0, 0, 0, 0);
			border: 0;
		}
	</style>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
		rel="stylesheet">
</head>

<body class="min-h-screen">
	<!-- Header -->
	<header id="sticky-header" class="fixed z-50 w-full transition-all duration-300 ease-in-out py-6">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<nav class="relative flex justify-between items-center">
				<div class="flex items-center">
					<h1 class="text-2xl font-bold">NutriTrack+</h1>
				</div>
				<ul class="hidden md:flex items-center space-x-8">
					<li><a href="dashboard.php" class="transition duration-200 hover:scale-105">Dashboard</a></li>
					<li><a href="user.php" class="transition duration-200 hover:scale-105">User</a></li>
					<li><a href="season.php" class="transition duration-200 hover:scale-105">Season</a></li>
					<li><a href="meal.php" class="transition duration-200 hover:scale-105">Meal</a></li>
					<li><a href="food.php" class="transition duration-200 hover:scale-105">Food</a></li>
					<li><a href="daily.php" class="transition duration-200 hover:scale-105">Daily</a></li>
				</ul>
				<div class="hidden md:flex items-center space-x-3">
					<span class="whitespace-nowrap">Hello, <?php echo htmlspecialchars($authUsername); ?></span>
					<a href="logout.php"
						class="inline-flex justify-center gap-2 text-white bg-[#3dccc7] hover:bg-[#68d8d6] px-4 py-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2">Logout</a>
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
				<div class="card shadow-lg rounded-xl p-6 space-y-4">
					<div class="flex flex-col space-y-3">
						<a href="dashboard.php"
							class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Dashboard</a>
						<a href="user.php"
							class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">User</a>
						<a href="season.php"
							class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Season</a>
						<a href="meal.php"
							class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Meal</a>
						<a href="food.php"
							class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Food</a>
						<a href="daily.php"
							class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Daily</a>
						<a href="setting.php"
							class="block text-base font-semibold text-[#3dccc7] transition-colors duration-200">Settings</a>
					</div>
					<div class="flex flex-col gap-3 py-3 border-t border-neutral-200 dark:border-neutral-700">
						<span class="text-sm opacity-70">Hello, <?php echo htmlspecialchars($authUsername); ?></span>
						<a href="logout.php"
							class="inline-flex justify-center items-center gap-2 text-sm font-medium rounded-md py-2 px-4 text-white bg-[#3dccc7] hover:bg-[#68d8d6] transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#3dccc7]">Logout</a>
					</div>
				</div>
			</div>
		</div>
	</header>

	<main class="pt-28 md:pt-36 pb-12">
		<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
			<!-- Page Header -->
			<div>
				<p class="text-sm uppercase tracking-widest opacity-60">Settings</p>
				<h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Account Settings</h1>
				<p class="mt-2 text-base opacity-80">Manage your profile information and preferences.</p>
			</div>

			<!-- Flash Messages -->
			<?php if ($error) { ?>
				<div class="rounded-lg px-4 py-3 fade-in bg-red-50 text-red-700 border border-red-200">
					<?php echo htmlspecialchars($error); ?>
				</div>
			<?php } ?>

			<?php if ($success) { ?>
				<div class="rounded-lg px-4 py-3 fade-in bg-emerald-50 text-emerald-700 border border-emerald-200">
					<?php echo htmlspecialchars($success); ?>
				</div>
			<?php } ?>

			<!-- Profile Information Section -->
			<section class="card rounded-2xl shadow-lg p-6 space-y-6">
				<div>
					<h2 class="text-2xl font-semibold">Profile Information</h2>
					<p class="text-sm opacity-70 mt-1">Update your personal information and health metrics.</p>
				</div>

				<form method="POST" action="setting.php" class="space-y-6">
					<input type="hidden" name="action" value="update_profile" />

					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label for="fullname" class="block text-sm font-medium mb-2">Full Name</label>
							<input type="text" id="fullname" name="fullname"
								value="<?php echo htmlspecialchars($formData['fullname']); ?>" required
								class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
						</div>

						<div>
							<label for="email" class="block text-sm font-medium mb-2">Email</label>
							<input type="email" id="email" name="email"
								value="<?php echo htmlspecialchars($formData['email']); ?>" required
								class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
						</div>

						<div>
							<label for="phone" class="block text-sm font-medium mb-2">Phone Number</label>
							<input type="tel" id="phone" name="phone"
								value="<?php echo htmlspecialchars($formData['phone']); ?>"
								class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
						</div>

						<div>
							<label for="age" class="block text-sm font-medium mb-2">Age</label>
							<input type="number" id="age" name="age" min="1" max="150"
								value="<?php echo htmlspecialchars($formData['age']); ?>"
								class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
						</div>

						<div>
							<label for="gender" class="block text-sm font-medium mb-2">Gender</label>
							<select id="gender" name="gender"
								class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]">
								<option value="">Select Gender</option>
								<option value="laki-laki" <?php echo $formData['gender'] === 'laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
								<option value="Perempuan" <?php echo $formData['gender'] === 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
								<option value="perempuan" <?php echo $formData['gender'] === 'perempuan' ? 'selected' : ''; ?>>Perempuan</option>
							</select>
						</div>

						<div>
							<label for="height" class="block text-sm font-medium mb-2">Height (cm)</label>
							<input type="number" id="height" name="height" min="1" max="300"
								value="<?php echo htmlspecialchars($formData['height']); ?>"
								class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
						</div>

						<div>
							<label for="weight" class="block text-sm font-medium mb-2">Weight (kg)</label>
							<input type="number" id="weight" name="weight" min="1" max="500" step="0.1"
								value="<?php echo htmlspecialchars($formData['weight']); ?>"
								class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
						</div>

						<div>
							<label for="waist_size" class="block text-sm font-medium mb-2">Waist Size (cm)</label>
							<input type="number" id="waist_size" name="waist_size" min="1" max="200" step="0.1"
								value="<?php echo htmlspecialchars($formData['waist_size']); ?>"
								class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
						</div>

						<div>
							<label for="daily_calories_target" class="block text-sm font-medium mb-2">Daily Calories Target</label>
							<input type="number" id="daily_calories_target" name="daily_calories_target" min="1" max="10000"
								value="<?php echo htmlspecialchars($formData['daily_calories_target']); ?>"
								class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
						</div>

						<div>
							<label for="bmi" class="block text-sm font-medium mb-2">BMI</label>
							<input type="text" id="bmi" name="bmi" readonly
								value="<?php echo htmlspecialchars($formData['bmi'] ?: 'Calculate by entering height and weight'); ?>"
								class="w-full card px-4 py-3 rounded-lg bg-neutral-50 dark:bg-neutral-900/60 focus:outline-none" />
						</div>
					</div>

					<div class="flex justify-end pt-4">
						<button type="submit"
							class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold text-white bg-[#3dccc7] hover:bg-[#68d8d6] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#3dccc7]">
							Save Changes
						</button>
					</div>
				</form>
			</section>

			<!-- Change Password Section -->
			<section class="card rounded-2xl shadow-lg p-6 space-y-6">
				<div>
					<h2 class="text-2xl font-semibold">Change Password</h2>
					<p class="text-sm opacity-70 mt-1">Update your password to keep your account secure.</p>
				</div>

				<form method="POST" action="setting.php" class="space-y-4">
					<input type="hidden" name="action" value="change_password" />

					<div>
						<label for="current_password" class="block text-sm font-medium mb-2">Current Password</label>
						<input type="password" id="current_password" name="current_password" required
							class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
					</div>

					<div>
						<label for="new_password" class="block text-sm font-medium mb-2">New Password</label>
						<input type="password" id="new_password" name="new_password" required minlength="6"
							class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
						<p class="text-xs opacity-60 mt-1">Password must be at least 6 characters.</p>
					</div>

					<div>
						<label for="confirm_password" class="block text-sm font-medium mb-2">Confirm New Password</label>
						<input type="password" id="confirm_password" name="confirm_password" required minlength="6"
							class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
					</div>

					<div class="flex justify-end pt-4">
						<button type="submit"
							class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold text-white bg-[#3dccc7] hover:bg-[#68d8d6] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#3dccc7]">
							Change Password
						</button>
					</div>
				</form>
			</section>
		</div>
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
		// Mobile Menu Toggle
		const menuToggleBtn = document.getElementById('menu-toggle-btn');
		const mobileMenu = document.getElementById('mobile-menu');
		if (menuToggleBtn && mobileMenu) {
			menuToggleBtn.addEventListener('click', () => {
				const expanded = menuToggleBtn.getAttribute('aria-expanded') === 'true';
				menuToggleBtn.setAttribute('aria-expanded', (!expanded).toString());
				mobileMenu.classList.toggle('hidden');
			});
		}

		// Auto-calculate BMI
		const heightInput = document.getElementById('height');
		const weightInput = document.getElementById('weight');
		const bmiInput = document.getElementById('bmi');

		function calculateBMI() {
			const height = parseFloat(heightInput.value);
			const weight = parseFloat(weightInput.value);

			if (height > 0 && weight > 0) {
				const heightInMeters = height / 100;
				const bmi = (weight / (heightInMeters * heightInMeters)).toFixed(2);
				bmiInput.value = bmi;
			} else {
				bmiInput.value = 'Calculate by entering height and weight';
			}
		}

		if (heightInput && weightInput && bmiInput) {
			heightInput.addEventListener('input', calculateBMI);
			weightInput.addEventListener('input', calculateBMI);
		}

		// Theme Switcher
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

		// Sticky Header Logic
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