<?php
// Database Connection Test Script
// Place this in the root folder and visit: http://localhost/elearning-underprivileged/test_db.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Use central config so tests use the same credentials as the app
require_once __DIR__ . '/config.php';

echo "<h1>Database Connection Test</h1>";

// Test 1: PHP Version
echo "<h2>1. PHP Version</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Status: <span style='color: green;'>✓ OK</span><br><br>";

// Test 2: MySQLi Extension
echo "<h2>2. MySQLi Extension</h2>";
if (extension_loaded('mysqli')) {
    echo "MySQLi Extension: Loaded<br>";
    echo "Status: <span style='color: green;'>✓ OK</span><br><br>";
} else {
    echo "MySQLi Extension: <span style='color: red;'>NOT LOADED</span><br>";
    echo "Status: <span style='color: red;'>✗ FAILED</span><br><br>";
    exit();
}

// Test 3: Database Connection using getDBConnection()
echo "<h2>3. Database Connection</h2>";
// getDBConnection() will exit() with JSON on failure; capture with output buffering
ob_start();
$conn = null;
try {
    $conn = getDBConnection();
    $output = ob_get_clean();
    // If getDBConnection emitted JSON error, show it
    if ($output) {
        echo $output;
    }
} catch (Exception $e) {
    ob_end_clean();
    echo "Connection: <span style='color: red;'>FAILED</span><br>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Status: <span style='color: red;'>✗ FAILED</span><br><br>";
    exit();
}

if (!$conn) {
    echo "Connection: <span style='color: red;'>FAILED</span><br>";
    echo "Status: <span style='color: red;'>✗ FAILED</span><br><br>";
    exit();
} else {
    echo "Connection: <span style='color: green;'>SUCCESS</span><br>";
    echo "Status: <span style='color: green;'>✓ OK</span><br><br>";
}

// Test 4: Check Tables
echo "<h2>4. Database Tables</h2>";
$tables = ['users', 'courses', 'lessons', 'user_progress', 'quiz_questions', 'quiz_attempts', 'badges', 'user_badges', 'streaks', 'user_logins'];

$allTablesExist = true;
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "Table '$table': <span style='color: green;'>EXISTS</span><br>";
    } else {
        echo "Table '$table': <span style='color: red;'>MISSING</span><br>";
        $allTablesExist = false;
    }
}

if ($allTablesExist) {
    echo "Status: <span style='color: green;'>✓ ALL TABLES OK</span><br><br>";
} else {
    echo "Status: <span style='color: red;'>✗ SOME TABLES MISSING - Run schema.sql!</span><br><br>";
}

// Test 5: Check Admin User
echo "<h2>5. Admin User</h2>";
$result = $conn->query("SELECT id, username, email, is_admin FROM users WHERE username = 'admin'");
if ($result && $result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    echo "Admin user exists:<br>";
    echo "- ID: " . $admin['id'] . "<br>";
    echo "- Username: " . $admin['username'] . "<br>";
    echo "- Email: " . $admin['email'] . "<br>";
    echo "- Is Admin: " . ($admin['is_admin'] ? 'Yes' : 'No') . "<br>";
    echo "Status: <span style='color: green;'>✓ OK</span><br><br>";
    echo "<strong>Login credentials:</strong><br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br><br>";
} else {
    echo "Admin user: <span style='color: red;'>NOT FOUND</span><br>";
    echo "Status: <span style='color: red;'>✗ FAILED - Run schema.sql!</span><br><br>";
}

// Test 6: Check Courses
echo "<h2>6. Sample Data (Courses)</h2>";
$result = $conn->query("SELECT COUNT(*) as total FROM courses");
if ($result) {
    $row = $result->fetch_assoc();
    echo "Total courses: " . $row['total'] . "<br>";
    if ($row['total'] > 0) {
        echo "Status: <span style='color: green;'>✓ OK</span><br><br>";
        
        // Show first 3 courses
        $courses = $conn->query("SELECT title, slug FROM courses LIMIT 3");
        echo "Sample courses:<br>";
        while ($course = $courses->fetch_assoc()) {
            echo "- " . $course['title'] . " (" . $course['slug'] . ")<br>";
        }
    } else {
        echo "Status: <span style='color: orange;'>⚠ NO COURSES - Run schema.sql!</span><br><br>";
    }
}

// Test 7: Test Password Verification
echo "<h2>7. Password Hash Test</h2>";
$testPassword = 'admin123';
$result = $conn->query("SELECT password FROM users WHERE username = 'admin'");
if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $storedHash = $user['password'];
    
    echo "Stored hash: " . substr($storedHash, 0, 30) . "...<br>";
    
    if (password_verify($testPassword, $storedHash)) {
        echo "Password verification: <span style='color: green;'>SUCCESS</span><br>";
        echo "Status: <span style='color: green;'>✓ OK</span><br><br>";
    } else {
        echo "Password verification: <span style='color: red;'>FAILED</span><br>";
        echo "Status: <span style='color: red;'>✗ PASSWORD MISMATCH</span><br><br>";
    }
} else {
    echo "Cannot test password - admin user not found<br><br>";
}

// Test 8: Write Test
echo "<h2>8. Write Permission Test</h2>";
$testFile = __DIR__ . '/debug.log';
if (file_put_contents($testFile, "Test log entry: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND)) {
    echo "Log file write: <span style='color: green;'>SUCCESS</span><br>";
    echo "Log location: " . $testFile . "<br>";
    echo "Status: <span style='color: green;'>✓ OK</span><br><br>";
} else {
    echo "Log file write: <span style='color: red;'>FAILED</span><br>";
    echo "Status: <span style='color: red;'>✗ CHECK PERMISSIONS</span><br><br>";
}

echo "<hr>";
echo "<h2>Final Summary</h2>";
if ($conn && $allTablesExist) {
    echo "<span style='color: green; font-size: 20px;'>✓ ALL TESTS PASSED</span><br><br>";
    echo "<strong>Next Steps:</strong><br>";
    echo "1. Go to: <a href='login.html'>login.html</a><br>";
    echo "2. Login with username: <strong>admin</strong> and password: <strong>admin123</strong><br>";
    echo "3. Check this file after login to see the debug log: debug.log<br>";
    echo "4. Check MySQL Workbench table 'user_logins' to see login records<br>";
} else {
    echo "<span style='color: red; font-size: 20px;'>✗ SOME TESTS FAILED</span><br><br>";
    echo "<strong>Fix Required:</strong><br>";
    echo "1. Make sure XAMPP Apache and MySQL are running<br>";
    echo "2. Import schema.sql into MySQL Workbench<br>";
    echo "3. Refresh this page to test again<br>";
}

$conn->close();
?>