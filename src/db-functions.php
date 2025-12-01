<?php
// db_functions.php - Kumpulan Fungsi Database untuk NutriTrack

// ==================== DATABASE HELPER FUNCTIONS ====================

/**
 * Helper function: Execute query and return result
 */
function dbQuery($sql)
{
    global $conn;
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        // Log error ke PHP error log
        error_log("Query Error: " . mysqli_error($conn) . " in SQL: " . $sql);
        return false;
    }
    return $result;
}

/**
 * Helper function: Escape string to prevent SQL Injection
 */
function escape($string)
{
    global $conn;
    // Pengecekan agar fungsi tidak error jika $conn belum terdefinisi
    if (!$conn) {
        die("Koneksi database belum dibuat. Pastikan 'config.php' sudah di-include.");
    }
    return mysqli_real_escape_string($conn, $string);
}


// ==================== USER FUNCTIONS ====================

/**
 * Get user by username or email
 */
function getUserByUsername($username)
{
    $safe_username = escape($username);

    $sql = "SELECT * FROM users WHERE username = '$safe_username' OR email = '$safe_username' LIMIT 1";
    $result = dbQuery($sql);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

/**
 * Verify user credentials for login
 * Menggunakan password_verify() untuk keamanan
 */
function verifyUserLogin($username, $password)
{
    $user = getUserByUsername($username);

    // Cek jika user ditemukan dan password sesuai hash
    if ($user && password_verify($password, $user['password'])) {
        return $user; // Login berhasil
    }
    return false; // Login gagal
}

/**
 * Create new user (with Password Hashing)
 */
function createUser($userData)
{
    global $conn;

    $password_plaintext = $userData['password'];
    $password_hashed = password_hash($password_plaintext, PASSWORD_DEFAULT);

    $username = escape($userData['username']);
    $fullname = escape($userData['fullname'] ?? '');
    $email = escape($userData['email']);
    $height = isset($userData['height']) ? (int)$userData['height'] : 0;
    $weight = isset($userData['weight']) ? (int)$userData['weight'] : 0;
    $age = isset($userData['age']) ? (int)$userData['age'] : 0;
    $gender = escape($userData['gender']);
    $daily_target = isset($userData['daily_calories_target']) ? (int)$userData['daily_calories_target'] : 2000;
    $level = 'user';

    $sql = "INSERT INTO users (username, fullname, email, password, height, weight, age, gender, daily_calories_target, level) 
             VALUES ('$username', '$fullname', '$email', '$password_hashed', $height, $weight, $age, '$gender', $daily_target, '$level')";

    if (dbQuery($sql)) {
        return ['status' => 201, 'data' => ['id' => mysqli_insert_id($conn)]];
    }
    return ['status' => 500, 'data' => ['error' => mysqli_error($conn)]];
}

/**
 * Update user
 */
function updateUser($username, $userData)
{
    $safe_username = escape($username);

    $updates = [];
    foreach ($userData as $key => $value) {
        $val = escape($value);
        if ($key === 'password') {
            $val = password_hash($value, PASSWORD_DEFAULT);
        }
        $updates[] = "$key = '$val'";
    }

    if (empty($updates)) return ['status' => 400, 'data' => ['error' => 'No data to update']];

    $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE username = '$safe_username'";

    if (dbQuery($sql)) {
        return ['status' => 200, 'data' => 'Updated successfully'];
    }
    return ['status' => 500, 'data' => ['error' => 'Update failed']];
}

/**
 * Delete User
 */
function deleteUser($username)
{
    global $conn;

    if (!$username) {
        return ['status' => 400, 'data' => ['error' => 'Missing username']];
    }

    $safe_username = escape($username);

    $checkSql = "SELECT id FROM users WHERE username = '$safe_username' LIMIT 1";
    $checkResult = dbQuery($checkSql);

    if (!$checkResult || mysqli_num_rows($checkResult) === 0) {
        return ['status' => 404, 'data' => ['error' => 'User not found']];
    }

    $deleteSql = "DELETE FROM users WHERE username = '$safe_username'";

    if (dbQuery($deleteSql)) {
        return ['status' => 200, 'data' => 'User deleted'];
    }

    return ['status' => 500, 'data' => ['error' => mysqli_error($conn)]];
}


/**
 * Check Admin
 */
function requireAdmin($username)
{
    $user = getUserByUsername($username);
    if (!$user || strtolower($user['level']) !== 'admin') {
        header("Location: index.php");
        exit;
    }
}


// ==================== FOOD FUNCTIONS ====================

/**
 * Get Foods By User
 */
function getFoodsByUser()
{
    $sql = "SELECT * FROM foods ORDER BY id DESC";
    $result = dbQuery($sql);

    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

/**
 * Create Food
 */
function createFood($data)
{
    global $conn;

    $name = escape($data['name']);
    $cal  = intval($data['calories']);
    $prot = intval($data['protein']);
    $carb = intval($data['carbs']);
    $fat  = intval($data['fat']);

    $sql = "
        INSERT INTO foods (foods_name, calories_per_unit, protein_per_unit, carbs_per_unit, fat_per_unit)
        VALUES ('$name', $cal, $prot, $carb, $fat)
    ";

    if (dbQuery($sql)) {
        return ['status' => 201, 'data' => ['id' => mysqli_insert_id($conn)]];
    }

    return ['status' => 500, 'data' => mysqli_error($conn)];
}

/**
 * Update Food
 */
function updateFood($foodId, $username, $data)
{
    $foodId = intval($foodId);

    $name = escape($data['name']);
    $cal  = intval($data['calories']);
    $prot = intval($data['protein']);
    $carb = intval($data['carbs']);
    $fat  = intval($data['fat']);

    $sql = "
        UPDATE foods SET 
            foods_name = '$name',
            calories_per_unit = $cal,
            protein_per_unit = $prot,
            carbs_per_unit = $carb,
            fat_per_unit = $fat
        WHERE id = $foodId
    ";

    if (dbQuery($sql)) {
        return ['status' => 200, 'data' => 'Updated'];
    }

    return ['status' => 500, 'data' => mysqli_error($GLOBALS['conn'])];
}

/**
 * Delete Food
 */
function deleteFood($foodId)
{
    $id = (int)$foodId;
    $sql = "DELETE FROM foods WHERE id = $id";

    if (dbQuery($sql)) {
        return ['status' => 200, 'data' => 'Deleted'];
    }
    return ['status' => 500, 'data' => 'Error'];
}


// ==================== MEAL FUNCTIONS ====================

/**
 * Get Meals By User
 */
function getMealsByUser($userId)
{
    $user_id = (int)$userId;

    $sql = "SELECT * FROM meals WHERE id_user = $user_id ORDER BY meals_name ASC";
    $result = dbQuery($sql);

    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

/**
 * Create Meal
 */
function createMeal($mealData, $userId)
{
    global $conn;
    $user_id = (int)$userId;

    $name = escape($mealData['meals_name']);
    $desc = escape($mealData['description'] ?? '');
    $cal = (int)$mealData['calories'];
    $prot = (int)$mealData['protein'];
    $carbs = (int)$mealData['carbs'];
    $fat = (int)$mealData['fat'];

    $sql = "INSERT INTO meals (id_user, meals_name, description, calories, protein, carbs, fat) 
             VALUES ($user_id, '$name', '$desc', $cal, $prot, $carbs, $fat)";

    if (dbQuery($sql)) {
        return ['status' => 201, 'data' => ['id' => mysqli_insert_id($conn)]];
    }
    return ['status' => 500, 'data' => ['error' => mysqli_error($conn)]];
}

/**
 * Update Meal Item
 */
function updateMealItem($mealId, $data, $userId)
{
    $mealId = intval($mealId);
    $userId = intval($userId);

    $fields = [];
    foreach ($data as $key => $value) {
        $val = escape($value);
        $fields[] = "$key = '$val'";
    }

    $sql = "UPDATE meals SET " . implode(", ", $fields) . " 
            WHERE id = $mealId AND id_user = $userId";

    return dbQuery($sql);
}


/**
 * Delete Meal
 */
function deleteMeal($mealId, $userId)
{
    $id = (int)$mealId;
    $user_id = (int)$userId;

    $sql = "DELETE FROM meals WHERE id = $id AND id_user = $user_id";

    if (dbQuery($sql)) {
        if (mysqli_affected_rows($GLOBALS['conn']) > 0) {
            return ['status' => 200, 'data' => 'Deleted'];
        } else {
            return ['status' => 404, 'data' => 'Meal not found or unauthorized'];
        }
    }
    return ['status' => 500, 'data' => 'Error'];
}


// ==================== SEASONING FUNCTIONS (Tabel: seasoning) ====================

/**
 * Get all seasoning items
 */
function getSeasonings()
{
    $sql = "SELECT * FROM seasoning ORDER BY id DESC";
    $result = dbQuery($sql);
    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

/**
 * Create Seasoning
 */
function createSeasoning($seasonData)
{
    global $conn;
    $name = escape($seasonData['seasoning_name']);
    $cal = (int)$seasonData['calories'];
    $prot = (int)$seasonData['protein'];
    $carbs = (int)$seasonData['carbs'];
    $fat = (int)$seasonData['fat'];
    $sodium = (int)$seasonData['sodium_mg'];

    $sql = "INSERT INTO seasoning (seasoning_name, calories, protein, carbs, fat, sodium_mg) 
             VALUES ('$name', $cal, $prot, $carbs, $fat, $sodium)";

    if (dbQuery($sql)) {
        return ['status' => 201, 'data' => ['id' => mysqli_insert_id($conn)]];
    }
    return ['status' => 500, 'data' => ['error' => mysqli_error($conn)]];
}

/**
 * Delete Seasoning
 */
function deleteSeasoning($id)
{
    $id = intval($id);
    $sql = "DELETE FROM seasoning WHERE id = $id";
    return dbQuery($sql);
}

/**
 * Update Seasoning
 */
function updateSeasoning($id, $data)
{
    $id = intval($id);

    $fields = [];
    foreach ($data as $key => $value) {
        $val = escape($value);
        $fields[] = "$key = '$val'";
    }

    $sql = "UPDATE seasoning SET " . implode(", ", $fields) . " WHERE id = $id";
    return dbQuery($sql);
}


// ==================== DAILY LOG FUNCTIONS (Tabel: daily_calories_history) ====================

/**
 * Get daily log for a specific user and date
 */
function getDailyLog($userId, $date)
{
    $user_id = (int)$userId;
    $safe_date = escape($date); // Format: YYYY-MM-DD

    $sql = "SELECT * FROM daily_calories_history 
            WHERE id_user = $user_id AND tanggal = '$safe_date' LIMIT 1";
    $result = dbQuery($sql);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

/**
 * Create or Update Daily Log (Upsert logic)
 */
function logDailyCalories($userId, $date, $consumedCalories, $waterLog, $targetMet)
{
    $user_id = (int)$userId;
    $safe_date = escape($date);
    $cal = (int)$consumedCalories;
    $water = (int)$waterLog;
    $target = (int)$targetMet;

    $existing_log = getDailyLog($user_id, $safe_date);

    if ($existing_log) {
        $sql = "UPDATE daily_calories_history SET 
                consumed_calories = $cal, 
                log_water = $water,
                target_met = $target
                WHERE id_user = $user_id AND tanggal = '$safe_date'";
    } else {
        $sql = "INSERT INTO daily_calories_history (id_user, tanggal, consumed_calories, log_water, target_met) 
                VALUES ($user_id, '$safe_date', $cal, $water, $target)";
    }

    if (dbQuery($sql)) {
        return ['status' => 200, 'message' => 'Daily log updated successfully'];
    }
    return ['status' => 500, 'message' => 'Failed to update daily log'];
}
