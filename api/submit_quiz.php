<?php
require_once 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['user_id']) || !isset($data['course_id']) || !isset($data['score']) || !isset($data['total'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required fields'
    ]);
    exit();
}

$user_id = intval($data['user_id']);
$course_id = intval($data['course_id']);
$score = intval($data['score']);
$total = intval($data['total']);

$conn = getDBConnection();

// Insert quiz attempt
$stmt = $conn->prepare("INSERT INTO quiz_attempts (user_id, course_id, score, total_questions) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiii", $user_id, $course_id, $score, $total);

if ($stmt->execute()) {
    // Award points based on score
    $points = $score * 5;
    $stmt2 = $conn->prepare("UPDATE users SET total_points = total_points + ? WHERE id = ?");
    $stmt2->bind_param("ii", $points, $user_id);
    $stmt2->execute();
    $stmt2->close();
    
    // Award Quiz Master badge if perfect score
    if ($score === $total) {
        $stmt3 = $conn->prepare("
            INSERT IGNORE INTO user_badges (user_id, badge_id)
            SELECT ?, id FROM badges WHERE requirement_type = 'quiz_perfect' LIMIT 1
        ");
        $stmt3->bind_param("i", $user_id);
        $stmt3->execute();
        $stmt3->close();
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Quiz submitted successfully',
        'points_earned' => $points
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to submit quiz'
    ]);
}

$stmt->close();
$conn->close();
?>