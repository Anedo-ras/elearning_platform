<?php
require_once '../config.php';

$conn = getDBConnection();

$query = "
    SELECT id, username, email, full_name, total_points, current_streak, created_at
    FROM users
    WHERE is_admin = FALSE
    ORDER BY created_at DESC
";

$result = $conn->query($query);

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = [
        'id' => $row['id'],
        'username' => $row['username'],
        'email' => $row['email'],
        'full_name' => $row['full_name'],
        'total_points' => intval($row['total_points']),
        'current_streak' => intval($row['current_streak']),
        'created_at' => $row['created_at']
    ];
}

echo json_encode([
    'success' => true,
    'users' => $users
]);

$conn->close();
?>