<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit;
}

$username = $_SESSION['username'];
include 'config.php';
include 'db-functions.php';

// Check admin
requireAdmin($username);

$message = '';
$error = '';

// Get user profile
$user = getUserByUsername($username);
$fullname = $user['fullname'] ?? $username;

// ============================
// DELETE FOOD
// ============================
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);

    if ($deleteId > 0) {
        $result = deleteFood($deleteId);

        if ($result['status'] == 204 || $result['status'] == 200) {
            $message = "Food item deleted successfully!";
        } else {
            $error = "Failed to delete item. Status: " . $result['status'];
        }
    }
}

// ============================
// ADD FOOD
// ============================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_food'])) {
    $foodName = trim($_POST['name'] ?? '');
    $calories = trim($_POST['calories'] ?? '0');
    $protein  = trim($_POST['protein'] ?? '0');
    $carbs    = trim($_POST['carbs'] ?? '0');
    $fat      = trim($_POST['fat'] ?? '0');

    if ($foodName === '') {
        $error = "Food name is required.";
    } else {
        $data = [
            'name' => $foodName,
            'calories' => $calories,
            'protein' => $protein,
            'carbs' => $carbs,
            'fat' => $fat,
            'username' => $username
        ];

        $result = createFood($data);

        if ($result['status'] == 201) {
            $message = "Food added successfully!";
        } else {
            $error = "Failed to add. Status: " . $result['status'];
        }
    }
}

// ============================
// EDIT FOOD
// ============================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_food'])) {
    $foodId = intval($_POST['food_id'] ?? 0);
    $foodName = trim($_POST['edit_name'] ?? '');
    $calories = trim($_POST['edit_calories'] ?? '0');
    $protein  = trim($_POST['edit_protein'] ?? '0');
    $carbs    = trim($_POST['edit_carbs'] ?? '0');
    $fat      = trim($_POST['edit_fat'] ?? '0');

    if ($foodId <= 0) {
        $error = "Invalid food item.";
    } elseif ($foodName === '') {
        $error = "Food name is required.";
    } else {
        $data = [
            'name' => $foodName,
            'calories' => $calories,
            'protein' => $protein,
            'carbs' => $carbs,
            'fat' => $fat
        ];

        $result = updateFood($foodId, $username, $data);

        if ($result['status'] == 200) {
            $message = "Food updated successfully!";
        } else {
            $error = "Failed to update. Status: " . $result['status'];
        }
    }
}

// ============================
// FETCH ALL FOODS (USER ONLY)
// ============================

$foods = getFoodsByUser();
?>


<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriTrack - Staple Management</title>
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
                    <li><a href="dashboard.php" class="transition duration-200 text-hover-light">Dashboard</a>
                    </li>
                    <li><a href="user.php" class="transition duration-200 hover:scale-105">User</a></li>
                    <li><a href="season.php" class="transition duration-200 hover:scale-105">Season</a></li>
                    <li><a href="meal.php" class="transition duration-200 hover:scale-105">Meal</a></li>
                    <li><a href="food.php" class="font-semibold text-[#3dccc7]">Food</a></li>
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
    <main class="pt-28 md:pt-36 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <p class="text-sm uppercase tracking-widest opacity-60">Food</p>
                    <h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Food Management</h1>
                    <p class="mt-2 text-base opacity-80">Manage your food items and track nutritional information.</p>
                </div>
            </div>

            <?php if (!empty($message)) { ?>
                <div class="text-sm text-green-700 bg-green-100 dark:bg-green-900/20 dark:text-green-400 rounded-md px-4 py-3">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>
            <?php if (!empty($error)) { ?>
                <div class="text-sm text-red-700 bg-red-100 dark:bg-red-900/20 dark:text-red-400 rounded-md px-4 py-3">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php } ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="p-6 rounded-lg shadow-md card">
                    <h2 class="text-xl font-semibold">Add Food</h2>
                    <form class="mt-4 space-y-4" action="food.php" method="POST">
                        <input type="hidden" name="add_food" value="1">
                        <div>
                            <label for="name" class="block text-sm font-medium mb-2">Name</label>
                            <input id="name" name="name" type="text" required
                                class="block w-full px-3 py-2 card rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                placeholder="e.g., Grilled Chicken Breast">
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label for="calories" class="block text-sm font-medium mb-2">Calories</label>
                                <input id="calories" name="calories" type="number" step="0.01" min="0"
                                    class="block w-full px-3 py-2 card rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                    placeholder="0">
                            </div>
                            <div>
                                <label for="protein" class="block text-sm font-medium mb-2">Protein (g)</label>
                                <input id="protein" name="protein" type="number" step="0.01" min="0"
                                    class="block w-full px-3 py-2 card rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                    placeholder="0">
                            </div>
                            <div>
                                <label for="carbs" class="block text-sm font-medium mb-2">Carbs (g)</label>
                                <input id="carbs" name="carbs" type="number" step="0.01" min="0"
                                    class="block w-full px-3 py-2 card rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                    placeholder="0">
                            </div>
                            <div>
                                <label for="fat" class="block text-sm font-medium mb-2">Fat (g)</label>
                                <input id="fat" name="fat" type="number" step="0.01" min="0"
                                    class="block w-full px-3 py-2 card rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                    placeholder="0">
                            </div>
                        </div>
                        <div>
                            <button type="submit"
                                class="inline-flex justify-center gap-2 text-white bg-[#3dccc7] hover:bg-[#68d8d6] px-4 py-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2">
                                Add Food
                            </button>
                        </div>
                    </form>
                </div>

                <div class="p-6 rounded-lg shadow-md card">
                    <h2 class="text-xl font-semibold">Your Foods</h2>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left opacity-70">
                                    <th class="py-2 pr-4">Name</th>
                                    <th class="py-2 pr-4">Cal</th>
                                    <th class="py-2 pr-4">P (g)</th>
                                    <th class="py-2 pr-4">C (g)</th>
                                    <th class="py-2 pr-4">F (g)</th>
                                    <th class="py-2 pr-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($foods)) { ?>
                                    <tr>
                                        <td colspan="6" class="py-4 opacity-70">No food items yet.</td>
                                    </tr>
                                    <?php } else {
                                    foreach ($foods as $f) { ?>
                                        <tr class="border-t border-neutral-200 dark:border-neutral-700">
                                            <td class="py-2 pr-4"><?php echo htmlspecialchars($f['foods_name']); ?></td>
                                            <td class="py-2 pr-4"><?php echo htmlspecialchars((string) $f['calories_per_unit']); ?>
                                            </td>
                                            <td class="py-2 pr-4"><?php echo htmlspecialchars((string) $f['protein_per_unit']); ?>
                                            </td>
                                            <td class="py-2 pr-4"><?php echo htmlspecialchars((string) $f['carbs_per_unit']); ?></td>
                                            <td class="py-2 pr-4"><?php echo htmlspecialchars((string) $f['fat_per_unit']); ?></td>
                                            <td class="py-2 pr-4 space-x-3">
                                                <button type="button"
                                                    class="text-cyan-600 hover:underline dark:text-cyan-300 edit-food-btn"
                                                    data-id="<?php echo (int) $f['id']; ?>"
                                                    data-name="<?php echo htmlspecialchars($f['foods_name']); ?>"
                                                    data-calories="<?php echo htmlspecialchars((string) $f['calories_per_unit']); ?>"
                                                    data-protein="<?php echo htmlspecialchars((string) $f['protein_per_unit']); ?>"
                                                    data-carbs="<?php echo htmlspecialchars((string) $f['carbs_per_unit']); ?>"
                                                    data-fat="<?php echo htmlspecialchars((string) $f['fat_per_unit']); ?>">
                                                    Edit
                                                </button>
                                                <a href="food.php?delete=<?php echo (int) $f['id']; ?>"
                                                    class="text-red-600 hover:underline dark:text-red-400"
                                                    onclick="return confirm('Delete this item?');">Delete</a>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>

    <!-- Edit Food Modal -->
    <div id="edit-food-modal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-lg mx-auto card rounded-xl shadow-2xl p-6 fade-in">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold">Edit Food</h3>
                <button type="button" id="close-edit-modal" class="opacity-80">✕</button>
            </div>
            <form id="edit-food-form" class="space-y-4" method="POST" action="food.php">
                <input type="hidden" name="edit_food" value="1">
                <input type="hidden" name="food_id" id="edit-food-id">
                <div>
                    <label for="edit-name" class="block text-sm font-medium mb-2">Name</label>
                    <input id="edit-name" name="edit_name" type="text" required
                        class="block w-full px-3 py-2 card rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label for="edit-calories" class="block text-sm font-medium mb-2">Calories</label>
                        <input id="edit-calories" name="edit_calories" type="number" step="0.01" min="0"
                            class="block w-full px-3 py-2 card rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>
                    <div>
                        <label for="edit-protein" class="block text-sm font-medium mb-2">Protein (g)</label>
                        <input id="edit-protein" name="edit_protein" type="number" step="0.01" min="0"
                            class="block w-full px-3 py-2 card rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>
                    <div>
                        <label for="edit-carbs" class="block text-sm font-medium mb-2">Carbs (g)</label>
                        <input id="edit-carbs" name="edit_carbs" type="number" step="0.01" min="0"
                            class="block w-full px-3 py-2 card rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>
                    <div>
                        <label for="edit-fat" class="block text-sm font-medium mb-2">Fat (g)</label>
                        <input id="edit-fat" name="edit_fat" type="number" step="0.01" min="0"
                            class="block w-full px-3 py-2 card rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" id="cancel-edit-modal"
                        class="px-4 py-2 rounded-md border border-gray-300 hover:bg-white/10">Cancel</button>
                    <button type="submit"
                        class="inline-flex justify-center gap-2 text-white bg-[#3dccc7] hover:bg-[#68d8d6] px-4 py-3 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2">
                        Save Changes
                    </button>
                </div>
            </form>
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

        // === Edit Food Modal Logic ===
        const editModal = document.getElementById('edit-food-modal');
        const editButtons = document.querySelectorAll('.edit-food-btn');
        const closeEditModalBtn = document.getElementById('close-edit-modal');
        const cancelEditModalBtn = document.getElementById('cancel-edit-modal');
        const editFoodIdInput = document.getElementById('edit-food-id');
        const editNameInput = document.getElementById('edit-name');
        const editCaloriesInput = document.getElementById('edit-calories');
        const editProteinInput = document.getElementById('edit-protein');
        const editCarbsInput = document.getElementById('edit-carbs');
        const editFatInput = document.getElementById('edit-fat');

        const setBodyScroll = (locked) => {
            document.body.style.overflow = locked ? 'hidden' : '';
        };

        const openEditModal = () => {
            if (!editModal) return;
            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
            setBodyScroll(true);
        };

        const closeEditModal = () => {
            if (!editModal) return;
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
            setBodyScroll(false);
        };

        editButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                const {
                    id,
                    name,
                    calories,
                    protein,
                    carbs,
                    fat
                } = btn.dataset;
                editFoodIdInput.value = id || '';
                editNameInput.value = name || '';
                editCaloriesInput.value = calories || 0;
                editProteinInput.value = protein || 0;
                editCarbsInput.value = carbs || 0;
                editFatInput.value = fat || 0;
                openEditModal();
            });
        });

        closeEditModalBtn && closeEditModalBtn.addEventListener('click', closeEditModal);
        cancelEditModalBtn && cancelEditModalBtn.addEventListener('click', (e) => {
            e.preventDefault();
            closeEditModal();
        });

        editModal && editModal.addEventListener('click', (event) => {
            if (event.target === editModal) {
                closeEditModal();
            }
        });
    </script>
</body>

</html>