<?php 
include '../config.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$totalStudents = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'];
$totalPositions = $conn->query("SELECT COUNT(*) as total FROM positions")->fetch_assoc()['total'];
$totalCandidates = $conn->query("SELECT COUNT(*) as total FROM candidates")->fetch_assoc()['total'];
$totalVotes = $conn->query("SELECT COUNT(*) as total FROM votes")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - VeriVote</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<div class="main-content">
    <h1 class="page-title">Admin Dashboard</h1>

    <div class="cards">
        <div class="card">
            <h2><?= $totalStudents ?></h2>
            <p>Total Students</p>
        </div>

        <div class="card">
            <h2><?= $totalPositions ?></h2>
            <p>Total Positions</p>
        </div>

        <div class="card">
            <h2><?= $totalCandidates ?></h2>
            <p>Total Candidates</p>
        </div>

        <div class="card">
            <h2><?= $totalVotes ?></h2>
            <p>Total Votes Cast</p>
        </div>
    </div>
</div>

</body>
</html>