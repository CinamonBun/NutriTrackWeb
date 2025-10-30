<?php
// config.php - Supabase Configuration
// This file loads credentials from .env file (not uploaded to GitHub)

// Simple .env file loader
function loadEnv($path) {
    if (!file_exists($path)) {
        die('Error: .env file not found. Please copy .env.example to .env and fill in your credentials.');
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            $value = trim($value, '"\'');
            
            // Set environment variable
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

// Load .env file
loadEnv(__DIR__ . '/.env');

// Supabase API Configuration
define('SUPABASE_URL', getenv('SUPABASE_URL'));
define('SUPABASE_KEY', getenv('SUPABASE_KEY'));

// Validate configuration
if (empty(SUPABASE_URL) || empty(SUPABASE_KEY)) {
    die('Error: Supabase credentials not configured. Please check your .env file.');
}

/**
 * Make API request to Supabase
 * 
 * @param string $method HTTP method (GET, POST, PATCH, DELETE)
 * @param string $endpoint API endpoint (e.g., '/rest/v1/user')
 * @param array|null $data Data to send (for POST/PATCH)
 * @return array Response with 'status' and 'data' keys
 */
function supabaseRequest($method, $endpoint, $data = null) {
    $ch = curl_init();
    
    $url = SUPABASE_URL . $endpoint;
    
    $headers = [
        'apikey: ' . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
        'Content-Type: application/json',
        'Prefer: return=representation'
    ];
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return [
            'status' => 500,
            'data' => ['error' => $error]
        ];
    }
    
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    return [
        'status' => $httpCode,
        'data' => $result
    ];
}

// ==================== USER FUNCTIONS ====================

/**
 * Get user by username
 * 
 * @param string $username Username to search for
 * @return array|null User data or null if not found
 */
function getUserByUsername($username) {
    $response = supabaseRequest('GET', '/rest/v1/user?username=eq.' . urlencode($username) . '&select=*');
    
    if ($response['status'] == 200 && !empty($response['data'])) {
        return $response['data'][0];
    }
    
    return null;
}

/**
 * Create new user
 * 
 * @param array $userData User data to insert
 * @return array Response from Supabase
 */
function createUser($userData) {
    return supabaseRequest('POST', '/rest/v1/user', $userData);
}

/**
 * Update user
 * 
 * @param string $username Username to update
 * @param array $userData Data to update
 * @return array Response from Supabase
 */
function updateUser($username, $userData) {
    return supabaseRequest('PATCH', '/rest/v1/user?username=eq.' . urlencode($username), $userData);
}

/**
 * Delete user
 * 
 * @param string $username Username to delete
 * @return array Response from Supabase
 */
function deleteUser($username) {
    return supabaseRequest('DELETE', '/rest/v1/user?username=eq.' . urlencode($username));
}

// ==================== FOOD FUNCTIONS ====================

/**
 * Get all food items for a specific user
 * 
 * @param string $username Username to get foods for
 * @return array Array of food items
 */
function getFoodsByUser($username) {
    $response = supabaseRequest('GET', '/rest/v1/food?username=eq.' . urlencode($username) . '&select=*&order=created_at.desc');
    
    if ($response['status'] == 200 && !empty($response['data'])) {
        return $response['data'];
    }
    
    return [];
}

/**
 * Create new food item
 * 
 * @param array $foodData Food data to insert (must include username)
 * @return array Response from Supabase
 */
function createFood($foodData) {
    return supabaseRequest('POST', '/rest/v1/food', $foodData);
}

/**
 * Delete food item (with user verification)
 * 
 * @param int $foodId Food ID to delete
 * @param string $username Username for verification (optional for testing)
 * @return array Response from Supabase
 */
function deleteFood($foodId, $username = null) {
    if ($username !== null) {
        // Delete with user verification (more secure)
        return supabaseRequest('DELETE', '/rest/v1/food?id=eq.' . $foodId . '&username=eq.' . urlencode($username));
    } else {
        // Delete by ID only (for testing)
        return supabaseRequest('DELETE', '/rest/v1/food?id=eq.' . $foodId);
    }
}

/**
 * Update food item
 * 
 * @param int $foodId Food ID to update
 * @param string $username Username for verification
 * @param array $foodData Data to update
 * @return array Response from Supabase
 */
function updateFood($foodId, $username, $foodData) {
    return supabaseRequest('PATCH', '/rest/v1/food?id=eq.' . $foodId . '&username=eq.' . urlencode($username), $foodData);
}

// Legacy mysqli compatibility (will return null)
// This is here so old code doesn't break, but you should use the functions above instead
$conn = null;

?>