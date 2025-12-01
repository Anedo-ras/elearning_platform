<?php
require_once 'config.php';

// Log request
logDebug("Login attempt started");

// Get raw POST data
$rawData = file_get_contents('php://input');
logDebug("Raw input data", $rawData);

$data = json_decode($rawData, true);

if (!$data || !isset($data['username']) || !isset($data['password'])) {
    logDebug("Missing credentials", $data);
    echo json_encode([
        'success' => false,
        'message' => 'Missing credentials'
    ]);
    exit();
}

$username = trim($data['username']);
$password = $data['password'];

logDebug("Login attempt for username", $username);

$conn = getDBConnection();

// Find user by username or email
$stmt = $conn->prepare("SELECT id, username, email, password, full_name, is_admin FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    logDebug("User not found", $username);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid username or password'
    ]);
    $stmt->close();
    $conn->close();
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();

logDebug("User found", ['id' => $user['id'], 'username' => $user['username']]);

// Verify password
if (!password_verify($password, $user['password'])) {
    logDebug("Password verification failed", $username);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid username or password'
    ]);
    $conn->close();
    exit();
}

logDebug("Password verified successfully", $username);

// Update last login
$stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$stmt->close();

// Log the login to database
$stmt = $conn->prepare("INSERT INTO user_logins (user_id, login_time, ip_address) VALUES (?, NOW(), ?)");
$ip = $_SERVER['REMOTE_ADDR'];
$stmt->bind_param("is", $user['id'], $ip);
$stmt->execute();
$stmt->close();

logDebug("Login successful", ['user_id' => $user['id'], 'username' => $user['username']]);

// Set session
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['is_admin'] = $user['is_admin'];

echo json_encode([
    'success' => true,
    'message' => 'Login successful',
    'user' => [
        'id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email'],
        'full_name' => $user['full_name'],
        'is_admin' => (bool)$user['is_admin']
    ]
]);

$conn->close();
?>