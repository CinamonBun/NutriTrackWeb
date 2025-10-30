<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit;
}

$username = $_SESSION['username'];
include 'config.php';

$message = '';
$error = '';

// Get current user info
$user = getUserByUsername($username);
$fullname = $user['fullname'] ?? $username;

// Handle delete
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    if ($deleteId > 0) {
        // Try delete with user verification first
        $result = deleteFood($deleteId, $username);
        
        if ($result['status'] == 204) {
            $message = 'Food item deleted successfully!';
        } else if ($result['status'] == 200) {
            // Some APIs return 200 instead of 204
            $message = 'Food item deleted successfully!';
        } else {
            // Show detailed error
            $error = 'Failed to delete item (Status: ' . $result['status'] . '). ';
            
            // Try to delete without username check (for debugging)
            $result2 = supabaseRequest('DELETE', '/rest/v1/food?id=eq.' . $deleteId);
            
            if ($result2['status'] == 204 || $result2['status'] == 200) {
                $message = 'Food item deleted (without user check)!';
                $error = ''; // Clear error
            } else {
                $error .= 'Item may not exist or username mismatch. Details: ' . json_encode($result['data']);
            }
        }
    }
}

// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_food'])) {
    $foodName = trim($_POST['name'] ?? '');
    $calories = isset($_POST['calories']) ? trim((string) $_POST['calories']) : '0';
    $protein = isset($_POST['protein']) ? trim((string) $_POST['protein']) : '0';
    $carbs = isset($_POST['carbs']) ? trim((string) $_POST['carbs']) : '0';
    $fat = isset($_POST['fat']) ? trim((string) $_POST['fat']) : '0';

    if ($foodName === '') {
        $error = 'Food name is required.';
    } else {
        $foodData = [
            'name' => $foodName,
            'calories' => $calories,
            'protein' => $protein,
            'carbs' => $carbs,
            'fat' => $fat,
            'username' => $username // Associate food with user
            // Removed created_at - let Supabase use default now()
        ];
        
        $result = createFood($foodData);
        
        // DEBUG: Show the result
        if ($result['status'] == 201) {
            $message = 'Food item added successfully!';
        } else {
            // Show detailed error for debugging
            $error = 'Failed to add item. Status: ' . $result['status'];
            if (!empty($result['data'])) {
                $error .= ' | Details: ' . json_encode($result['data']);
            }
        }
    }
}

// Fetch list - only for current user
$foods = getFoodsByUser($username);

?>
<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriTrack - Food Management</title>
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
            <nav class="flex mx-auto justify-between items-center px-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold">Logo</h1>
                </div>
                <ul class="hidden md:flex items-center space-x-8">
                    <li><a href="dashboard.php" class="transition duration-200 transform text-hover-light">Dashboard</a>
                    </li>
                    <li><a href="food.php" class="transition duration-200 transform hover:scale-105">Food</a></li>
                    <li><a href="user.php" class="transition duration-200 transform hover:scale-105">User</a></li>
                </ul>
                <div class="hidden md:flex items-center space-x-3">
                    <span class="dark:text-dark-text whitespace-nowrap">Hello,
                        <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php"
                        class="inline-flex justify-center gap-2 text-white dark:hover:bg-[#08D2CB] dark:dark:bg-[#07bab4] px-4 py-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 w-full">Logout</a>
                </div>
                <div class="md:hidden">
                    <button class="text-gray-800 dark:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main -->
    <main>
        <section class="pt-28 pb-12 md:pt-36 min-h-[60vh]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
					<h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Food Management</h1>
					<p class="mt-2 text-lg dark:opacity-80">Welcome back, <span
							class="font-semibold"><?php echo htmlspecialchars($fullname); ?></span>.</p>
				</div>

                <?php if (!empty($message)) { ?>
                    <div class="mb-6 text-sm text-green-700 bg-green-100 dark:bg-green-900/20 dark:text-green-400 rounded-md px-4 py-3">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php } ?>
                <?php if (!empty($error)) { ?>
                    <div class="mb-6 text-sm text-red-700 bg-red-100 dark:bg-red-900/20 dark:text-red-400 rounded-md px-4 py-3">
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
                                    class="inline-flex justify-center gap-2 text-white dark:hover:bg-[#08D2CB] dark:dark:bg-[#07bab4] px-4 py-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2">
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
                                            <tr class="border-t border-gray-200/50 dark:border-gray-700/50">
                                                <td class="py-2 pr-4"><?php echo htmlspecialchars($f['name']); ?></td>
                                                <td class="py-2 pr-4"><?php echo htmlspecialchars((string) $f['calories']); ?>
                                                </td>
                                                <td class="py-2 pr-4"><?php echo htmlspecialchars((string) $f['protein']); ?>
                                                </td>
                                                <td class="py-2 pr-4"><?php echo htmlspecialchars((string) $f['carbs']); ?></td>
                                                <td class="py-2 pr-4"><?php echo htmlspecialchars((string) $f['fat']); ?></td>
                                                <td class="py-2 pr-4">
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
        </section>
    </main>

    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-center space-y-4">
        <div class="p-1 rounded-full card shadow-md transition-all duration-300">
            <button id="settings-btn"
                class="flex items-center justify-center p-2 rounded-full transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.591 1.042c1.523-.878 3.25.848 2.372 2.372a1.724 1.724 0 001.042 2.591c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.042 2.591c.878 1.523-.849 3.25-2.372 2.372a1.724 1.724 0 00-2.591 1.042c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.591-1.042c-1.523.878-3.25-.849-2.372-2.372a1.724 1.724 0 00-1.042-2.591c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.042-2.591c-.878-1.524.849-3.25 2.372-2.372a1.724 1.724 0 002.591-1.042z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>
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