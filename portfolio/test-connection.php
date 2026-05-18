<?php
require_once 'config.php';

echo "<h1>Database Connection Test</h1>";

// Test connection
if ($conn->connect_error) {
    echo "<p style='color: red;'>❌ Connection failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>✓ Database connected successfully!</p>";
    
    // Test tables
    $tables = ['contact_messages', 'projects', 'skills', 'experience'];
    
    echo "<h2>Tables Check:</h2>";
    foreach ($tables as $table) {
        $result = $conn->query("SELECT 1 FROM $table LIMIT 1");
        if ($result) {
            echo "<p style='color: green;'>✓ Table '$table' exists</p>";
        } else {
            echo "<p style='color: orange;'>⚠ Table '$table' not found</p>";
        }
    }
}

$conn->close();
?>