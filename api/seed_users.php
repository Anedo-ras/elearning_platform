<?php
require_once 'config.php';

$conn = getDBConnection();

// Insert sample users
$users = [
    ['username' => 'student1', 'email' => 'student1@example.com', 'password' => 'password123', 'full_name' => 'John Doe'],
    ['username' => 'student2', 'email' => 'student2@example.com', 'password' => 'password123', 'full_name' => 'Jane Smith'],
    ['username' => 'admin', 'email' => 'admin@example.com', 'password' => 'admin123', 'full_name' => 'Admin User', 'is_admin' => 1]
];

$stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, is_admin) VALUES (?, ?, ?, ?, ?)");

foreach ($users as $user) {
    $hashed_password = password_hash($user['password'], PASSWORD_BCRYPT);
    $is_admin = $user['is_admin'] ?? 0;
    
    $stmt->bind_param("ssssi", $user['username'], $user['email'], $hashed_password, $user['full_name'], $is_admin);
    
    if (!$stmt->execute()) {
        echo "Error inserting user: " . $stmt->error . "<br>";
    }
}

echo "Sample users created!<br>";
echo "Test Credentials:<br>";
echo "Username: student1 | Password: password123<br>";
echo "Username: admin | Password: admin123<br>";

$stmt->close();
$conn->close();
?>
