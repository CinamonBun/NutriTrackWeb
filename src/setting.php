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
$avatarSuccess = '';
$avatarError = '';

if (!$user) {
	$profileError = 'We could not load your profile from Supabase.';
	$user = [
		'fullname' => $username,
		'email' => '',
		'phone' => '',
		'password' => '',
		'avatar' => ''
	];
}

// Handle AJAX requests
if (isset($_GET['action'])) {
	header('Content-Type: application/json');

	switch ($_GET['action']) {
		case 'export_data':
			// Generate CSV export
			$data = [
				['Field', 'Value'],
				['Username', $username],
				['Full Name', $user['fullname'] ?? ''],
				['Email', $user['email'] ?? ''],
				['Phone', $user['phone'] ?? ''],
				['Member Since', date('F Y')],
			];

			$filename = 'nutritrack_data_' . date('Y-m-d') . '.csv';
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="' . $filename . '"');

			$output = fopen('php://output', 'w');
			foreach ($data as $row) {
				fputcsv($output, $row);
			}
			fclose($output);
			exit;

		case 'delete_account':
			// In production, you would send an email or create a deletion request
			echo json_encode(['success' => true, 'message' => 'Account deletion request submitted. Our team will contact you via email.']);
			exit;

		case 'update_timeout':
			$timeout = intval($_POST['timeout'] ?? 30);
			$_SESSION['session_timeout'] = $timeout;
			echo json_encode(['success' => true, 'message' => 'Session timeout updated to ' . $timeout . ' minutes']);
			exit;
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Handle avatar upload
	if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
		$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
		$maxSize = 2 * 1024 * 1024; // 2MB

		$fileType = $_FILES['avatar']['type'];
		$fileSize = $_FILES['avatar']['size'];

		if (!in_array($fileType, $allowedTypes)) {
			$avatarError = 'Invalid file type. Please upload JPG, PNG, GIF, or WebP.';
		} elseif ($fileSize > $maxSize) {
			$avatarError = 'File size must be less than 2MB.';
		} else {
			$uploadDir = 'uploads/avatars/';
			if (!file_exists($uploadDir)) {
				mkdir($uploadDir, 0755, true);
			}

			$extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
			$filename = $username . '_' . time() . '.' . $extension;
			$uploadPath = $uploadDir . $filename;

			if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
				$response = updateUser($username, ['avatar' => $uploadPath]);

				if ($response['status'] === 200) {
					$avatarSuccess = 'Avatar uploaded successfully.';
					$user['avatar'] = $uploadPath;
				} else {
					$avatarError = 'Failed to save avatar. Please try again.';
				}
			} else {
				$avatarError = 'Failed to upload file.';
			}
		}
	}

	// Handle avatar removal
	if (isset($_POST['remove_avatar'])) {
		if (!empty($user['avatar']) && file_exists($user['avatar'])) {
			unlink($user['avatar']);
		}

		$response = updateUser($username, ['avatar' => '']);

		if ($response['status'] === 200) {
			$avatarSuccess = 'Avatar removed successfully.';
			$user['avatar'] = '';
		} else {
			$avatarError = 'Failed to remove avatar.';
		}
	}

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
		} elseif (strlen($newPassword) < 8) {
			$passwordError = 'New password must be at least 8 characters.';
		} elseif (!preg_match('/[A-Z]/', $newPassword)) {
			$passwordError = 'Password must contain at least one uppercase letter.';
		} elseif (!preg_match('/[a-z]/', $newPassword)) {
			$passwordError = 'Password must contain at least one lowercase letter.';
		} elseif (!preg_match('/[0-9]/', $newPassword)) {
			$passwordError = 'Password must contain at least one number.';
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

// Get avatar URL
$avatarUrl = !empty($user['avatar']) && file_exists($user['avatar'])
	? $user['avatar']
	: 'https://ui-avatars.com/api/?name=' . urlencode($user['fullname'] ?? $username) . '&size=120&background=3dccc7&color=fff';
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

		/* Password strength bar */
		.password-strength-bar {
			height: 4px;
			border-radius: 2px;
			transition: all 0.3s ease;
		}

		.strength-weak {
			background: linear-gradient(to right, #ef4444 0%, #ef4444 33%, #e5e7eb 33%);
		}

		.strength-medium {
			background: linear-gradient(to right, #f59e0b 0%, #f59e0b 66%, #e5e7eb 66%);
		}

		.strength-strong {
			background: linear-gradient(to right, #10b981 0%, #10b981 100%);
		}

		/* Input with icon */
		.input-with-icon {
			position: relative;
		}

		.input-with-icon input {
			padding-right: 40px;
		}

		.input-with-icon button {
			position: absolute;
			right: 8px;
			top: 50%;
			transform: translateY(-50%);
		}

		/* Toast notification */
		.toast {
			position: fixed;
			top: 100px;
			right: 20px;
			z-index: 1000;
			animation: slideInRight 0.3s ease;
		}

		@keyframes slideInRight {
			from {
				transform: translateX(400px);
				opacity: 0;
			}

			to {
				transform: translateX(0);
				opacity: 1;
			}
		}

		/* Modal backdrop */
		.modal-backdrop {
			background-color: rgba(0, 0, 0, 0.5);
			backdrop-filter: blur(4px);
		}

		/* Avatar upload preview */
		.avatar-preview {
			width: 120px;
			height: 120px;
			border-radius: 50%;
			object-fit: cover;
		}

		.avatar-upload-btn {
			position: absolute;
			bottom: 0;
			right: 0;
		}
	</style>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
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
					<li><a href="dashboard.php" class="transition duration-200 transform hover:scale-105">Dashboard</a></li>
					<li><a href="user.php" class="transition duration-200 transform hover:scale-105">User</a></li>
					<li><a href="season.php" class="transition duration-200 transform hover:scale-105">Season</a></li>
					<li><a href="meal.php" class="transition duration-200 transform hover:scale-105">Meal</a></li>
					<li><a href="food.php" class="transition duration-200 transform hover:scale-105">Food</a></li>
					<li><a href="daily.php" class="transition duration-200 transform hover:scale-105">Daily</a></li>
				</ul>
				<div class="hidden md:flex items-center space-x-3">
					<span class="dark:text-dark-text whitespace-nowrap">Hello, CinamonBun</span>
					<a href="logout.php"
						class="inline-flex justify-center gap-2 text-white bg-[#3dccc7] hover:bg-[#68d8d6] px-4 py-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 w-full">Logout</a>
				</div>
				<div class="md:hidden">
					<button id="menu-toggle-btn" type="button" aria-expanded="false" aria-controls="mobile-menu"
						aria-label="Toggle navigation"
						class="p-2 rounded-lg transition text-gray-800 dark:text-gray-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#3dccc7]">
						<svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
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
						<span class="text-sm opacity-70">Hello, CinamonBun</span>
						<a href="logout.php"
							class="inline-flex justify-center items-center gap-2 text-sm font-medium rounded-md py-2 px-4 text-white bg-[#3dccc7] hover:bg-[#68d8d6] transition-colors duration-200">Logout</a>
					</div>
				</div>
			</div>
		</div>
	</header>

	<main class="pt-28 md:pt-36 pb-12">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
			<!-- Header Section -->
			<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
				<div>
					<p class="text-sm uppercase tracking-widest opacity-60">Settings</p>
					<h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Control center</h1>
					<p class="mt-2 text-base opacity-80">Update your account details, privacy preferences, and daily habits in one place.</p>
				</div>
				<div class="card rounded-xl p-4 shadow-sm">
					<p class="text-xs uppercase tracking-widest opacity-60">Account status</p>
					<p class="text-lg font-semibold mt-1">Active</p>
					<p class="text-xs opacity-60 mt-1">Last synced: Nov 25, 2025 12:53 PM</p>
				</div>
			</div>

			<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
				<!-- Main Content -->
				<div class="lg:col-span-2 space-y-8">
					<!-- Profile Information Section -->
					<section class="card rounded-2xl shadow-lg p-6 space-y-6">
						<div class="flex items-start justify-between gap-4 flex-wrap">
							<div>
								<h2 class="text-2xl font-semibold">Profile information</h2>
								<p class="text-sm opacity-70 mt-2">Keep your contact details up to date so we can tailor your plan.</p>
							</div>
							<div class="text-right">
								<p class="text-xs uppercase tracking-widest opacity-70">Member since</p>
								<p class="text-sm font-medium">November 2025</p>
							</div>
						</div>

						<!-- Avatar Upload Section -->
						<div class="flex items-center gap-6 p-4 card rounded-xl">
							<div class="relative">
								<img id="avatar-preview" src="https://ui-avatars.com/api/?name=Cinamon+Bun&size=120&background=3dccc7&color=fff"
									alt="Profile avatar" class="avatar-preview">
								<label for="avatar-upload"
									class="avatar-upload-btn absolute bottom-0 right-0 p-2 bg-[#3dccc7] rounded-full cursor-pointer hover:bg-[#68d8d6] transition">
									<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
									</svg>
								</label>
								<input type="file" id="avatar-upload" accept="image/*" class="hidden">
							</div>
							<div>
								<h3 class="font-semibold">Profile Picture</h3>
								<p class="text-sm opacity-70 mt-1">Upload a new avatar. JPG or PNG, max 2MB.</p>
								<button id="remove-avatar" class="text-sm text-red-500 hover:text-red-600 mt-2">Remove photo</button>
							</div>
						</div>

						<form id="profile-form" method="POST" action="setting.php" class="space-y-5">
							<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
								<div>
									<label for="fullname" class="block text-sm font-medium mb-2">Full name</label>
									<input type="text" id="fullname" name="fullname" value="unknown" required
										class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7] transition"
										placeholder="Enter your full name" />
								</div>
								<div>
									<label for="email" class="block text-sm font-medium mb-2">Email address</label>
									<input type="email" id="email" name="email" value="unknown@gmail.com" required
										class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7] transition"
										placeholder="your.email@example.com" />
									<p id="email-validation" class="text-xs mt-1 hidden"></p>
								</div>
							</div>
							<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
								<div>
									<label for="username" class="block text-sm font-medium mb-2">Username</label>
									<input type="text" id="username" value="CinamonBun" disabled
										class="w-full card px-4 py-3 rounded-lg opacity-60 cursor-not-allowed" />
									<p class="text-xs opacity-60 mt-1">Username cannot be changed</p>
								</div>
								<div>
									<label for="phone" class="block text-sm font-medium mb-2">Phone number (optional)</label>
									<input type="tel" id="phone" name="phone" value="+62 812-3456-7890"
										class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7] transition"
										placeholder="+62 812-3456-7890" />
								</div>
							</div>
							<div class="flex flex-wrap gap-3 justify-end">
								<button type="reset"
									class="px-5 py-2 rounded-lg border border-neutral-200 dark:border-neutral-700 text-sm font-medium transition hover:border-[#3dccc7]">Reset</button>
								<button type="submit" name="update_profile" id="save-profile-btn"
									class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold text-white bg-[#3dccc7] hover:bg-[#68d8d6] transition">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
									</svg>
									<span>Save changes</span>
								</button>
							</div>
						</form>
					</section>

					<!-- Security Section -->
					<section class="card rounded-2xl shadow-lg p-6 space-y-6">
						<div class="flex items-start justify-between flex-wrap gap-4">
							<div>
								<h2 class="text-2xl font-semibold">Security</h2>
								<p class="text-sm opacity-70 mt-2">Protect your account with a strong password.</p>
							</div>
							<div class="text-right">
								<p class="text-xs uppercase tracking-widest opacity-70">Password strength</p>
								<p id="password-strength-text" class="text-sm font-semibold text-emerald-500">Secure</p>
							</div>
						</div>

						<form id="password-form" method="POST" action="setting.php" class="space-y-5">
							<div class="grid grid-cols-1 gap-4">
								<div class="input-with-icon">
									<label for="current_password" class="block text-sm font-medium mb-2">Current password</label>
									<input type="password" id="current_password" name="current_password" required
										class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7] transition" />
									<button type="button" class="toggle-password p-2 opacity-60 hover:opacity-100 transition"
										data-target="current_password">
										<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
												d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
												d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
										</svg>
									</button>
								</div>

								<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
									<div class="input-with-icon">
										<label for="new_password" class="block text-sm font-medium mb-2">New password</label>
										<input type="password" id="new_password" name="new_password" required
											class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7] transition" />
										<button type="button" class="toggle-password p-2 opacity-60 hover:opacity-100 transition"
											data-target="new_password">
											<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
													d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
													d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
											</svg>
										</button>
										<!-- Password Strength Indicator -->
										<div class="mt-2">
											<div id="password-strength-bar" class="password-strength-bar"></div>
											<p id="password-strength-msg" class="text-xs mt-1 opacity-70"></p>
										</div>
									</div>
									<div class="input-with-icon">
										<label for="confirm_password" class="block text-sm font-medium mb-2">Confirm password</label>
										<input type="password" id="confirm_password" name="confirm_password" required
											class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7] transition" />
										<button type="button" class="toggle-password p-2 opacity-60 hover:opacity-100 transition"
											data-target="confirm_password">
											<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
													d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
													d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
											</svg>
										</button>
										<p id="password-match-msg" class="text-xs mt-1 hidden"></p>
									</div>
								</div>

								<!-- Password Requirements -->
								<div class="card rounded-lg border border-blue-200 bg-blue-50/60 dark:bg-blue-900/20 px-4 py-3">
									<p class="text-sm font-medium mb-2">Password must contain:</p>
									<ul class="space-y-1 text-xs">
										<li id="req-length" class="flex items-center gap-2 opacity-60">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
											</svg>
											At least 8 characters
										</li>
										<li id="req-uppercase" class="flex items-center gap-2 opacity-60">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
											</svg>
											One uppercase letter
										</li>
										<li id="req-lowercase" class="flex items-center gap-2 opacity-60">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
											</svg>
											One lowercase letter
										</li>
										<li id="req-number" class="flex items-center gap-2 opacity-60">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
											</svg>
											One number
										</li>
									</ul>
								</div>

								<div class="flex items-center card rounded-lg border border-amber-200 bg-amber-50/60 dark:bg-amber-900/20 px-4 py-3 gap-3">
									<svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
									</svg>
									<p class="text-xs opacity-80">We currently store passwords as plain text. Use a unique password for NutriTrack.</p>
								</div>
							</div>
							<div class="flex flex-wrap gap-3 justify-end">
								<button type="submit" name="update_password" id="update-password-btn"
									class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold text-white bg-[#3dccc7] hover:bg-[#68d8d6] transition">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
									</svg>
									<span>Update password</span>
								</button>
							</div>
						</form>
					</section>
				</div>

				<!-- Sidebar -->
				<div class="space-y-8">
					<!-- Data & Privacy Section -->
					<section class="card rounded-2xl shadow-lg p-6 space-y-4">
						<div>
							<h2 class="text-xl font-semibold">Data & privacy</h2>
							<p class="text-sm opacity-70 mt-1">Control how NutriTrack uses your data.</p>
						</div>
						<ul class="space-y-4 text-sm">
							<li class="flex justify-between items-center gap-3">
								<div class="flex-1">
									<p class="font-medium">Download activity</p>
									<p class="opacity-70 text-xs">Export your logs as CSV.</p>
								</div>
								<button id="export-btn"
									class="px-4 py-2 rounded-lg border border-neutral-200 dark:border-neutral-700 text-xs font-semibold hover:border-[#3dccc7] transition flex-shrink-0">Export</button>
							</li>
							<li class="flex justify-between items-center gap-3">
								<div class="flex-1">
									<p class="font-medium">Session timeout</p>
									<p class="opacity-70 text-xs">Auto-sign out after inactivity.</p>
								</div>
								<select id="timeout-select"
									class="card rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3dccc7] flex-shrink-0">
									<option value="15">15 min</option>
									<option value="30" selected>30 min</option>
									<option value="60">1 hour</option>
								</select>
							</li>
							<li class="flex justify-between items-center gap-3">
								<div class="flex-1">
									<p class="font-medium">Account deletion</p>
									<p class="opacity-70 text-xs">Permanently remove your data.</p>
								</div>
								<button id="delete-request"
									class="px-4 py-2 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 transition flex-shrink-0">Request</button>
							</li>
						</ul>
					</section>

					<!-- Active Sessions Section -->
					<section class="card rounded-2xl shadow-lg p-6 space-y-4">
						<div>
							<h2 class="text-xl font-semibold">Active sessions</h2>
							<p class="text-sm opacity-70 mt-1">Devices currently logged in.</p>
						</div>
						<ul class="space-y-3 text-sm">
							<li class="flex items-start gap-3 p-3 card rounded-lg">
								<svg class="w-5 h-5 opacity-60 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
								</svg>
								<div class="flex-1">
									<p class="font-medium">Windows PC - Chrome</p>
									<p class="opacity-60 text-xs">Jember, Indonesia • Current session</p>
								</div>
								<span class="text-xs text-emerald-500 flex-shrink-0">Active</span>
							</li>
						</ul>
					</section>

					<!-- Notifications Section -->
					<section class="card rounded-2xl shadow-lg p-6 space-y-4">
						<div>
							<h2 class="text-xl font-semibold">Notifications</h2>
							<p class="text-sm opacity-70 mt-1">Manage your notification preferences.</p>
						</div>
						<ul class="space-y-3 text-sm">
							<li class="flex justify-between items-center">
								<div>
									<p class="font-medium">Email notifications</p>
									<p class="opacity-70 text-xs">Weekly summary reports</p>
								</div>
								<label class="relative inline-flex items-center cursor-pointer">
									<input type="checkbox" checked class="sr-only peer">
									<div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#3dccc7] dark:peer-focus:ring-[#3dccc7] rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#3dccc7]"></div>
								</label>
							</li>
						</ul>
					</section>
				</div>
			</div>
		</div>
	</main>

	<!-- Delete Account Confirmation Modal -->
	<div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
		<div class="modal-backdrop absolute inset-0" id="modal-backdrop"></div>
		<div class="card rounded-2xl shadow-2xl max-w-md w-full p-6 relative z-10 fade-in">
			<div class="flex items-start gap-4">
				<div class="p-3 rounded-full bg-red-100 dark:bg-red-900/30">
					<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
					</svg>
				</div>
				<div class="flex-1">
					<h3 class="text-lg font-semibold">Delete Account</h3>
					<p class="text-sm opacity-70 mt-2">Are you sure you want to delete your account? This action cannot be undone and all your data will be permanently removed.</p>
					<div class="flex gap-3 mt-6">
						<button id="cancel-delete" class="flex-1 px-4 py-2 rounded-lg border border-neutral-200 dark:border-neutral-700 text-sm font-medium hover:border-[#3dccc7] transition">
							Cancel
						</button>
						<button id="confirm-delete" class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition">
							Delete Account
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Theme Switcher -->
	<div id="theme-switcher" class="fixed bottom-6 left-6 z-50 flex flex-col p-1 rounded-full card shadow-sm">
		<button id="system-btn" class="flex items-center justify-center p-2 rounded-full transition-colors duration-200">
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
				<path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
			</svg>
		</button>
		<button id="light-btn" class="flex items-center justify-center p-2 rounded-full transition-colors duration-200">
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
				<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
			</svg>
		</button>
		<button id="dark-btn" class="flex items-center justify-center p-2 rounded-full transition-colors duration-200">
			<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
			</svg>
		</button>
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

		// === Toast notification ===
		function showToast(message, type = 'success') {
			const toast = document.createElement('div');
			toast.className = `toast card rounded-lg shadow-lg px-4 py-3 flex items-center gap-3 ${type === 'success' ? 'bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700' : 'bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700'}`;

			const icon = type === 'success' ?
				'<svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>' :
				'<svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';

			toast.innerHTML = `
				${icon}
				<span class="text-sm ${type === 'success' ? 'text-emerald-600 dark:text-emerald-200' : 'text-red-600 dark:text-red-200'}">${message}</span>
			`;

			document.body.appendChild(toast);

			setTimeout(() => {
				toast.style.animation = 'slideOutRight 0.3s ease';
				setTimeout(() => toast.remove(), 300);
			}, 3000);
		}

		// === Password visibility toggle ===
		document.querySelectorAll('.toggle-password').forEach(button => {
			button.addEventListener('click', function() {
				const targetId = this.getAttribute('data-target');
				const input = document.getElementById(targetId);
				const icon = this.querySelector('svg');

				if (input.type === 'password') {
					input.type = 'text';
					icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
				} else {
					input.type = 'password';
					icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
				}
			});
		});

		// === Password strength checker ===
		const newPasswordInput = document.getElementById('new_password');
		const strengthBar = document.getElementById('password-strength-bar');
		const strengthMsg = document.getElementById('password-strength-msg');
		const strengthText = document.getElementById('password-strength-text');

		const reqLength = document.getElementById('req-length');
		const reqUppercase = document.getElementById('req-uppercase');
		const reqLowercase = document.getElementById('req-lowercase');
		const reqNumber = document.getElementById('req-number');

		function checkPasswordStrength(password) {
			let strength = 0;
			const requirements = {
				length: password.length >= 8,
				uppercase: /[A-Z]/.test(password),
				lowercase: /[a-z]/.test(password),
				number: /[0-9]/.test(password)
			};

			// Update requirement indicators
			updateRequirement(reqLength, requirements.length);
			updateRequirement(reqUppercase, requirements.uppercase);
			updateRequirement(reqLowercase, requirements.lowercase);
			updateRequirement(reqNumber, requirements.number);

			// Calculate strength
			Object.values(requirements).forEach(met => {
				if (met) strength++;
			});

			// Update UI
			if (password.length === 0) {
				strengthBar.className = 'password-strength-bar';
				strengthMsg.textContent = '';
				strengthText.textContent = 'Not set';
				strengthText.className = 'text-sm font-semibold opacity-60';
			} else if (strength <= 2) {
				strengthBar.className = 'password-strength-bar strength-weak';
				strengthMsg.textContent = 'Weak password';
				strengthMsg.style.color = '#ef4444';
				strengthText.textContent = 'Weak';
				strengthText.className = 'text-sm font-semibold text-red-500';
			} else if (strength === 3) {
				strengthBar.className = 'password-strength-bar strength-medium';
				strengthMsg.textContent = 'Medium strength';
				strengthMsg.style.color = '#f59e0b';
				strengthText.textContent = 'Medium';
				strengthText.className = 'text-sm font-semibold text-amber-500';
			} else {
				strengthBar.className = 'password-strength-bar strength-strong';
				strengthMsg.textContent = 'Strong password';
				strengthMsg.style.color = '#10b981';
				strengthText.textContent = 'Strong';
				strengthText.className = 'text-sm font-semibold text-emerald-500';
			}
		}

		function updateRequirement(element, met) {
			const icon = element.querySelector('svg');
			if (met) {
				element.classList.remove('opacity-60');
				element.classList.add('text-emerald-500');
				icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
			} else {
				element.classList.add('opacity-60');
				element.classList.remove('text-emerald-500');
				icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
			}
		}

		newPasswordInput?.addEventListener('input', (e) => {
			checkPasswordStrength(e.target.value);
		});

		// === Password match validation ===
		const confirmPasswordInput = document.getElementById('confirm_password');
		const matchMsg = document.getElementById('password-match-msg');

		function checkPasswordMatch() {
			const newPass = newPasswordInput.value;
			const confirmPass = confirmPasswordInput.value;

			if (confirmPass.length === 0) {
				matchMsg.classList.add('hidden');
				return;
			}

			matchMsg.classList.remove('hidden');
			if (newPass === confirmPass) {
				matchMsg.textContent = 'Passwords match';
				matchMsg.className = 'text-xs mt-1 text-emerald-500';
			} else {
				matchMsg.textContent = 'Passwords do not match';
				matchMsg.className = 'text-xs mt-1 text-red-500';
			}
		}

		confirmPasswordInput?.addEventListener('input', checkPasswordMatch);
		newPasswordInput?.addEventListener('input', checkPasswordMatch);

		// === Email validation ===
		const emailInput = document.getElementById('email');
		const emailValidation = document.getElementById('email-validation');

		emailInput?.addEventListener('blur', function() {
			const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			if (this.value && !emailRegex.test(this.value)) {
				emailValidation.textContent = 'Please enter a valid email address';
				emailValidation.className = 'text-xs mt-1 text-red-500';
				emailValidation.classList.remove('hidden');
			} else {
				emailValidation.classList.add('hidden');
			}
		});

		// === Form submissions with loading states ===
		const profileForm = document.getElementById('profile-form');
		const saveProfileBtn = document.getElementById('save-profile-btn');

		profileForm?.addEventListener('submit', function(e) {
			e.preventDefault();
			const originalContent = saveProfileBtn.innerHTML;
			saveProfileBtn.disabled = true;
			saveProfileBtn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Saving...</span>';

			setTimeout(() => {
				saveProfileBtn.disabled = false;
				saveProfileBtn.innerHTML = originalContent;
				showToast('Profile updated successfully!', 'success');
				// Here you would actually submit the form
			}, 1500);
		});

		const passwordForm = document.getElementById('password-form');
		const updatePasswordBtn = document.getElementById('update-password-btn');

		passwordForm?.addEventListener('submit', function(e) {
			e.preventDefault();

			// Validate password match
			if (newPasswordInput.value !== confirmPasswordInput.value) {
				showToast('Passwords do not match!', 'error');
				return;
			}

			const originalContent = updatePasswordBtn.innerHTML;
			updatePasswordBtn.disabled = true;
			updatePasswordBtn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Updating...</span>';

			setTimeout(() => {
				updatePasswordBtn.disabled = false;
				updatePasswordBtn.innerHTML = originalContent;
				showToast('Password updated successfully!', 'success');
				passwordForm.reset();
				checkPasswordStrength('');
				// Here you would actually submit the form
			}, 1500);
		});

		// === Avatar upload ===
		const avatarUpload = document.getElementById('avatar-upload');
		const avatarPreview = document.getElementById('avatar-preview');
		const removeAvatarBtn = document.getElementById('remove-avatar');

		avatarUpload?.addEventListener('change', function(e) {
			const file = e.target.files[0];
			if (file && file.type.startsWith('image/')) {
				if (file.size > 2 * 1024 * 1024) {
					showToast('Image size must be less than 2MB', 'error');
					return;
				}
				const reader = new FileReader();
				reader.onload = function(e) {
					avatarPreview.src = e.target.result;
					showToast('Avatar uploaded successfully!', 'success');
				};
				reader.readAsDataURL(file);
			}
		});

		removeAvatarBtn?.addEventListener('click', function() {
			avatarPreview.src = 'https://ui-avatars.com/api/?name=Cinamon+Bun&size=120&background=3dccc7&color=fff';
			avatarUpload.value = '';
			showToast('Avatar removed', 'success');
		});

		// === Delete account modal ===
		const deleteRequestBtn = document.getElementById('delete-request');
		const deleteModal = document.getElementById('delete-modal');
		const modalBackdrop = document.getElementById('modal-backdrop');
		const cancelDeleteBtn = document.getElementById('cancel-delete');
		const confirmDeleteBtn = document.getElementById('confirm-delete');

		deleteRequestBtn?.addEventListener('click', () => {
			deleteModal.classList.remove('hidden');
		});

		function closeModal() {
			deleteModal.classList.add('hidden');
		}

		modalBackdrop?.addEventListener('click', closeModal);
		cancelDeleteBtn?.addEventListener('click', closeModal);

		confirmDeleteBtn?.addEventListener('click', () => {
			showToast('Account deletion request submitted. We will contact you via email.', 'success');
			closeModal();
		});

		// === Export data ===
		document.getElementById('export-btn')?.addEventListener('click', () => {
			showToast('Preparing your data export. You will receive an email shortly.', 'success');
		});

		// === Session timeout ===
		const timeoutSelect = document.getElementById('timeout-select');
		timeoutSelect?.addEventListener('change', () => {
			const minutes = timeoutSelect.value;
			showToast(`Session timeout updated to ${minutes} minutes`, 'success');
		});
	</script>
</body>

</html>