<?php

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$response = array();


if ($method === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    
    $teacher_id = $_POST['teacher_id'];
    $password = $_POST['password'];
    
    if (empty($teacher_id) || empty($password)) {
        $response['success'] = false;
        $response['message'] = 'Please fill in all fields';
        echo json_encode($response);
        exit;
    }

    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM teachers WHERE teacher_id = ?");
    $stmt->bind_param("s", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $teacher = $result->fetch_assoc();
        
        if (password_verify($password, $teacher['password'])) {
            $_SESSION['teacher_db_id'] = $teacher['id'];
            $_SESSION['teacher_id'] = $teacher['teacher_id'];
            $_SESSION['teacher_name'] = $teacher['full_name'];
            $_SESSION['user_type'] = 'teacher';
            
            $response['success'] = true;
            $response['message'] = 'Login successful';
        } else {
            $response['success'] = false;
            $response['message'] = 'Invalid teacher ID or password';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Invalid teacher ID or password';
    }
    
    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}

if ($method === 'GET' && isset($_SESSION['teacher_id']) && !isset($_GET['action'])) {
    
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT teacher_id, full_name, email, phone_number, subject FROM teachers WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['teacher_db_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $teacher = $result->fetch_assoc();
        $response['success'] = true;
        $response['data'] = $teacher;
    } else {
        $response['success'] = false;
        $response['message'] = 'Teacher not found';
    }
    
    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}

if ($method === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_students' && isset($_SESSION['teacher_id'])) {
    
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT admission_number, full_name FROM students ORDER BY full_name");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $students = array();
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    
    $response['success'] = true;
    $response['data'] = $students;
    
    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}
if ($method === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_all_students' && isset($_SESSION['teacher_id'])) {
    
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT admission_number, full_name, date_of_enrollment FROM students ORDER BY full_name");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $students = array();
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    
    $response['success'] = true;
    $response['data'] = $students;
    
    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}

if ($method === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_behavior' && isset($_SESSION['teacher_id'])) {
    
    $student_admission = $_POST['student_admission'];
    $behavior_date = $_POST['behavior_date'];
    $behavior_type = $_POST['behavior_type'];
    $comments = $_POST['comments'];
    $teacher_id = $_SESSION['teacher_id'];
    
    if (empty($student_admission) || empty($behavior_date) || empty($behavior_type)) {
        $response['success'] = false;
        $response['message'] = 'Please fill in all required fields';
        echo json_encode($response);
        exit;
    }
    
    $conn = getDBConnection();
    $stmt = $conn->prepare("INSERT INTO behavior_records (student_admission, teacher_id, behavior_date, behavior_type, comments) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $student_admission, $teacher_id, $behavior_date, $behavior_type, $comments);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Behavior record added successfully';
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to add behavior record';
    }
    
    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}

if ($method === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_behaviors' && isset($_SESSION['teacher_id'])) {
    
    $teacher_id = $_SESSION['teacher_id'];
    
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT br.*, s.full_name as student_name FROM behavior_records br JOIN students s ON br.student_admission = s.admission_number WHERE br.teacher_id = ? ORDER BY br.behavior_date DESC LIMIT 50");
    $stmt->bind_param("s", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $behaviors = array();
    while ($row = $result->fetch_assoc()) {
        $behaviors[] = $row;
    }
    
    $response['success'] = true;
    $response['data'] = $behaviors;
    
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