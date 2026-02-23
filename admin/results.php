<?php 
include '../config.php';

if(!isset($_SESSION['admin'])) header("Location: login.php");

date_default_timezone_set("Asia/Manila");
?>

<!DOCTYPE html>
<html><head>
    <title>Election Results</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<div class="main-content">

<h1 class="page-title">Election Results</h1>

<div class="timestamp">
    Generated at: <?= date("F d, Y h:i A"); ?>
</div>

<?php
$positions = $conn->query("SELECT * FROM positions");

while($pos = $positions->fetch_assoc()){

    echo "<div class='position-card'>";
    echo "<h2>".$pos['position_name']."</h2>";

    $results = $conn->query("
        SELECT candidates.id, candidates.name,
        COUNT(votes.id) as total_votes
        FROM candidates
        LEFT JOIN votes ON candidates.id = votes.candidate_id
        WHERE candidates.position_id=".$pos['id']."
        GROUP BY candidates.id
        ORDER BY total_votes DESC
    ");

    $maxVotes = 0;
    $candidates = [];

    while($row = $results->fetch_assoc()){
        $candidates[] = $row;
        if($row['total_votes'] > $maxVotes){
            $maxVotes = $row['total_votes'];
        }
    }

    foreach($candidates as $row){

        $isWinner = ($row['total_votes'] == $maxVotes && $maxVotes > 0);

        if($isWinner){
            echo "<div class='candidate winner'>";
            echo "<span>".$row['name']." - ".$row['total_votes']." votes</span>";
            echo "<span class='badge badge-win'>WINNER</span>";
        } else {
            echo "<div class='candidate loser'>";
            echo "<span>".$row['name']." - ".$row['total_votes']." votes</span>";
            echo "<span class='badge badge-lose'>LOSER</span>";
        }

        echo "</div>";
    }

    echo "</div>";
}
?>

</div>
</body>
</html>