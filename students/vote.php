<?php
include '../config.php';

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure student is logged in
if(!isset($_SESSION['student'])){
    header("Location: registration.php");
    exit();
}

$student_id = $_SESSION['student'];

/* Check if already voted */
$student = $conn->query("SELECT has_voted FROM students WHERE id=$student_id")->fetch_assoc();
if($student['has_voted']){
    session_destroy(); // End session so next student can register
    echo "<h2 style='text-align:center;margin-top:50px;color:red;'>You already voted!</h2>";
    echo "<p style='text-align:center;'><a href='registration.php'>Next Student Register</a></p>";
    exit;
}

/* Submit Vote */
if(isset($_POST['submit_vote'])){
    foreach($_POST as $position_id => $candidate_id){
        if($position_id != "submit_vote"){
            $conn->query("INSERT INTO votes (student_id, candidate_id, position_id) 
                         VALUES ($student_id, $candidate_id, $position_id)");
        }
    }

    // Mark student as voted
    $conn->query("UPDATE students SET has_voted=1 WHERE id=$student_id");

    // End session
    session_destroy();

    echo "<h2 style='text-align:center;margin-top:50px;color:green;'>Vote Submitted Successfully!</h2>";
    echo "<p style='text-align:center;'><a href='registration.php'>Next Student Register</a></p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Vote</title> 
<link rel="stylesheet" href="../assets/css/studentVote.css">

</head>
<body>
<img src="Logo.png" class="logo" alt="">
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
        echo "<input type='radio' name='".$pos['id']."' value='".$can['id']."' required>";
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