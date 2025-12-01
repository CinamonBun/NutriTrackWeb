<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit;
}

// Pastikan Anda sudah menyertakan file koneksi database dan fungsi
include 'config.php';
include 'db-functions.php';

$authUsername = $_SESSION['username'];

// Check if user is admin (Asumsi fungsi ini sudah didefinisikan)
requireAdmin($authUsername);
$createError = '';
$listError = '';
$createFormData = [
    'fullname' => '',
    'email' => '',
    'username' => '',
    'password' => '',
    'phone' => '',
    'level' => ''
];
$searchTerm = trim($_GET['q'] ?? '');
$flash = $_SESSION['user_flash'] ?? null;
unset($_SESSION['user_flash']);

/**
 * Store flash message in session and redirect to avoid resubmits.
 */
function redirectWithFlash($type, $message, $target = 'user.php')
{
    $_SESSION['user_flash'] = [
        'type' => $type,
        'message' => $message
    ];
    header("Location: {$target}");
    exit;
}

/**
 * Build SQL WHERE clause for the search term.
 * Menggantikan buildSearchFilter Supabase.
 */
function buildSearchFilter($term)
{
    if (empty($term)) {
        return '';
    }

    // Sanitize term for LIKE query
    // Gunakan fungsi escape dari db_functions.php
    $safe_term = '%' . escape(trim($term)) . '%';

    // Build the SQL WHERE clause for searching username, fullname, or email
    $filter = " WHERE username LIKE '$safe_term' OR fullname LIKE '$safe_term' OR email LIKE '$safe_term' ";

    return $filter;
}

/**
 * Get the current page (with query) to preserve filters after redirects.
 */
function currentPageUrl()
{
    $uri = $_SERVER['REQUEST_URI'] ?? 'user.php';
    $path = basename(parse_url($uri, PHP_URL_PATH) ?: 'user.php');
    $query = parse_url($uri, PHP_URL_QUERY);
    return $path . ($query ? '?' . $query : '');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create_user') {
        $redirectTarget = currentPageUrl();
        $createFormData['fullname'] = trim($_POST['fullname'] ?? '');
        $createFormData['email'] = trim($_POST['email'] ?? '');
        $createFormData['username'] = trim($_POST['username'] ?? '');
        $createFormData['password'] = trim($_POST['password'] ?? '');
        $createFormData['phone'] = trim($_POST['phone'] ?? '');
        $createFormData['level'] = trim($_POST['level'] ?? 'user'); // Default to 'user'

        if (strlen($createFormData['fullname']) < 3) {
            $createError = 'Full name must be at least 3 characters.';
        } elseif (!filter_var($createFormData['email'], FILTER_VALIDATE_EMAIL)) {
            $createError = 'Please provide a valid email address.';
        } elseif (strlen($createFormData['username']) < 4) {
            $createError = 'Username must be at least 4 characters.';
        } elseif (strlen($createFormData['password']) < 6) {
            $createError = 'Password must be at least 6 characters.';
        } else {
            // 1. Cek Username
            $existingUsername = getUserByUsername($createFormData['username']);
            if ($existingUsername !== null) {
                $createError = 'Username already exists.';
            } else {
                // 2. Cek Email (Menggantikan supabaseRequest)
                $safe_email = escape($createFormData['email']);
                $sql_email_check = "SELECT id FROM users WHERE email = '$safe_email' LIMIT 1";
                $result_email_check = dbQuery($sql_email_check);

                if ($result_email_check && mysqli_num_rows($result_email_check) > 0) {
                    $createError = 'Email already in use.';
                } else {
                    // 3. Buat Pengguna Baru
                    $payload = [
                        'fullname' => $createFormData['fullname'],
                        'email' => $createFormData['email'],
                        'username' => $createFormData['username'],
                        'password' => $createFormData['password'],
                        'phone' => $createFormData['phone'],
                        'level' => $createFormData['level'],
                        // Nilai default untuk kolom wajib yang tidak ada di form admin
                        'height' => 0,
                        'weight' => 0,
                        'age' => 0,
                        'gender' => 'Other',
                        'daily_calories_target' => 2000,
                    ];

                    // Asumsi createUser menangani hashing password dan insert ke DB
                    $result = createUser($payload);

                    if ($result['status'] === 201) {
                        redirectWithFlash('success', 'User created successfully.', $redirectTarget);
                    } else {
                        $db_error = $result['data']['error'] ?? 'Unknown database error';
                        $createError = 'Failed to create user. Database Error: ' . $db_error;
                    }
                }
            }
        }
    } elseif ($action === 'update_user') {
        $redirectTarget = currentPageUrl();
        $usernameToUpdate = trim($_POST['username'] ?? '');
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $newPassword = trim($_POST['password'] ?? '');
        $level = trim($_POST['level'] ?? '');

        if ($usernameToUpdate === '') {
            redirectWithFlash('error', 'Missing user reference.', $redirectTarget);
        }

        $existingUser = getUserByUsername($usernameToUpdate);
        if (!$existingUser) {
            redirectWithFlash('error', 'User not found.', $redirectTarget);
        }

        if (strlen($fullname) < 3) {
            redirectWithFlash('error', 'Full name must be at least 3 characters.', $redirectTarget);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redirectWithFlash('error', 'Please provide a valid email address.', $redirectTarget);
        }

        // 1. Cek Email Unik (Menggantikan supabaseRequest)
        if ($email !== ($existingUser['email'] ?? '')) {
            $safe_email = escape($email);
            $safe_username_to_update = escape($usernameToUpdate);

            // Cek apakah email sudah digunakan oleh pengguna lain
            $sql_email_check = "SELECT username FROM users WHERE email = '$safe_email' AND username != '$safe_username_to_update' LIMIT 1";
            $result_email_check = dbQuery($sql_email_check);

            if ($result_email_check && mysqli_num_rows($result_email_check) > 0) {
                redirectWithFlash('error', 'Email already in use by another user.', $redirectTarget);
            }
        }

        $updateData = [
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'level' => $level
        ];

        if ($newPassword !== '') {
            if (strlen($newPassword) < 6) {
                redirectWithFlash('error', 'Password must be at least 6 characters.', $redirectTarget);
            }
            // Asumsi updateUser akan menangani hashing password jika ada
            $updateData['password'] = $newPassword;
        }

        // 2. Update Pengguna
        $response = updateUser($usernameToUpdate, $updateData);
        if ($response['status'] === 200) {
            redirectWithFlash('success', 'User updated successfully.', $redirectTarget);
        } else {
            $db_error = $response['data']['error'] ?? 'Unknown database error';
            redirectWithFlash('error', 'Failed to update user. Error: ' . $db_error, $redirectTarget);
        }
    } elseif ($action === 'delete_user') {
        $redirectTarget = currentPageUrl();
        $targetUsername = trim($_POST['username'] ?? '');
        if ($targetUsername === '') {
            redirectWithFlash('error', 'Missing user reference.', $redirectTarget);
        } elseif ($targetUsername === $authUsername) {
            redirectWithFlash('error', 'You cannot delete your own account while signed in.', $redirectTarget);
        } else {
            // 1. Hapus Pengguna
            $deleteResponse = deleteUser($targetUsername);

            if ($deleteResponse['status'] >= 200 && $deleteResponse['status'] < 300) {
                redirectWithFlash('success', 'User deleted successfully.', $redirectTarget);
            } else {
                $db_error = $deleteResponse['data']['error'] ?? 'Unknown database error';
                redirectWithFlash('error', 'Failed to delete user. Error: ' . $db_error, $redirectTarget);
            }
        }
    }
}

// =========================================================
// Fetch user list for the table (Menggantikan supabaseRequest)
// =========================================================

// 1. Buat klausa WHERE
$searchFilterSql = buildSearchFilter($searchTerm);

// 2. Bangun kueri SQL
$sql_fetch_users = "
    SELECT id, fullname, email, username, phone, level, created_at 
    FROM users 
    $searchFilterSql 
    ORDER BY created_at DESC
";

// 3. Eksekusi kueri
$result_users = dbQuery($sql_fetch_users);
$users = [];

if ($result_users) {
    while ($row = mysqli_fetch_assoc($result_users)) {
        $users[] = $row;
    }
    mysqli_free_result($result_users);
} else {
    $listError = 'Failed to load users from database.';
}

// Variabel $users, $listError, dan $createError siap digunakan di bagian HTML
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriTrack - User Management</title>
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

        .modal-panel {
            transform: translateY(12px);
            opacity: 0;
        }

        .modal-panel.show {
            transform: translateY(0);
            opacity: 1;
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
                    <li><a href="user.php" class="font-semibold text-[#3dccc7]">User</a></li>
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
                            class="block text-base font-semibold text-[#3dccc7] transition-colors duration-200">User</a>
                        <a href="season.php"
                            class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Season</a>
                        <a href="meal.php"
                            class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Meal</a>
                        <a href="food.php"
                            class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Food</a>
                        <a href="daily.php"
                            class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Daily</a>
                        <a href="setting.php"
                            class="block text-base font-medium transition-colors duration-200 hover:text-[#3dccc7]">Settings</a>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- Search Bar -->
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <p class="text-sm uppercase tracking-widest opacity-60">Users</p>
                    <h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Manage your community</h1>
                    <p class="mt-2 text-base opacity-80">Invite, update, or remove accounts directly from this panel.
                    </p>
                </div>
                <div class="w-full md:w-auto">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <input type="text" id="search-input" name="q"
                                value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search"
                                class="w-full card px-4 py-3 rounded-lg pl-10 focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
                            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 opacity-60" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($flash) { ?>
                <div
                    class="rounded-lg px-4 py-3 fade-in <?php echo $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200'; ?>">
                    <?php echo htmlspecialchars($flash['message']); ?>
                </div>
            <?php } ?>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

                <!-- Add User Form -->
                <div class="space-y-8 xl:col-span-1">
                    <section class="card rounded-2xl shadow-lg p-6 space-y-6">
                        <div>
                            <h2 class="text-2xl font-semibold">Add new user</h2>
                            <p class="text-sm opacity-70 mt-1">Create an account manually for a teammate.</p>
                        </div>
                        <?php if ($createError) { ?>
                            <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600 fade-in">
                                <?php echo htmlspecialchars($createError); ?>
                            </div>
                        <?php } ?>
                        <form method="POST" action="user.php" class="space-y-4">
                            <input type="hidden" name="action" value="create_user" />
                            <div>
                                <label for="create_fullname" class="block text-sm font-medium mb-2">Full name</label>
                                <input type="text" id="create_fullname" name="fullname"
                                    value="<?php echo htmlspecialchars($createFormData['fullname']); ?>" required
                                    class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
                            </div>
                            <div>
                                <label for="create_email" class="block text-sm font-medium mb-2">Email</label>
                                <input type="email" id="create_email" name="email"
                                    value="<?php echo htmlspecialchars($createFormData['email']); ?>" required
                                    class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
                            </div>
                            <div>
                                <label for="create_username" class="block text-sm font-medium mb-2">Username</label>
                                <input type="text" id="create_username" name="username"
                                    value="<?php echo htmlspecialchars($createFormData['username']); ?>" required
                                    class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
                            </div>
                            <div>
                                <label for="create_password" class="block text-sm font-medium mb-2">Password</label>
                                <input type="password" id="create_password" name="password" required
                                    value="<?php echo htmlspecialchars($createFormData['password']); ?>"
                                    class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
                            </div>
                            <div>
                                <label for="create_phone" class="block text-sm font-medium mb-2">Phone
                                    (optional)</label>
                                <input type="tel" id="create_phone" name="phone"
                                    value="<?php echo htmlspecialchars($createFormData['phone']); ?>"
                                    class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
                            </div>
                            <div>
                                <label for="create_level" class="block text-sm font-medium mb-2">Level</label>
                                <input type="hidden" id="create_level" name="level" value="<?php echo htmlspecialchars($createFormData['level']); ?>" required>
                                <div class="relative w-full font-sans text-sm">
                                    <button type="button" id="create_level_btn" class="group w-full flex justify-between items-center 
                                        card px-4 py-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#3dccc7] 
                                        cursor-pointer transition-all duration-300 ease-in-out
                                        hover:border-[#3dccc7]">
                                        <span id="create_level_display" class="text-sm font-medium">
                                            <?php echo $createFormData['level'] ? ucfirst($createFormData['level']) : 'Select level'; ?>
                                        </span>
                                        <svg id="create_level_icon" class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform duration-300"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="create_level_menu" class="hidden absolute left-0 z-50 mt-2 w-full card rounded-lg shadow-lg dark:shadow-[0_10px_25px_rgba(0,0,0,0.5)] p-1.5 overflow-hidden">
                                        <div data-value="admin" class="create-level-option cursor-pointer rounded-lg py-2.5 px-3.5 mb-1
                                            opacity-80 font-medium hover:bg-neutral-100 dark:hover:bg-[#3a3a3a] 
                                            transition-colors duration-200">
                                            Admin
                                        </div>
                                        <div data-value="user" class="create-level-option cursor-pointer rounded-lg py-2.5 px-3.5 
                                            opacity-80 font-medium hover:bg-neutral-100 dark:hover:bg-[#3a3a3a] 
                                            transition-colors duration-200">
                                            User
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 rounded-lg text-sm font-semibold text-white bg-[#3dccc7] hover:bg-[#68d8d6] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#3dccc7]">
                                Create user
                            </button>
                        </form>
                    </section>
                </div>

                <!-- User List Table -->
                <section class="card rounded-2xl shadow-lg p-6 space-y-6 xl:col-span-2">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold">All users</h2>
                            <p class="text-sm opacity-70 mt-1">Showing <?php echo count($users); ?> record(s).</p>
                        </div>
                    </div>

                    <?php if ($listError) { ?>
                        <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600 fade-in">
                            <?php echo htmlspecialchars($listError); ?>
                        </div>
                    <?php } ?>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm divide-y divide-neutral-300">
                            <thead>
                                <tr class="text-left opacity-70 text-xs uppercase tracking-widest">
                                    <th class="py-3 px-4">Username</th>
                                    <th class="py-3 px-4">Level</th>
                                    <th class="py-3 px-4">Date added</th>
                                    <th class="py-3 px-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)) { ?>
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center opacity-70">
                                            <?php echo $searchTerm === '' ? 'No users found.' : 'No matching users for "' . htmlspecialchars($searchTerm) . '".'; ?>
                                        </td>
                                    </tr>
                                <?php } else { ?>
                                    <?php foreach ($users as $user) {
                                        // Format created_at
                                        $createdAt = $user['created_at'] ?? '-';
                                        if ($createdAt !== '-') {
                                            try {
                                                $date = new DateTime($createdAt);
                                                $createdFormatted = $date->format('d M Y, H:i');
                                            } catch (Exception $e) {
                                                $createdFormatted = $createdAt;
                                            }
                                        } else {
                                            $createdFormatted = '-';
                                        }

                                        // Badge color based on level
                                        $levelBadgeClass = '';
                                        switch (strtolower($user['level'] ?? '')) {
                                            case 'admin':
                                                $levelBadgeClass = 'border-1 border-primary bg-primary/10 backdrop-blur-sm text-primary';
                                                break;
                                            case 'user':
                                                $levelBadgeClass = 'border-1 border-accent bg-accent/10 backdrop-blur-sm text-accent';
                                                break;
                                            default:
                                                $levelBadgeClass = 'bg-neutral-500';
                                        }
                                    ?>
                                        <tr class="border-b border-neutral-200 transition-colors">
                                            <td class="py-3 px-4">
                                                <span class="font-medium">@<?php echo htmlspecialchars($user['username']); ?></span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $levelBadgeClass; ?>">
                                                    <?php echo htmlspecialchars($user['level']); ?>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="text-sm"><?php echo htmlspecialchars($createdFormatted); ?></span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center justify-center gap-2">
                                                    <button type="button"
                                                        class="edit-btn px-3 py-1.5 rounded-lg card text-xs font-semibold hover:border-[#3dccc7]"
                                                        data-username="<?php echo htmlspecialchars($user['username'] ?? '', ENT_QUOTES); ?>"
                                                        data-fullname="<?php echo htmlspecialchars($user['fullname'] ?? '', ENT_QUOTES); ?>"
                                                        data-email="<?php echo htmlspecialchars($user['email'] ?? '', ENT_QUOTES); ?>"
                                                        data-phone="<?php echo htmlspecialchars($user['phone'] ?? '', ENT_QUOTES); ?>"
                                                        data-level="<?php echo htmlspecialchars($user['level'] ?? '', ENT_QUOTES); ?>">
                                                        Edit
                                                    </button>
                                                    <form method="POST"
                                                        action="user.php<?php echo $searchTerm !== '' ? '?q=' . urlencode($searchTerm) : ''; ?>"
                                                        onsubmit="return confirm('Delete user <?php echo htmlspecialchars($user['username']); ?>? This cannot be undone.');">
                                                        <input type="hidden" name="action" value="delete_user" />
                                                        <input type="hidden" name="username"
                                                            value="<?php echo htmlspecialchars($user['username']); ?>" />
                                                        <button type="submit"
                                                            class="px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 border border-red-400 hover:bg-red-50">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <!-- Edit User Modal -->
    <div id="edit-user-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div id="edit-modal-overlay" class="fixed inset-0 backdrop-blur-sm"></div>
        <div class="relative mx-auto py-8 w-full max-w-xl px-4 min-h-full">
            <div class="card rounded-2xl shadow-2xl p-6 fade-in w-full">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-semibold">Edit user</h3>
                        <p class="text-sm opacity-70 mt-1">Update account details and credential options.</p>
                    </div>
                    <button id="edit-modal-close"
                        class="rounded-full p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="edit-user-form" method="POST"
                    action="user.php<?php echo $searchTerm !== '' ? '?q=' . urlencode($searchTerm) : ''; ?>"
                    class="mt-6 space-y-4">
                    <input type="hidden" name="action" value="update_user" />
                    <input type="hidden" name="username" id="edit_username" />
                    <div>
                        <label class="block text-xs uppercase tracking-widest opacity-60 mb-2">Username</label>
                        <div id="edit_username_display"
                            class="px-4 py-3 rounded-lg card bg-neutral-50 dark:bg-neutral-900/60 text-sm font-semibold">
                        </div>
                    </div>
                    <div>
                        <label for="modal_fullname" class="block text-sm font-medium mb-2">Full name</label>
                        <input type="text" id="modal_fullname" name="fullname" required
                            class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
                    </div>
                    <div>
                        <label for="modal_email" class="block text-sm font-medium mb-2">Email</label>
                        <input type="email" id="modal_email" name="email" required
                            class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
                    </div>
                    <div>
                        <label for="modal_phone" class="block text-sm font-medium mb-2">Phone</label>
                        <input type="tel" id="modal_phone" name="phone"
                            class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]" />
                    </div>
                    <div>
                        <label for="modal_level" class="block text-sm font-medium mb-2">Level</label>
                        <input type="hidden" id="modal_level" name="level" value="" required>
                        <div class="relative w-full font-sans text-sm">
                            <button type="button" id="modal_level_btn" class="group w-full flex justify-between items-center 
                                        card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7] 
                                        cursor-pointer transition-all duration-300 ease-in-out
                                        hover:border-[#3dccc7]">
                                <span id="modal_level_display" class="text-sm font-medium">Select level</span>
                                <svg id="modal_level_icon" class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="modal_level_menu" class="hidden absolute left-0 z-[70] mt-2 w-full 
                                card rounded-lg shadow-lg dark:shadow-[0_10px_25px_rgba(0,0,0,0.5)] 
                                shadow-lg dark:shadow-[0_10px_25px_rgba(0,0,0,0.5)] 
                                p-1.5 overflow-hidden">
                                <div data-value="admin" class="modal-level-option cursor-pointer rounded-lg py-2.5 px-3.5 mb-1
                                    opacity-80 font-medium hover:bg-neutral-100 dark:hover:bg-[#3a3a3a] hover:text-gray-900 dark:hover:text-white 
                                    transition-colors duration-200">
                                    Admin
                                </div>
                                <div data-value="user" class="modal-level-option cursor-pointer rounded-lg py-2.5 px-3.5 
                                    opacity-80 font-medium hover:bg-neutral-100 dark:hover:bg-[#3a3a3a] hover:text-gray-900 dark:hover:text-white 
                                    transition-colors duration-200">
                                    User
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="modal_password" class="block text-sm font-medium mb-2">New password
                            (optional)</label>
                        <input type="password" id="modal_password" name="password"
                            class="w-full card px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3dccc7]"
                            placeholder="Leave blank to keep current password" />
                    </div>
                    <div class="flex flex-wrap gap-3 justify-end pt-2">
                        <button type="button" id="edit-cancel-btn"
                            class="px-4 py-2 rounded-lg border border-neutral-200 text-sm font-medium hover:border-[#3dccc7]">
                            Cancel
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold text-white bg-[#3dccc7] hover:bg-[#68d8d6] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#3dccc7]">
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

        // Refresh Button
        document.getElementById('refresh-btn')?.addEventListener('click', () => {
            window.location.reload();
        });

        // Live Search
        const searchInput = document.getElementById('search-input');
        const resetSearchBtn = document.getElementById('reset-search-btn');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.trim();

                // Clear previous timeout
                clearTimeout(searchTimeout);

                // Debounce: wait 500ms after user stops typing
                searchTimeout = setTimeout(() => {
                    const url = new URL(window.location.href);

                    if (searchTerm === '') {
                        url.searchParams.delete('q');
                    } else {
                        url.searchParams.set('q', searchTerm);
                    }

                    // Update URL and reload
                    window.location.href = url.toString();
                }, 500);
            });
        }

        if (resetSearchBtn) {
            resetSearchBtn.addEventListener('click', () => {
                const url = new URL(window.location.href);
                url.searchParams.delete('q');
                window.location.href = url.toString();
            });
        }

        // Custom Dropdown for Level (Add User Form)
        const createLevelBtn = document.getElementById('create_level_btn');
        const createLevelMenu = document.getElementById('create_level_menu');
        const createLevelInput = document.getElementById('create_level');
        const createLevelDisplay = document.getElementById('create_level_display');
        const createLevelIcon = document.getElementById('create_level_icon');
        const createLevelOptions = document.querySelectorAll('.create-level-option');

        if (createLevelBtn && createLevelMenu) {
            createLevelBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = !createLevelMenu.classList.contains('hidden');
                createLevelMenu.classList.toggle('hidden');
                createLevelIcon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
            });

            createLevelOptions.forEach(option => {
                option.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const value = option.getAttribute('data-value');
                    const text = option.textContent.trim();
                    createLevelInput.value = value;
                    createLevelDisplay.textContent = text;
                    createLevelMenu.classList.add('hidden');
                    createLevelIcon.style.transform = 'rotate(0deg)';

                    // Update selected state
                    createLevelOptions.forEach(opt => {
                        opt.classList.remove('bg-[#00d4ff]/15', 'text-[#00d4ff]', 'dark:bg-[#00d4ff]/15', 'dark:text-[#00d4ff]');
                    });
                    option.classList.add('bg-[#00d4ff]/15', 'text-[#00d4ff]', 'dark:bg-[#00d4ff]/15', 'dark:text-[#00d4ff]');
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!createLevelBtn.contains(e.target) && !createLevelMenu.contains(e.target)) {
                    createLevelMenu.classList.add('hidden');
                    createLevelIcon.style.transform = 'rotate(0deg)';
                }
            });
        }

        // Custom Dropdown for Level (Edit User Form)
        const modalLevelBtn = document.getElementById('modal_level_btn');
        const modalLevelMenu = document.getElementById('modal_level_menu');
        const modalLevelInput = document.getElementById('modal_level');
        const modalLevelDisplay = document.getElementById('modal_level_display');
        const modalLevelIcon = document.getElementById('modal_level_icon');
        const modalLevelOptions = document.querySelectorAll('.modal-level-option');

        if (modalLevelBtn && modalLevelMenu) {
            modalLevelBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = !modalLevelMenu.classList.contains('hidden');
                modalLevelMenu.classList.toggle('hidden');
                modalLevelIcon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
            });

            modalLevelOptions.forEach(option => {
                option.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const value = option.getAttribute('data-value');
                    const text = option.textContent.trim();
                    modalLevelInput.value = value;
                    modalLevelDisplay.textContent = text;
                    modalLevelMenu.classList.add('hidden');
                    modalLevelIcon.style.transform = 'rotate(0deg)';

                    // Update selected state
                    modalLevelOptions.forEach(opt => {
                        opt.classList.remove('bg-[#00d4ff]/15', 'text-[#00d4ff]', 'dark:bg-[#00d4ff]/15', 'dark:text-[#00d4ff]');
                    });
                    option.classList.add('bg-[#00d4ff]/15', 'text-[#00d4ff]', 'dark:bg-[#00d4ff]/15', 'dark:text-[#00d4ff]');
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!modalLevelBtn.contains(e.target) && !modalLevelMenu.contains(e.target)) {
                    modalLevelMenu.classList.add('hidden');
                    modalLevelIcon.style.transform = 'rotate(0deg)';
                }
            });
        }

        // Edit User Modal
        const editModal = document.getElementById('edit-user-modal');
        const editForm = document.getElementById('edit-user-form');
        if (editModal && editForm) {
            const editUsernameInput = document.getElementById('edit_username');
            const editUsernameDisplay = document.getElementById('edit_username_display');
            const modalFullname = document.getElementById('modal_fullname');
            const modalEmail = document.getElementById('modal_email');
            const modalPhone = document.getElementById('modal_phone');
            const modalLevelInput = document.getElementById('modal_level');
            const modalLevelDisplay = document.getElementById('modal_level_display');
            const modalPassword = document.getElementById('modal_password');
            const editCancelBtn = document.getElementById('edit-cancel-btn');
            const editCloseBtn = document.getElementById('edit-modal-close');
            const editOverlay = document.getElementById('edit-modal-overlay');

            const openEditModal = (userData) => {
                editModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                editUsernameInput.value = userData.username || '';
                editUsernameDisplay.textContent = '@' + (userData.username || '');
                modalFullname.value = userData.fullname || '';
                modalEmail.value = userData.email || '';
                modalPhone.value = userData.phone || '';
                const levelValue = userData.level || '';
                if (modalLevelInput) modalLevelInput.value = levelValue;
                if (modalLevelDisplay) modalLevelDisplay.textContent = levelValue ? levelValue.charAt(0).toUpperCase() + levelValue.slice(1) : 'Select level';

                // Update selected state in dropdown
                if (modalLevelOptions && modalLevelOptions.length > 0) {
                    modalLevelOptions.forEach(opt => {
                        opt.classList.remove('bg-[#00d4ff]/15', 'text-[#00d4ff]', 'dark:bg-[#00d4ff]/15', 'dark:text-[#00d4ff]');
                        if (opt.getAttribute('data-value') === levelValue) {
                            opt.classList.add('bg-[#00d4ff]/15', 'text-[#00d4ff]', 'dark:bg-[#00d4ff]/15', 'dark:text-[#00d4ff]');
                        }
                    });
                }
                modalPassword.value = '';
            };

            const closeEditModal = () => {
                editModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                modalPassword.value = '';
            };

            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const userData = {
                        username: btn.getAttribute('data-username'),
                        fullname: btn.getAttribute('data-fullname'),
                        email: btn.getAttribute('data-email'),
                        phone: btn.getAttribute('data-phone'),
                        level: btn.getAttribute('data-level'),
                    };
                    openEditModal(userData);
                });
            });

            editCancelBtn?.addEventListener('click', closeEditModal);
            editCloseBtn?.addEventListener('click', closeEditModal);
            editOverlay?.addEventListener('click', closeEditModal);
            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !editModal.classList.contains('hidden')) {
                    closeEditModal();
                }
            });
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