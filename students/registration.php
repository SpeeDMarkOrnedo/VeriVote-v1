<?php
include '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = "";

// HANDLE REGISTRATION
if(isset($_POST['register'])){
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $name       = $conn->real_escape_string($_POST['name']);
    $course     = $conn->real_escape_string($_POST['course']);

    // Check if student_id already exists
    $check = $conn->query("SELECT * FROM students WHERE student_id='$student_id'");
    if($check->num_rows > 0){
        $message = "Student ID already exists!";
    } else {
        // Insert student
        $conn->query("INSERT INTO students (student_id, name, course, has_voted) 
                      VALUES ('$student_id','$name','$course',0)");

        // Auto-login student
        $_SESSION['student'] = $conn->insert_id;

        // Redirect to voting page
        header("Location: vote.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Registration</title> 
<link rel="stylesheet" href="../assets/css/studentLogin.css">
</head>
<body>

<div class="card">
    <img src="../includes/MinSULogo.png" alt="Logo">
    <h2>Student Registration</h2>

    <form method="POST">
        <input type="text" name="student_id" placeholder="Student ID" required>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="course" placeholder="Course" required>
        <button type="submit" name="register">Register & Vote</button>
    </form>

    <?php if($message){ echo "<div class='message'>$message</div>"; } ?>
</div>

</body>
</html>