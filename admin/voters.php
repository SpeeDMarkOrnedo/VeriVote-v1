<?php
include '../config.php';
include '../includes/auth_admin.php'; 
function generateNumber($length = 6){
    return str_pad(mt_rand(0, 999999), $length, '0', STR_PAD_LEFT);
}

/* ============================
   CREATE
============================ */
if(isset($_POST['add'])){
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $number = generateNumber();

    $conn->query("INSERT INTO voters (student_id, voter_number) 
                  VALUES ('$student_id', '$number')");

    header("Location: voters.php?success=added");
    exit();
}

/* ============================
   DELETE
============================ */
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];

    $conn->query("DELETE FROM voters WHERE id=$id");

    header("Location: voters.php?success=deleted");
    exit();
}

/* ============================
   UPDATE
============================ */
if(isset($_POST['update'])){
    $id = (int)$_POST['id'];
    $student_id = $conn->real_escape_string($_POST['student_id']);

    $conn->query("UPDATE voters SET student_id='$student_id' WHERE id=$id");

    header("Location: voters.php?success=updated");
    exit();
}

/* ============================
   FETCH DATA
============================ */
$voters = $conn->query("SELECT * FROM voters ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Voters</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<div class="main-content">
<div class="card">

<?php if(isset($_GET['success'])){ ?>
    <div class="success-msg">
        Action completed successfully.
    </div>
<?php } ?>

<h2 class="page-title">Manage Voters</h2>

<h3>Add Voter</h3>
<form method="POST" class="form-inline">
    <input type="text" name="student_id" placeholder="Student ID" required>
    <button type="submit" name="add" class="btn btn-primary">
        Add Voter
    </button>
</form>

<hr>

<h3>Existing Voters</h3>

<table>
<tr>
    <th>ID</th>
    <th>Student ID</th>
    <th>Voter Number (Password)</th>
    <th>Actions</th>
</tr>

<?php while($row = $voters->fetch_assoc()){ ?>
<tr>
    <td><?= $row['id']; ?></td>

    <td>
    <?php if(isset($_GET['edit']) && $_GET['edit'] == $row['id']){ ?>
        <form method="POST" style="display:flex; gap:5px;">
            <input type="hidden" name="id" value="<?= $row['id']; ?>">
            <input type="text" name="student_id" value="<?= $row['student_id']; ?>" required>
            <button type="submit" name="update" class="btn btn-success">Save</button>
            <a href="voters.php" class="btn btn-cancel">Cancel</a>
        </form>
    <?php } else { 
        echo $row['student_id']; 
    } ?>
    </td>
    <td><span class="voter-number"><?= $row['voter_number']; ?></span></td>
    <td>
    <?php if(!isset($_GET['edit'])){ ?>
        <a href="voters.php?edit=<?= $row['id']; ?>" class="btn btn-success">Edit</a>
        <a href="voters.php?delete=<?= $row['id']; ?>"
           onclick="return confirm('Delete this voter?')"
           class="btn btn-danger">Delete</a>
    <?php } ?>
    </td>
</tr>
<?php } ?>

</table>

</div>
</div>

</body>
</html>