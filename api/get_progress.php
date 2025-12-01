<?php
require_once 'config.php';

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'User ID required'
    ]);
    exit();
}

$conn = getDBConnection();

// Get user's current streak
$stmt = $conn->prepare("SELECT current_streak FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$streak = $user ? intval($user['current_streak']) : 0;
$stmt->close();

// Get user's badges
$stmt = $conn->prepare("
    SELECT b.* 
    FROM user_badges ub
    JOIN badges b ON ub.badge_id = b.id
    WHERE ub.user_id = ?
    ORDER BY ub.earned_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$badges = [];
while ($row = $result->fetch_assoc()) {
    $badges[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'description' => $row['description'],
        'icon' => $row['icon']
    ];
}
$stmt->close();

// Get total completed lessons
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM user_progress WHERE user_id = ? AND completed = TRUE");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$completed = $result->fetch_assoc();
$total_completed = intval($completed['total']);
$stmt->close();

echo json_encode([
    'success' => true,
    'streak' => $streak,
    'badges' => $badges,
    'total_completed' => $total_completed
]);

$conn->close();
?>