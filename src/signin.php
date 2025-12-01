<?php
session_start();

include 'config.php';
include 'db-functions.php'; 

$error = ''; 

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $user = getUserByUsername($username);

    if ($user !== null) {
        
        if ($password === $user['password']) {

            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level'];

            if ($user['level'] === 'admin') {
                header("Location: dashboard.php");
            } 
            else if ($user['level'] === 'user') {
                header("Location: index.php");
            } 
            else {
                header("Location: index.php");
            }
            exit;
        }
    }

    $error = "Username atau password salah.";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./output.css" rel="stylesheet">
    <title>NutriTrack - Sign In</title>
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

        <div class="text-center">
            <div class="mx-auto h-12 w-12 bg-[#3dccc7] rounded-full flex items-center justify-center shadow-lg">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold">
                Sign in to your account
            </h2>
            <p class="mt-2 text-sm opacity-60">
                Welcome back! Please enter your details.
            </p>
        </div>

        <div class="card rounded-xl shadow-xl p-8">
            <form class="space-y-4" action="signin.php" method="POST">
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
                            placeholder="Enter your username" value="<?php echo htmlspecialchars($username ?? ''); ?>">
                    </div>
                </div>

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
                            placeholder="Enter your password">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" name="login"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-[#3dccc7] hover:bg-[#68d8d6] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-400 transition duration-200 transform hover:scale-105">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-white group-hover:text-gray-200" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        Sign in
                    </button>
                </div>

                <?php if (!empty($error)) { ?>
                    <div class="text-center text-red-600 text-sm">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php } ?>

                <div>
                    <a href="index.php"
                        class="group relative w-full flex justify-center py-3 px-4 card text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-200">
                        Kembali ke Beranda
                    </a>
                </div>

                <div class="text-center">
                    <span class="text-sm">
                        Don't have an account?
                        <a href="signup.php" class="font-medium">
                            Sign up here
                        </a>
                    </span>
                </div>
            </form>
        </div>

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
    </div>

    <script>
        // === Theme Switcher Logic (Tetap sama) ===
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