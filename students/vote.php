<?php 
include '../config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['student'])) header("Location: login.php");

$student_id = $_SESSION['student'];

/* Check if already voted */
$check = $conn->query("SELECT has_voted FROM students WHERE id=$student_id")->fetch_assoc();
if($check['has_voted']){
    echo "<h2 style='text-align:center;margin-top:50px;color:red;'>You already voted!</h2>";
    exit;
}

/* Submit Vote */
if(isset($_POST['submit_vote'])){

    foreach($_POST as $position_id => $candidate_id){

        if($position_id != "submit_vote"){

            $conn->query("INSERT INTO votes 
                (student_id, candidate_id, position_id)
                VALUES ($student_id, $candidate_id, $position_id)");
        }
    }

    $conn->query("UPDATE students SET has_voted=1 WHERE id=$student_id");

    echo "<h2 style='text-align:center;margin-top:50px;color:green;'>Vote Submitted Successfully!</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Vote</title>

<style>
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family: Arial, sans-serif;
    }

    body{
        background:#f4f6f9;
        padding:40px;
    }

    h1{
        text-align:center;
        margin-bottom:30px;
        color:#1e293b;
    }

    .container{
        max-width:700px;
        margin:auto;
    }

    .card{
        background:white;
        padding:20px;
        margin-bottom:20px;
        border-radius:10px;
        box-shadow:0 5px 15px rgba(0,0,0,0.05);
    }

    .card h3{
        margin-bottom:15px;
        color:#2563eb;
    }

    .candidate{
        margin-bottom:10px;
        padding:8px;
        border-radius:6px;
        transition:0.2s;
    }

    .candidate:hover{
        background:#f1f5f9;
    }

    input[type="radio"]{
        margin-right:8px;
    }

    .submit-btn{
        display:block;
        width:100%;
        padding:15px;
        border:none;
        border-radius:8px;
        background:#2563eb;
        color:white;
        font-size:16px;
        cursor:pointer;
        transition:0.3s;
        margin-top:20px;
    }

    .submit-btn:hover{
        background:#1d4ed8;
        transform:translateY(-2px);
    }

</style>
</head>
<body>

<h1>Cast Your Vote</h1>

<div class="container">
<form method="POST">

<?php
$positions = $conn->query("SELECT * FROM positions");

while($pos = $positions->fetch_assoc()){
    echo "<div class='card'>";
    echo "<h3>".$pos['position_name']."</h3>";

    $candidates = $conn->query("SELECT * FROM candidates WHERE position_id=".$pos['id']);

    while($can = $candidates->fetch_assoc()){
        echo "<div class='candidate'>";
        echo "<label>";
        echo "<input type='radio' 
                name='".$pos['id']."' 
                value='".$can['id']."' required>";
        echo $can['name'];
        echo "</label>";
        echo "</div>";
    }

    echo "</div>";
}
?>

<button class="submit-btn" name="submit_vote">Submit Vote</button>

</form>
</div>

</body>
</html>