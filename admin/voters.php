<?php
include '../config.php';

if(!isset($_SESSION['admin'])) header("Location: login.php");

if(isset($_POST['add'])){
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $course = $conn->real_escape_string($_POST['course']);

    $check = $conn->query("SELECT id FROM students WHERE student_id='$student_id'");

if($check->num_rows > 0){
    echo "<script>alert('Student ID already exists!'); window.location='voters.php';</script>";
    exit();
}else{
    $conn->query("INSERT INTO students (student_id, name, course) 
                  VALUES ('$student_id', '$name', '$course')");
    header("Location: voters.php?success=added");
    exit();
}

<<<<<<< HEAD
    header("Location: students.php?success=added");
    exit();
}

/* ============================
   DELETE
============================ */
=======
>>>>>>> 7d4b6d6aa2e54a7d7738cba960c6a41c7f402ad5
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM students WHERE id=$id");

    header("Location: students.php?success=deleted");
    exit();
}

if(isset($_POST['update'])){
    $id = (int)$_POST['id'];
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $course = $conn->real_escape_string($_POST['course']);

    $conn->query("UPDATE students 
                  SET student_id='$student_id', name='$name', course='$course' 
                  WHERE id=$id");

    header("Location: students.php?success=updated");
    exit();
}
<<<<<<< HEAD

$students = $conn->query("SELECT * FROM students ORDER BY id DESC");
=======
 
$voters = $conn->query("SELECT * FROM voters ORDER BY id DESC");
>>>>>>> 7d4b6d6aa2e54a7d7738cba960c6a41c7f402ad5
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Students</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, sans-serif;
}

body{
    display:flex;
    background:#f4f6f9;
}

/* SIDEBAR */
.sidebar{
    width:230px;
    height:100vh;
    background:#1e293b;
    color:white;
    position:fixed;
    display:flex;
    flex-direction:column;
    align-items:center;
}

.sidebar img{
    width:70px;
    margin:20px 0 10px;
}

.sidebar h2{
    font-size:18px;
    margin-bottom:20px;
}

.sidebar a{
    width:100%;
    padding:12px 20px;
    text-decoration:none;
    color:white;
    display:block;
    transition:0.3s;
}

.sidebar a:hover{
    background:#334155;
    padding-left:25px;
}

.sidebar a.active{
    background:#2563eb;
}

.sidebar .logout{
    margin-top:auto;
    background:#dc2626;
}

.sidebar .logout:hover{
    background:#b91c1c;
}

/* MAIN */
.main-content{
    margin-left:230px;
    padding:40px;
    width:100%;
}

.card{
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

.page-title{
    margin-bottom:20px;
}

/* FORM */
.form-inline{
    display:flex;
    gap:10px;
    margin-bottom:20px;
}

input{
    padding:8px;
    border:1px solid #ccc;
    border-radius:6px;
}

/* BUTTONS */
button, .btn{
    padding:8px 12px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    text-decoration:none;
    color:white;
}

.btn-primary{ background:#2563eb; }
.btn-success{ background:#16a34a; }
.btn-danger{ background:#dc2626; }
.btn-cancel{ background:#64748b; }

button:hover, .btn:hover{
    opacity:0.85;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}

th, td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:left;
}

th{
    background:#f1f5f9;
}

/* SUCCESS */
.success-msg{
    padding:10px;
    background:#dcfce7;
    color:#166534;
    border-radius:6px;
    margin-bottom:15px;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <img src="Logo.png" alt="">
    <h2>VeriVote Admin</h2>

    <a href="dashboard.php">Dashboard</a>
    <a href="positions.php">Manage Positions</a>
    <a href="candidates.php">Manage Candidates</a>
    <a href="students.php" class="active">Manage Students</a>
    <a href="results.php">View Results</a>
    <a href="logout.php" class="logout">Logout</a>
</div>

<!-- MAIN -->
<div class="main-content">
<div class="card">

<?php if(isset($_GET['success'])){ ?>
<div class="success-msg">
    Action completed successfully.
</div>
<?php } ?>

<h2 class="page-title">Manage Students</h2>

<h3>Add Student</h3>
<form method="POST" class="form-inline">
    <input type="text" name="student_id" placeholder="Student ID" required>
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="text" name="course" placeholder="Course" required>
    <button type="submit" name="add" class="btn-primary">Add Student</button>
</form>

<hr>

<h3 style="margin-top:20px;">Existing Students</h3>

<table>
<tr>
    <th>ID</th>
    <th>Student ID</th>
    <th>Name</th>
    <th>Course</th>
    <th>Actions</th>
</tr>

<?php while($row = $students->fetch_assoc()){ ?>
<tr>
    <td><?= $row['id']; ?></td>

    <?php if(isset($_GET['edit']) && $_GET['edit'] == $row['id']){ ?>
    <form method="POST">
        <td>
            <input type="hidden" name="id" value="<?= $row['id']; ?>">
            <input type="text" name="student_id" value="<?= $row['student_id']; ?>">
        </td>
        <td><input type="text" name="name" value="<?= $row['name']; ?>"></td>
        <td><input type="text" name="course" value="<?= $row['course']; ?>"></td>
        <td>
            <button type="submit" name="update" class="btn-success">Save</button>
            <a href="students.php" class="btn-cancel">Cancel</a>
        </td>
    </form>
    <?php } else { ?>
        <td><?= $row['student_id']; ?></td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['course']; ?></td>
        <td>
            <a href="students.php?edit=<?= $row['id']; ?>" class="btn btn-success">Edit</a>
            <a href="students.php?delete=<?= $row['id']; ?>"
               onclick="return confirm('Delete this student?')"
               class="btn btn-danger">Delete</a>
        </td>
    <?php } ?>
</tr>
<?php } ?>

</table>

</div>
</div>

</body>
</html>