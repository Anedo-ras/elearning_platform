<?php
require_once 'config.php';

$conn = getDBConnection();

// Get top 10 users by points
$query = "
    SELECT username, total_points as points, current_streak
    FROM users
    WHERE is_admin = FALSE
    ORDER BY total_points DESC, current_streak DESC
    LIMIT 10
";

$result = $conn->query($query);

$leaderboard = [];
while ($row = $result->fetch_assoc()) {
    $leaderboard[] = [
        'username' => $row['username'],
        'points' => intval($row['points']),
        'streak' => intval($row['current_streak'])
    ];
}

echo json_encode([
    'success' => true,
    'leaderboard' => $leaderboard
]);

$conn->close();
?>