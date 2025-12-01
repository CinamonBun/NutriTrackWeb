<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}

// Pastikan Anda sudah menyertakan file koneksi database
include 'config.php';
// WAJIB: Sertakan file fungsi database
include 'db-functions.php'; 

$signup_error = ''; // Variabel untuk pesan error

if (isset($_POST['signup'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : ''; // Variabel ini tidak digunakan di DB, tapi tidak apa-apa disimpan

    // ========== 1. VALIDASI DATA INPUT ==========
    if ($password !== $confirm_password) {
        $signup_error = "Password and confirm password do not match";
    } elseif (strlen($password) < 6) {
        $signup_error = "Password must be at least 6 characters long";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $signup_error = "Invalid email address";
    } elseif (strlen($username) < 4) {
        $signup_error = "Username must be at least 4 characters long";
    } elseif (strlen($fullname) < 3) {
        $signup_error = "Full name must be at least 3 characters long";
    } else {
        
        // ========== 2. CEK KEUNIKAN DI DATABASE ==========
        
        // A. Cek Username
        $safe_username = escape($username);
        $sql_user_check = "SELECT id FROM users WHERE username = '$safe_username' LIMIT 1";
        $result_user = dbQuery($sql_user_check);
        
        if ($result_user && mysqli_num_rows($result_user) > 0) {
            $signup_error = "Username already taken";
        } else {
            // B. Cek Email (Mengganti supabaseRequest)
            $safe_email = escape($email);
            $sql_email_check = "SELECT id FROM users WHERE email = '$safe_email' LIMIT 1";
            $result_email = dbQuery($sql_email_check);

            if ($result_email && mysqli_num_rows($result_email) > 0) {
                $signup_error = "Email already registered";
            } else {
                // ========== 3. BUAT PENGGUNA BARU ==========
                
                // Siapkan data pengguna. Sertakan nilai default untuk kolom wajib yang tidak ada di formulir.
                $userData = [
                    'fullname' => $fullname,
                    'email' => $email,
                    'username' => $username,
                    'password' => $password, 
                    // Nilai default agar insert berhasil (harus dilengkapi user setelah login)
                    'height' => 0, 
                    'weight' => 0, 
                    'age' => 0, 
                    'gender' => 'Other', 
                    'daily_calories_target' => 2000, 
                    'level' => 'user' 
                ];

                $result = createUser($userData); // Fungsi ini menangani password_hash

                if ($result['status'] == 201) {
                    $_SESSION['signup_success'] = "Account created successfully! Please sign in.";
                    header("Location: signin.php");
                    exit;
                } else {
                    // Tampilkan pesan error database jika gagal
                    $db_error = $result['data']['error'] ?? 'Unknown error';
                    $signup_error = "Failed to create account. Database Error: " . $db_error;
                }
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
    <link href="./output.css" rel="stylesheet">
    <title>NutriTrack - Sign Up</title>
    <style>
        body {
            /* font-family: 'Inter', sans-serif; */
            font-family: 'Plus Jakarta Sans', sans-serif;
            /* font-family: "Geist", sans-serif; */
        }

        .custom-bg {
            background-color: #f5f5f5;
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

<body class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">

        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-12 w-12 bg-[#3dccc7] rounded-full flex items-center justify-center shadow-lg">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold">
                Create your account
            </h2>
            <p class="mt-2 text-sm opacity-60">
                Join us today! Fill in your details to get started.
            </p>
        </div>

        <!-- Sign Up Form -->
        <div class="card rounded-xl shadow-xl p-8">
            <form class="space-y-6" action="signup.php" method="POST">

                <!-- Full Name -->
                <div>
                    <label for="fullname" class="block text-sm font-medium mb-2">
                        Full Name
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input id="fullname" name="fullname" type="text" required
                            class="block w-full pl-10 pr-3 py-3 card rounded-lg focus:outline-none focus:ring-2 transition duration-200"
                            placeholder="Enter your full name">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" required
                            class="block w-full pl-10 pr-3 py-3 card rounded-lg focus:outline-none focus:ring-2 transition duration-200"
                            placeholder="Enter your email address">
                    </div>
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium mb-2">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input id="username" name="username" type="text" required
                            class="block w-full pl-10 pr-3 py-3 card rounded-lg focus:outline-none focus:ring-2 transition duration-200"
                            placeholder="Choose a username">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="block w-full pl-10 pr-3 py-3 card rounded-lg focus:outline-none focus:ring-2 transition duration-200"
                            placeholder="Create a password">
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="confirm-password" class="block text-sm font-medium mb-2">
                        Confirm Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <input id="confirm-password" name="confirm_password" type="password" required
                            class="block w-full pl-10 pr-3 py-3 card rounded-lg focus:outline-none focus:ring-2 transition duration-200"
                            placeholder="Confirm your password">
                    </div>
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block text-sm font-medium mb-2">
                        Phone Number (Optional)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <input id="phone" name="phone" type="tel"
                            class="block w-full pl-10 pr-3 py-3 card rounded-lg focus:outline-none focus:ring-2 transition duration-200"
                            placeholder="Enter your phone number">
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="">
                            I agree to the
                            <a href="#" class="font-medium">
                                Terms and Conditions
                            </a>
                            and
                            <a href="#" class="font-medium">
                                Privacy Policy
                            </a>
                        </label>
                    </div>
                </div>

                <!-- Newsletter Subscription -->
                <div class="flex items-center">
                    <input id="newsletter" name="newsletter" type="checkbox" class="h-4 w-4 rounded">
                    <label for="newsletter" class="ml-2 block text-sm">
                        Subscribe to our newsletter for updates and offers
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" name="signup"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-[#3dccc7] hover:bg-[#68d8d6] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-400 transition duration-200 transform hover:scale-105">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-white group-hover:text-gray-200" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        Create Account
                    </button>
                </div>

                <?php if (!empty($signup_error)) { ?>
                    <div class="text-center text-red-600 text-sm">
                        <?php echo htmlspecialchars($signup_error); ?>
                    </div>
                <?php } ?>

                <!-- Login Link -->
                <div class="text-center">
                    <span class="text-sm">
                        Already have an account?
                        <a href="signin.php" class="font-medium">
                            Sign in here
                        </a>
                    </span>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center">
            <p class="text-sm text-gray-400">
                © 2025 NutriTrack. All rights reserved.
            </p>
        </div>
    </div>

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
        // Password confirmation validation
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm-password');

            // Password confirmation validation
            confirmPassword.addEventListener('input', function() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Passwords do not match');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            });

            password.addEventListener('input', function() {
                if (confirmPassword.value && password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Passwords do not match');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            });
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
    </script>
</body>

</html>