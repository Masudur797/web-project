<?php
// ==================== CONTACT FORM HANDLER ====================

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Include config
require_once 'config.php';

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Check if POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate & Sanitize
    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $message = trim($data['message'] ?? '');
    
    $errors = [];
    
    // Validation
    if (empty($name) || strlen($name) < 2) {
        $errors[] = 'Valid name required';
    }
    
    if (!validate_email($email)) {
        $errors[] = 'Valid email required';
    }
    
    if (empty($message) || strlen($message) < 10) {
        $errors[] = 'Message must be at least 10 characters';
    }
    
    // If errors, return
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        exit;
    }
    
    // Sanitize inputs
    $name = sanitize($name);
    $email = sanitize($email);
    $message = sanitize($message);
    $ip_address = get_client_ip();
    
    // Insert into database
    try {
        $stmt = $conn->prepare(
            "INSERT INTO contact_messages (name, email, message, ip_address) 
             VALUES (?, ?, ?, ?)"
        );
        
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        
        $stmt->bind_param('ssss', $name, $email, $message, $ip_address);
        
        if (!$stmt->execute()) {
            throw new Exception('Execute failed: ' . $stmt->error);
        }
        
        $stmt->close();
        
        // Send email (optional)
        send_contact_email($name, $email, $message);
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Message saved successfully! I will contact you soon.'
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        log_error('Contact form error: ' . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Server error. Please try again later.'
        ]);
    }
    
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
}

// ==================== EMAIL FUNCTION ====================

function send_contact_email($name, $email, $message) {
    $to = ADMIN_EMAIL;
    $subject = "New Portfolio Message from $name";
    
    $body = "
New message from your portfolio:\n\n
Name: $name\n
Email: $email\n
Message:\n
$message\n\n
---\n
IP: " . get_client_ip() . "\n
Time: " . date('Y-m-d H:i:s') . "\n
    ";
    
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    mail($to, $subject, $body, $headers);
}

?>