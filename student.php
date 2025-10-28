<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

// Get JSON data from request
$input = file_get_contents('php://input');
error_log('Raw input: ' . $input); // Debug log

$data = json_decode($input, true);
error_log('Decoded data: ' . print_r($data, true)); // Debug log

if (!$data) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Invalid JSON data: ' . json_last_error_msg()
    ]);
    exit;
}

$action = $data['action'] ?? '';

switch ($action) {
    case 'register':
        // Validate required fields
        if (empty($data['admission']) || empty($data['name']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Missing required fields'
            ]);
            exit;
        }

        try {
            mysqli_begin_transaction($conn);

            // Insert parent first
            $parentName = $data['parentName'] ?? '';
            $parentPhone = $data['parentPhone'] ?? '';
            $parentEmail = $data['parentEmail'] ?? '';

            $parentStmt = $conn->prepare("INSERT INTO parents (full_name, phone, email) VALUES (?, ?, ?)");
            $parentStmt->bind_param("sss", $parentName, $parentPhone, $parentEmail);
            
            if (!$parentStmt->execute()) {
                throw new Exception("Failed to insert parent: " . $conn->error);
            }
            
            $parentId = $conn->insert_id;

            // Then insert student
            $studentStmt = $conn->prepare("INSERT INTO students (admission_number, full_name, date_of_birth, date_of_enrollment, parent_id, password) VALUES (?, ?, ?, ?, ?, ?)");
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $studentStmt->bind_param("ssssss", 
                $data['admission'],
                $data['name'],
                $data['dob'],
                $data['enrollment'],
                $parentId,
                $hashedPassword
            );

            if (!$studentStmt->execute()) {
                throw new Exception("Failed to insert student: " . $conn->error);
            }

            mysqli_commit($conn);
            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            mysqli_rollback($conn);
            error_log('Registration error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'error' => 'Registration failed: ' . $e->getMessage()
            ]);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        break;
}
?>