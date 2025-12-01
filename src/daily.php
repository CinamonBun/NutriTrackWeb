<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit;
}

$username = $_SESSION['username'];
include 'config.php';
include 'db-functions.php'; // pastikan ini ditambahkan

// Hanya admin
requireAdmin($username);

$listError = '';
$logError = '';
$users = [];
$dailyLogs = [];

// Ambil profil admin
$user = getUserByUsername($username);
$fullname = $user['fullname'] ?? $username;


// =======================================================
// AMBIL LIST USER (READ-ONLY UNTUK ADMIN)
// =======================================================

$sql_users = "
    SELECT id, fullname, username, email, phone, level, created_at,
           height, weight, waist_size, age, gender, bmi, daily_calories_target
    FROM users 
    ORDER BY created_at DESC
";

$result_users = dbQuery($sql_users);

if ($result_users) {
    while ($row = mysqli_fetch_assoc($result_users)) {
        $users[] = $row;
    }
} else {
    $listError = "Gagal memuat data pengguna.";
}


// =======================================================
// AMBIL DAILY LOG PER USER (Semua user untuk admin dashboard)
// =======================================================

$sql_logs = "
    SELECT 
        d.id,
        d.id_user,
        u.fullname,
        u.username,
        d.tanggal,
        d.consumed_calories,
        d.log_water,
        d.target_met
    FROM daily_calories_history d
    LEFT JOIN users u ON u.id = d.id_user
    ORDER BY d.tanggal DESC
";

$result_logs = dbQuery($sql_logs);

if ($result_logs) {
    while ($row = mysqli_fetch_assoc($result_logs)) {
        $dailyLogs[] = $row;
    }
} else {
    $logError = "Gagal memuat log harian.";
}

?>

<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriTrack - Daily Fix</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
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
                    <li><a href="dashboard.php" class="transition duration-200 hover:scale-105">Dashboard</a></li>
                    <li><a href="user.php" class="transition duration-200 hover:scale-105">User</a></li>
                    <li><a href="season.php" class="transition duration-200 hover:scale-105">Season</a></li>
                    <li><a href="meal.php" class="transition duration-200 hover:scale-105">Meal</a></li>
                    <li><a href="food.php" class="transition duration-200 hover:scale-105">Food</a></li>
                    <li><a href="daily.php" class="font-semibold text-[#3dccc7]">Daily</a></li>
                </ul>
                <div class="hidden md:flex items-center space-x-3">
                    <span class="whitespace-nowrap">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php"
                        class="inline-flex justify-center gap-2 text-white bg-[#3dccc7] hover:bg-[#68d8d6] px-4 py-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors">Logout</a>
                </div>
                <div class="md:hidden">
                    <button id="menu-toggle-btn" type="button" aria-expanded="false"
                        class="p-2 rounded-lg transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#3dccc7]">
                        <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <a href="daily.php" class="block text-base font-medium text-[#3dccc7]">Daily</a>
                    </div>
                    <div class="flex flex-col gap-3 py-3 border-t">
                        <span class="text-sm opacity-70">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <a href="logout.php"
                            class="inline-flex justify-center items-center gap-2 text-sm font-medium rounded-md py-2 px-4 text-white bg-[#3dccc7] hover:bg-[#68d8d6] transition-colors duration-200">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main>
        <section class="pt-28 pb-12 md:pt-36 min-h-[60vh]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-10 space-y-2">
                    <p class="text-sm uppercase tracking-widest opacity-60">Daily Fix</p>
                    <h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Monitor user insights</h1>
                    <p class="text-base opacity-80 max-w-3xl">
                        Panel ini menampilkan snapshot pengguna terbaru lengkap dengan level, kontak, dan waktu registrasi untuk memudahkan pengecekan cepat sebelum membuka halaman User Management.
                    </p>
                </div>

                <!-- Full Width Table -->
                <div class="p-6 rounded-lg shadow-md card">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <div>
                            <p class="text-2xl font-semibold">All users</p>
                            <p class="text-sm opacity-70 mt-1">Showing <?php echo count($users); ?> record(s).</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm divide-y divide-neutral-300">
                            <thead>
                                <tr class="text-left opacity-70 text-xs uppercase tracking-widest">
                                    <th class="py-3 px-4">Username</th>
                                    <th class="py-3 px-4">Level</th>
                                    <th class="py-3 px-4">Created At</th>
                                    <th class="py-3 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($listError)) { ?>
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 text-red-600"><?php echo htmlspecialchars($listError); ?></td>
                                    </tr>
                                <?php } elseif (empty($users)) { ?>
                                    <tr>
                                        <td colspan="4" class="py-8 px-4 text-center opacity-70">
                                            <p>Belum ada pengguna yang terdaftar</p>
                                        </td>
                                    </tr>
                                    <?php } else {
                                    foreach ($users as $userRow) {
                                        $detailData = [
                                            'id' => $userRow['id'] ?? '-',
                                            'fullname' => $userRow['fullname'] ?? '-',
                                            'username' => $userRow['username'] ?? '-',
                                            'email' => $userRow['email'] ?? '-',
                                            'phone' => $userRow['phone'] ?? '-',
                                            'level' => $userRow['level'] ?? '-',
                                            'created_at' => $userRow['created_at'] ?? '-',
                                            'height' => $userRow['height'] ?? '-',
                                            'weight' => $userRow['weight'] ?? '-',
                                            'waist_size' => $userRow['waist_size'] ?? '-',
                                            'age' => $userRow['age'] ?? '-',
                                            'gender' => $userRow['gender'] ?? '-',
                                            'bmi' => $userRow['bmi'] ?? '-',
                                            'daily_calories_target' => $userRow['daily_calories_target'] ?? '-'
                                        ];


                                        // Format created_at
                                        $createdAt = $userRow['created_at'] ?? '-';
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
                                        switch (strtolower($userRow['level'] ?? '')) {
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
                                                <span class="font-medium">@<?php echo htmlspecialchars($userRow['username']); ?></span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $levelBadgeClass; ?>">
                                                    <?php echo htmlspecialchars($userRow['level']); ?>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="text-sm"><?php echo htmlspecialchars($createdFormatted); ?></span>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <button type="button"
                                                    class="detail-btn inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-[#3dccc7] hover:text-white hover:bg-[#3dccc7] border border-[#3dccc7] rounded-md transition-all duration-200 hover:shadow-md"
                                                    data-detail="<?php echo htmlspecialchars(json_encode($detailData), ENT_QUOTES, 'UTF-8'); ?>">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Detail
                                                </button>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Detail Modal -->
    <div id="detail-modal" class="fixed inset-0 z-50 hidden">
        <div id="detail-modal-overlay"
            class="absolute inset-0 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

        <div id="detail-modal-panel"
            class="relative w-full max-w-6xl mx-auto mt-16 sm:mt-20 card rounded-xl shadow-2xl p-8 transform transition-all duration-300 opacity-0 scale-95 modal-panel overflow-y-auto max-h-[90vh]">

            <div class="flex items-center justify-between border-b pb-4 mb-6">
                <div>
                    <p class="text-sm uppercase tracking-widest text-[#3dccc7] font-medium">Detail User</p>
                    <h3 id="detail-name" class="text-3xl font-bold mt-1">-</h3>
                </div>
                <button id="detail-modal-close" type="button"
                    class="p-2 -mr-2 transition-colors duration-200 rounded-full">
                    <span class="sr-only">Tutup</span>
                    <svg class="w-6 h-6" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="md:col-span-1 space-y-6">
                    <h4 class="text-lg font-semibold border-b border-dashed pb-2">Basic Information</h4>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="rounded-lg p-4 card-1 shadow-md">
                                <p class="text-xs uppercase tracking-wider mb-1">Level</p>
                                <p id="detail-level" class="text-2xl font-bold text-[#3dccc7]">-</p>
                            </div>
                            <div class="rounded-lg p-4 card-1 shadow-md">
                                <p class="text-xs uppercase tracking-wider mb-1">Username</p>
                                <p id="detail-username" class="text-lg font-semibold break-words">-</p>
                            </div>
                        </div>

                        <div class="rounded-lg p-4 card-1 shadow-md">
                            <p class="text-xs uppercase tracking-wider mb-1">Email</p>
                            <p id="detail-email" class="text-base break-words font-medium">-</p>
                        </div>
                        <div class="rounded-lg p-4 card-1 shadow-md">
                            <p class="text-xs uppercase tracking-wider mb-1">Phone</p>
                            <p id="detail-phone" class="text-base font-medium">-</p>
                        </div>
                    </div>

                    <div class="pt-4 space-y-3">
                        <h4 class="text-lg font-semibold border-b border-dashed pb-2">Metadata</h4>
                        <div class="rounded-lg p-4 card-1 shadow-md">
                            <p class="text-xs uppercase tracking-wider mb-1 ">User ID</p>
                            <p id="detail-id" class="font-mono text-xs text-gray-600">-</p>
                        </div>
                        <div class="rounded-lg p-4 card-1 shadow-md">
                            <p class="text-xs uppercase tracking-wider mb-1">Created At</p>
                            <p id="detail-created" class="font-medium text-sm">-</p>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-6">
                    <h4 class="text-lg font-semibold border-b border-dashed pb-2">Physical Data & Daily Target</h4>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">

                        <div class="rounded-lg p-4 card-1 shadow-md">
                            <p class="text-xs uppercase tracking-wider mb-1">Gender</p>
                            <p id="detail-gender" class="text-base font-medium">-</p>
                        </div>

                        <div class="rounded-lg p-4 card-1 shadow-md">
                            <p class="text-xs uppercase tracking-wider mb-1">Age</p>
                            <p id="detail-age" class="text-base font-medium">-</p>
                        </div>

                        <div class="rounded-lg p-4 card-1 shadow-md">
                            <p class="text-xs uppercase tracking-wider mb-1">Height (cm)</p>
                            <p id="detail-height" class="text-base font-medium">-</p>
                        </div>

                        <div class="rounded-lg p-4 card-1 shadow-md">
                            <p class="text-xs uppercase tracking-wider mb-1">Weight (kg)</p>
                            <p id="detail-weight" class="text-base font-medium">-</p>
                        </div>

                        <div class="rounded-lg p-4 card-1 shadow-md">
                            <p class="text-xs uppercase tracking-wider mb-1">Waist Size (cm)</p>
                            <p id="detail-waist" class="text-base font-medium">-</p>
                        </div>

                        <div class="rounded-lg p-4 card-1 shadow-md">
                            <p class="text-xs uppercase tracking-wider mb-1">BMI</p>
                            <p id="detail-bmi" class="text-xl font-bold text-red-500 dark:text-red-400">-</p>
                        </div>

                        <div class="sm:col-span-3 rounded-lg p-4 shadow-md border border-[#3dccc7]">
                            <p class="text-sm uppercase tracking-wider text-[#3dccc7] mb-1 font-semibold">DAILY CALORIES TARGET</p>
                            <p id="detail-target" class="text-3xl font-extrabold text-[#3dccc7]">- <span class="text-lg font-normal">kcal</span></p>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Theme Switcher -->
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-center space-y-4">
        <div class="p-1 rounded-full card shadow-md">
            <a href="setting.php"
                class="flex items-center justify-center p-2 rounded-full transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.591 1.042c1.523-.878 3.25.848 2.372 2.372a1.724 1.724 0 001.042 2.591c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.042 2.591c.878 1.523-.849 3.25-2.372 2.372a1.724 1.724 0 00-2.591 1.042c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.591-1.042c-1.523.878-3.25-.849-2.372-2.372a1.724 1.724 0 00-1.042-2.591c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.042-2.591c-.878-1.524.849-3.25 2.372-2.372a1.724 1.724 0 002.591-1.042z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </a>
        </div>

        <div id="theme-switcher" class="flex flex-col p-1 rounded-full card">
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
        // === Detail Modal Logic ===
        const detailModal = document.getElementById('detail-modal');
        const detailModalOverlay = document.getElementById('detail-modal-overlay');
        const detailModalPanel = document.getElementById('detail-modal-panel');
        const detailModalClose = document.getElementById('detail-modal-close');
        const detailButtons = document.querySelectorAll('.detail-btn');
        const detailLogList = document.getElementById('detail-log-list');

        const detailFields = {
            name: document.getElementById('detail-name'),
            level: document.getElementById('detail-level'),
            username: document.getElementById('detail-username'),
            email: document.getElementById('detail-email'),
            phone: document.getElementById('detail-phone'),
            id: document.getElementById('detail-id'),
            created: document.getElementById('detail-created'),
            height: document.getElementById('detail-height'),
            weight: document.getElementById('detail-weight'),
            waist: document.getElementById('detail-waist'),
            age: document.getElementById('detail-age'),
            gender: document.getElementById('detail-gender'),
            bmi: document.getElementById('detail-bmi'),
            target: document.getElementById('detail-target')
        };


        const safeValue = (value, placeholder = '-') => {
            if (value === null || value === undefined) return placeholder;
            const trimmed = String(value).trim();
            return trimmed === '' ? placeholder : trimmed;
        };

        const setDetailContent = (data) => {
            detailFields.name.textContent = safeValue(data.fullname);
            detailFields.level.textContent = safeValue(data.level);
            detailFields.username.textContent = safeValue(data.username);
            detailFields.email.textContent = safeValue(data.email);
            detailFields.phone.textContent = safeValue(data.phone, 'Tidak ada nomor');
            detailFields.id.textContent = safeValue(data.id);
            detailFields.created.textContent = safeValue(data.created_at);
            detailFields.height.textContent = safeValue(data.height);
            detailFields.weight.textContent = safeValue(data.weight);
            detailFields.waist.textContent = safeValue(data.waist_size);
            detailFields.age.textContent = safeValue(data.age);
            detailFields.gender.textContent = safeValue(data.gender);
            detailFields.bmi.textContent = safeValue(data.bmi);
            detailFields.target.textContent = safeValue(data.daily_calories_target);

        };

        const openDetailModal = (data) => {
            if (!detailModal || !detailModalPanel || !detailModalOverlay) return;
            setDetailContent(data);
            detailModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => {
                detailModalOverlay.classList.remove('opacity-0');
                detailModalOverlay.classList.add('opacity-100');
                detailModalPanel.classList.add('show');
            });
        };

        const closeDetailModal = () => {
            if (!detailModal || !detailModalPanel || !detailModalOverlay) return;
            detailModalOverlay.classList.remove('opacity-100');
            detailModalOverlay.classList.add('opacity-0');
            detailModalPanel.classList.remove('show');
            const handleTransitionEnd = (event) => {
                if (event.propertyName === 'opacity') {
                    detailModal.classList.add('hidden');
                    document.body.style.overflow = '';
                    detailModalOverlay.removeEventListener('transitionend', handleTransitionEnd);
                }
            };
            detailModalOverlay.addEventListener('transitionend', handleTransitionEnd);
        };

        detailButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                const rawDetail = btn.getAttribute('data-detail') || '{}';
                try {
                    const detailData = JSON.parse(rawDetail);
                    openDetailModal(detailData);
                } catch (error) {
                    console.error('Failed to parse detail payload', error);
                }
            });
        });

        detailModalOverlay && detailModalOverlay.addEventListener('click', closeDetailModal);
        detailModalClose && detailModalClose.addEventListener('click', closeDetailModal);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && detailModal && !detailModal.classList.contains('hidden')) {
                closeDetailModal();
            }
        });

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
                mobileMenuPanel.classList.add('animate-open');
                menuToggleBtn.setAttribute('aria-expanded', 'true');
                setMenuIcon('open');
            };

            const closeMobileMenu = () => {
                mobileMenuPanel.classList.remove('animate-open');
                mobileMenuPanel.classList.add('animate-close');
                menuToggleBtn.setAttribute('aria-expanded', 'false');
                setMenuIcon('close');
            };

            mobileMenuPanel.addEventListener('animationend', (event) => {
                if (event.animationName === 'mobileMenuOut') {
                    mobileMenu.classList.add('hidden');
                }
            });

            menuToggleBtn.addEventListener('click', () => {
                const isExpanded = menuToggleBtn.getAttribute('aria-expanded') === 'true';
                isExpanded ? closeMobileMenu() : openMobileMenu();
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

        systemBtn && systemBtn.addEventListener('click', () => applyTheme('system'));
        lightBtn && lightBtn.addEventListener('click', () => applyTheme('light'));
        darkBtn && darkBtn.addEventListener('click', () => applyTheme('dark'));
        applyTheme(getActiveTheme());

        // === Sticky Header ===
        const header = document.getElementById('sticky-header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
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