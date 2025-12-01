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

// Get all active courses with progress
$query = "
    SELECT 
        c.*,
        COUNT(DISTINCT l.id) as total_lessons,
        COUNT(DISTINCT up.lesson_id) as completed_lessons,
        CASE 
            WHEN COUNT(DISTINCT l.id) > 0 
            THEN (COUNT(DISTINCT up.lesson_id) * 100.0 / COUNT(DISTINCT l.id))
            ELSE 0 
        END as progress
    FROM courses c
    LEFT JOIN lessons l ON c.id = l.course_id
    LEFT JOIN user_progress up ON l.id = up.lesson_id AND up.user_id = ? AND up.completed = TRUE
    WHERE c.is_active = TRUE
    GROUP BY c.id
    ORDER BY c.id
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'slug' => $row['slug'],
        'description' => $row['description'],
        'icon' => $row['icon'],
        'youtube_link' => $row['youtube_link'],
        'difficulty' => $row['difficulty'],
        'total_lessons' => intval($row['total_lessons']),
        'completed_lessons' => intval($row['completed_lessons']),
        'progress' => round(floatval($row['progress']), 2)
    ];
}

echo json_encode([
    'success' => true,
    'courses' => $courses
]);

$stmt->close();
$conn->close();
?>