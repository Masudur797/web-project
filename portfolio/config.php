<?php
// ==================== DATABASE CONFIG ====================

// Database Details
define('DB_HOST', 'localhost');      // Hostname (usually localhost)
define('DB_USER', 'root');           // Database username
define('DB_PASS', '');               // Database password (empty for XAMPP)
define('DB_NAME', 'portfolio_db');   // Database name

// Site Details
define('SITE_URL', 'http://localhost/portfolio');
define('SITE_NAME', '[Your Name] Portfolio');
define('ADMIN_EMAIL', 'your-email@example.com');

// Error Reporting (Development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ==================== DATABASE CONNECTION ====================

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }
    
    // Set charset
    $conn->set_charset('utf8mb4');
    
    // Success message (remove in production)
    // echo "Database connected successfully!";
    
} catch (Exception $e) {
    die('Database Error: ' . $e->getMessage());
}

// ==================== HELPER FUNCTIONS ====================

// Sanitize input
function sanitize($input) {
    global $conn;
    return htmlspecialchars($conn->real_escape_string($input));
}

// Validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Log error
function log_error($message) {
    $log_file = 'error.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND);
}

// Check if POST request
function is_post() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

// Redirect
function redirect($url) {
    header("Location: $url");
    exit;
}

// Get client IP
function get_client_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

?>



<?php
// Get all messages
function get_all_messages() {
    global $conn;
    $sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get unread messages
function get_unread_messages() {
    global $conn;
    $sql = "SELECT * FROM contact_messages WHERE is_read = 0 ORDER BY created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Mark message as read
function mark_message_read($id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}

// Get project count
function get_project_count() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as count FROM projects");
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Get featured projects
function get_featured_projects($limit = 3) {
    global $conn;
    $sql = "SELECT * FROM projects WHERE featured = 1 ORDER BY created_at DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Add project
function add_project($data) {
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO projects (title, description, technologies, live_url, github_url, image_url, featured, status) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    
    $stmt->bind_param(
        'ssssssii',
        $data['title'],
        $data['description'],
        $data['technologies'],
        $data['live_url'],
        $data['github_url'],
        $data['image_url'],
        $data['featured'],
        $data['status']
    );
    
    return $stmt->execute();
}

?>


