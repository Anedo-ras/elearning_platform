<?php
require_once 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User ID required'
    ]);
    exit();
}

$user_id = intval($data['user_id']);
$today = date('Y-m-d');

$conn = getDBConnection();

// Check if activity already logged today
$stmt = $conn->prepare("SELECT * FROM streaks WHERE user_id = ? AND streak_date = ?");
$stmt->bind_param("is", $user_id, $today);
$stmt->execute();
$result = $stmt->get_result();

$new_streak = false;

if ($result->num_rows === 0) {
    // Log today's activity
    $stmt2 = $conn->prepare("INSERT INTO streaks (user_id, streak_date) VALUES (?, ?)");
    $stmt2->bind_param("is", $user_id, $today);
    $stmt2->execute();
    $stmt2->close();
    
    // Get yesterday's date
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    
    // Check if user was active yesterday
    $stmt3 = $conn->prepare("SELECT * FROM streaks WHERE user_id = ? AND streak_date = ?");
    $stmt3->bind_param("is", $user_id, $yesterday);
    $stmt3->execute();
    $result3 = $stmt3->get_result();
    
    if ($result3->num_rows > 0) {
        // Increment streak
        $stmt4 = $conn->prepare("UPDATE users SET current_streak = current_streak + 1 WHERE id = ?");
        $stmt4->bind_param("i", $user_id);
        $stmt4->execute();
        $stmt4->close();
    } else {
        // Reset streak to 1
        $stmt5 = $conn->prepare("UPDATE users SET current_streak = 1 WHERE id = ?");
        $stmt5->bind_param("i", $user_id);
        $stmt5->execute();
        $stmt5->close();
    }
    
    $stmt3->close();
    $new_streak = true;
}
$stmt->close();

// Get current streak
$stmt6 = $conn->prepare("SELECT current_streak FROM users WHERE id = ?");
$stmt6->bind_param("i", $user_id);
$stmt6->execute();
$result6 = $stmt6->get_result();
$user = $result6->fetch_assoc();
$current_streak = intval($user['current_streak']);
$stmt6->close();

// Check for streak badges
if ($current_streak == 7) {
    $stmt7 = $conn->prepare("
        INSERT IGNORE INTO user_badges (user_id, badge_id)
        SELECT ?, id FROM badges WHERE requirement_type = 'streak_days' AND requirement_value = 7
    ");
    $stmt7->bind_param("i", $user_id);
    $stmt7->execute();
    $stmt7->close();
} elseif ($current_streak == 30) {
    $stmt8 = $conn->prepare("
        INSERT IGNORE INTO user_badges (user_id, badge_id)
        SELECT ?, id FROM badges WHERE requirement_type = 'streak_days' AND requirement_value = 30
    ");
    $stmt8->bind_param("i", $user_id);
    $stmt8->execute();
    $stmt8->close();
}

// Update last activity date
$stmt9 = $conn->prepare("UPDATE users SET last_activity_date = ? WHERE id = ?");
$stmt9->bind_param("si", $today, $user_id);
$stmt9->execute();
$stmt9->close();

echo json_encode([
    'success' => true,
    'streak' => $current_streak,
    'new_streak' => $new_streak
]);

$conn->close();
?>