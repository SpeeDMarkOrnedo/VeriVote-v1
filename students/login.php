<?php 
include '../config.php';

if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Login</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(135deg,#1e293b,#008000);
}

.card{
    background:white;
    width:340px;
    padding:35px 30px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.25);
    text-align:center;
}

.card img{
    width:80px;
    margin-bottom:15px;
}

.card h2{
    margin-bottom:20px;
    color:#1e293b;
}

form{
    display:flex;
    flex-direction:column;
    gap:15px;
}

input{
    padding:12px;
    border:1px solid #d1d5db;
    border-radius:6px;
    font-size:15px;
    transition:0.3s;
}

input:focus{
    border-color:#2563eb;
    outline:none;
    box-shadow:0 0 8px rgba(37,99,235,0.3);
}

button{
    padding:12px;
    border:none;
    border-radius:6px;
    background:#2563eb;
    color:white;
    font-size:15px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

.message{
    margin-top:15px;
    padding:10px;
    border-radius:6px;
    font-size:14px;
}

.error{
    background:#fee2e2;
    color:#991b1b;
}

</style>
</head>

<body>

<div class="card">
    <img src="Logo.png" alt="Logo">
    <h2>Put your credentials to Enable to VOTE.</h2>

    <form method="POST">
        <input type="text" name="student_id" placeholder="Student ID" required> 
        <button type="submit" name="login">VOTE!</button>
    </form>

    <?php
    if(isset($_POST['login'])){
        $student_id = $_POST['student_id'];
        $number = $_POST['number'];

        $result = $conn->query("SELECT * FROM students WHERE student_id='$student_id' AND number='$number'");
        
        if($result->num_rows > 0){
            $data = $result->fetch_assoc();
            $_SESSION['student'] = $data['id'];
            header("Location: vote.php");
            exit();
        } else {
            echo "<div class='message error'>Invalid credentials</div>";
        }
    }
    ?>
</div>

</body>
</html>