<?php
require_once 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['user_id']) || !isset($data['course_id']) || !isset($data['lesson_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required fields'
    ]);
    exit();
}

$user_id = intval($data['user_id']);
$course_id = intval($data['course_id']);
$lesson_id = intval($data['lesson_id']);

$conn = getDBConnection();

// Insert or update progress
$stmt = $conn->prepare("
    INSERT INTO user_progress (user_id, course_id, lesson_id, completed, completed_at)
    VALUES (?, ?, ?, TRUE, NOW())
    ON DUPLICATE KEY UPDATE completed = TRUE, completed_at = NOW()
");
$stmt->bind_param("iii", $user_id, $course_id, $lesson_id);

if ($stmt->execute()) {
    // Update user points
    $stmt2 = $conn->prepare("UPDATE users SET total_points = total_points + 10 WHERE id = ?");
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $stmt2->close();
    
    // Check if course is completed
    $stmt3 = $conn->prepare("
        SELECT 
            COUNT(DISTINCT l.id) as total_lessons,
            COUNT(DISTINCT up.lesson_id) as completed_lessons
        FROM lessons l
        LEFT JOIN user_progress up ON l.id = up.lesson_id AND up.user_id = ? AND up.completed = TRUE
        WHERE l.course_id = ?
    ");
    $stmt3->bind_param("ii", $user_id, $course_id);
    $stmt3->execute();
    $result = $stmt3->get_result();
    $progress = $result->fetch_assoc();
    $stmt3->close();
    
    $course_completed = false;
    if ($progress['total_lessons'] > 0 && $progress['total_lessons'] == $progress['completed_lessons']) {
        $course_completed = true;
        
        // Award badge for completing course
        $stmt4 = $conn->prepare("
            INSERT IGNORE INTO user_badges (user_id, badge_id)
            SELECT ?, id FROM badges WHERE requirement_type = 'course_completed' LIMIT 1
        ");
        $stmt4->bind_param("i", $user_id);
        $stmt4->execute();
        $stmt4->close();
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Progress updated',
        'course_completed' => $course_completed
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update progress'
    ]);
}

$stmt->close();
$conn->close();
?>