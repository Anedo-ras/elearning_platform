<?php
require_once '../config.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$conn = getDBConnection();

if ($action === 'list') {
    // Get all courses
    $result = $conn->query("SELECT * FROM courses ORDER BY id");
    
    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'courses' => $courses
    ]);
    
} elseif ($action === 'add') {
    // Add new course
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['title']) || !isset($data['slug'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required fields'
        ]);
        exit();
    }
    
    $title = $data['title'];
    $slug = $data['slug'];
    $description = isset($data['description']) ? $data['description'] : '';
    $youtube_link = isset($data['youtube_link']) ? $data['youtube_link'] : '';
    $icon = isset($data['icon']) ? $data['icon'] : '📚';
    $difficulty = isset($data['difficulty']) ? $data['difficulty'] : 'beginner';
    
    $stmt = $conn->prepare("INSERT INTO courses (title, slug, description, youtube_link, icon, difficulty) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $slug, $description, $youtube_link, $icon, $difficulty);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Course added successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add course'
        ]);
    }
    $stmt->close();
    
} elseif ($action === 'toggle') {
    // Toggle course active status
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['course_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Course ID required'
        ]);
        exit();
    }
    
    $course_id = intval($data['course_id']);
    $is_active = isset($data['is_active']) ? ($data['is_active'] ? 1 : 0) : 1;
    
    $stmt = $conn->prepare("UPDATE courses SET is_active = ? WHERE id = ?");
    $stmt->bind_param("ii", $is_active, $course_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Course status updated'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update course'
        ]);
    }
    $stmt->close();
    
} elseif ($action === 'delete') {
    // Delete course
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['course_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Course ID required'
        ]);
        exit();
    }
    
    $course_id = intval($data['course_id']);
    
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Course deleted successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete course'
        ]);
    }
    $stmt->close();
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid action'
    ]);
}

$conn->close();
?>