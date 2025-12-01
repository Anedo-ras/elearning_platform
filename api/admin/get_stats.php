<?php
require_once '../config.php';

$conn = getDBConnection();

// Get total users
$result = $conn->query("SELECT COUNT(*) as total FROM users WHERE is_admin = FALSE");
$total_users = $result->fetch_assoc()['total'];

// Get total courses
$result = $conn->query("SELECT COUNT(*) as total FROM courses WHERE is_active = TRUE");
$total_courses = $result->fetch_assoc()['total'];

// Get total lessons
$result = $conn->query("SELECT COUNT(*) as total FROM lessons");
$total_lessons = $result->fetch_assoc()['total'];

// Get active users today
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT COUNT(DISTINCT user_id) as total FROM streaks WHERE streak_date = ?");
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
$active_today = $result->fetch_assoc()['total'];
$stmt->close();

echo json_encode([
    'success' => true,
    'stats' => [
        'total_users' => intval($total_users),
        'total_courses' => intval($total_courses),
        'total_lessons' => intval($total_lessons),
        'active_today' => intval($active_today)
    ]
]);

$conn->close();
?>