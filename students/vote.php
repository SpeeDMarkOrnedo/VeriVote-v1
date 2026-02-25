<?php
include '../config.php';

if(session_status() === PHP_SESSION_NONE) session_start();

if(!isset($_SESSION['student'])){
    header("Location: registration.php");
    exit();
}

$student_id = $_SESSION['student'];

/* Check if already voted */
$student = $conn->query("SELECT has_voted FROM students WHERE id=$student_id")->fetch_assoc();
if($student['has_voted']){
    session_destroy();
    echo "
    <style>
    @keyframes bounceFade {
        0% { opacity: 0; transform: translateY(-50px); }
        50% { opacity: 1; transform: translateY(10px); }
        70% { transform: translateY(-5px); }
        100% { transform: translateY(0); }
    }
    .vote-message { text-align:center; margin-top:50px; color:red; font-size:24px; font-weight:bold; animation:bounceFade 1s ease; }
    .next-student { text-align:center; margin-top:20px; font-size:18px; animation:bounceFade 1s ease 0.5s forwards; opacity:0; }
    .next-student a { text-decoration:none; color:blue; font-weight:bold; }
    .next-student a:hover { text-decoration:underline; }
    </style>
    ";
    echo "<div class='vote-message'>Your vote has been counted! You already voted!</div>";
    echo "<div class='next-student'><a href='registration.php'>Next Student Register</a></div>";
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
    $conn->query("UPDATE students SET has_voted=1 WHERE id=$student_id");
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
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0 auto;
    background: #f4f6f9;
    padding: 20px;
    max-width: 700px;
    margin-top: 200px;
}
.logo { display:block; margin:0 auto 20px; width:120px; }
h1 { text-align:center; margin-bottom:30px; }
.card {
    background: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    display: none;
}
.card.active { display: block; }

.candidates-wrapper { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 15px; }
.candidate { display:flex; align-items:center; gap:10px; padding:10px; border:1px solid #eee; border-radius:6px; flex:1 1 200px; cursor:pointer; transition:0.2s; }
.candidate:hover { background:#f0f8ff; border-color:#a0c4ff; }
.candidate input[type="radio"] { display:none; }
.candidate-photo { width:60px; height:60px; object-fit:cover; border-radius:50%; border:2px solid #ccc; }
.candidate input[type="radio"]:checked + .candidate-label { font-weight:bold; color:#007bff; }
.candidate-label { display:flex; align-items:center; gap:10px; }

button {
    padding: 12px 20px;
    margin-top:20px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
}
#next-btn { background:#007bff; color:#fff; }
#next-btn:hover { background:#0056b3; }
#submit-btn { background:green; color:#fff; display:none; }
#submit-btn:hover { background:darkgreen; }
</style>
</head>
<body>
<img src="Logo.png" class="logo" alt="">
<h1>Cast Your Vote</h1>

<form method="POST" id="voteForm">

<?php
$positions = $conn->query("SELECT * FROM positions");
$position_array = [];
while($pos = $positions->fetch_assoc()){
    $position_array[] = $pos; // store for JS
    echo "<div class='card'>";
    echo "<h3>".$pos['position_name']."</h3>";
    $candidates = $conn->query("SELECT * FROM candidates WHERE position_id=".$pos['id']);
    echo "<div class='candidates-wrapper'>";
    while($can = $candidates->fetch_assoc()){
        $photo = !empty($can['photo']) ? "../uploads/".$can['photo'] : "../uploads/default.png";
        echo "<label class='candidate'>";
        echo "<input type='radio' name='".$pos['id']."' value='".$can['id']."' required>";
        echo "<div class='candidate-label'>";
        echo "<img src='".$photo."' alt='".$can['name']."' class='candidate-photo'>";
        echo $can['name'];
        echo "</div></label>";
    }
    echo "</div></div>"; // card
}
?>

<button type="button" id="next-btn">Next</button>
<button type="submit" id="submit-btn" name="submit_vote">Submit Vote</button>
</form>

<script>
let cards = document.querySelectorAll('.card');
let current = 0;
cards[current].classList.add('active');

const nextBtn = document.getElementById('next-btn');
const submitBtn = document.getElementById('submit-btn');

nextBtn.addEventListener('click', () => {
    // check if a candidate is selected
    let selected = cards[current].querySelector('input[type="radio"]:checked');
    if(!selected){
        alert("Please select a candidate before proceeding!");
        return;
    }
    cards[current].classList.remove('active');
    current++;
    if(current < cards.length){
        cards[current].classList.add('active');
        if(current == cards.length - 1){
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'block';
        }
    }
});
</script>
</body>
</html>