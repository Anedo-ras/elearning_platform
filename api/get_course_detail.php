<?php
require_once 'config.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if (empty($slug) || $user_id === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing parameters'
    ]);
    exit();
}

$conn = getDBConnection();

// Get course details
$stmt = $conn->prepare("SELECT * FROM courses WHERE slug = ? AND is_active = TRUE");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Course not found'
    ]);
    $stmt->close();
    $conn->close();
    exit();
}

$course = $result->fetch_assoc();
$course_id = $course['id'];
$stmt->close();

// Get lessons with completion status
$stmt = $conn->prepare("
    SELECT 
        l.*,
        CASE WHEN up.completed IS NOT NULL THEN up.completed ELSE FALSE END as completed
    FROM lessons l
    LEFT JOIN user_progress up ON l.id = up.lesson_id AND up.user_id = ?
    WHERE l.course_id = ?
    ORDER BY l.lesson_number
");
$stmt->bind_param("ii", $user_id, $course_id);
$stmt->execute();
$result = $stmt->get_result();

$lessons = [];
while ($row = $result->fetch_assoc()) {
    $lessons[] = [
        'id' => $row['id'],
        'lesson_number' => $row['lesson_number'],
        'title' => $row['title'],
        'content' => $row['content'],
        'video_link' => $row['video_link'],
        'duration_minutes' => $row['duration_minutes'],
        'completed' => (bool)$row['completed']
    ];
}
$stmt->close();

// Get quiz questions
$stmt = $conn->prepare("SELECT * FROM quiz_questions WHERE course_id = ? ORDER BY id");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

$quiz = [];
while ($row = $result->fetch_assoc()) {
    $quiz[] = [
        'id' => $row['id'],
        'question' => $row['question'],
        'option_a' => $row['option_a'],
        'option_b' => $row['option_b'],
        'option_c' => $row['option_c'],
        'option_d' => $row['option_d'],
        'correct_answer' => $row['correct_answer']
    ];
}
$stmt->close();

echo json_encode([
    'success' => true,
    'course' => $course,
    'lessons' => $lessons,
    'quiz' => $quiz
]);

$conn->close();
?>