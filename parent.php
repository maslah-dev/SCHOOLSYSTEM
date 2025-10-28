<?php

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$response = array();

if ($method === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    
    if (empty($phone_number) || empty($password)) {
        $response['success'] = false;
        $response['message'] = 'Please fill in all fields';
        echo json_encode($response);
        exit;
    }

     $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM parents WHERE phone_number = ?");
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $parent = $result->fetch_assoc();
        
        if (password_verify($password, $parent['password'])) {
            $_SESSION['parent_id'] = $parent['id'];
            $_SESSION['parent_name'] = $parent['full_name'];
            $_SESSION['parent_phone'] = $parent['phone_number'];
            $_SESSION['student_admission'] = $parent['student_admission'];
            $_SESSION['user_type'] = 'parent';
            
            $response['success'] = true;
            $response['message'] = 'Login successful';
        } else {
            $response['success'] = false;
            $response['message'] = 'Invalid phone number or password';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Invalid phone number or password';
    }
    
    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}   

if ($method === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $student_admission = $_POST['student_admission'];
    $password = $_POST['password'];
    
    if (empty($full_name) || empty($phone_number) || empty($password) || empty($student_admission)) {
        $response['success'] = false;
        $response['message'] = 'Please fill in all required fields';
        echo json_encode($response);
        exit;
    }
    
    $conn = getDBConnection();
    
   
    $stmt = $conn->prepare("SELECT admission_number FROM students WHERE admission_number = ?");
    $stmt->bind_param("s", $student_admission);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $response['success'] = false;
        $response['message'] = 'Student admission number not found';
        $stmt->close();
        $conn->close();
        echo json_encode($response);
        exit;
    }
    
     $stmt = $conn->prepare("SELECT id FROM parents WHERE phone_number = ?");
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = 'Phone number already registered';
        $stmt->close();
        $conn->close();
        echo json_encode($response);
        exit;
    }
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO parents (full_name, phone_number, email, password, student_admission) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $full_name, $phone_number, $email, $hashed_password, $student_admission);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Registration successful! You can now login.';
    } else {
        $response['success'] = false;
        $response['message'] = 'Registration failed. Please try again.';
    }
    
    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}

if ($method === 'GET' && isset($_SESSION['parent_id'])) {
    
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT p.full_name, p.phone_number, p.email, s.* FROM parents p JOIN students s ON p.student_admission = s.admission_number WHERE p.id = ?");
    $stmt->bind_param("i", $_SESSION['parent_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $response['success'] = true;
        $response['data'] = $data;
    } else {
        $response['success'] = false;
        $response['message'] = 'Data not found';
    }
    
    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}

if ($method === 'POST' && isset($_POST['action']) && $_POST['action'] === 'logout') {
    session_destroy();
    $response['success'] = true;
    $response['message'] = 'Logged out successfully';
    echo json_encode($response);
    exit;
}
?>