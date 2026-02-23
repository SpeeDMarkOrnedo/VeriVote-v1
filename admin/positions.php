<?php 
include '../config.php';
if(!isset($_SESSION['admin'])) header("Location: login.php"); 
if(isset($_POST['add'])){
    $name = $conn->real_escape_string($_POST['position_name']);
    $conn->query("INSERT INTO positions (position_name) VALUES ('$name')");
    header("Location: positions.php?success=added");
}
 
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM positions WHERE id=$id");
    header("Location: positions.php?success=deleted");
}
 
if(isset($_POST['update'])){
    $id = (int)$_POST['id'];
    $name = $conn->real_escape_string($_POST['position_name']);
    $conn->query("UPDATE positions SET position_name='$name' WHERE id=$id");
    header("Location: positions.php?success=updated");
}
 
$positions = $conn->query("SELECT * FROM positions ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Positions</title> 
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<div class="main-content">
<div class="card">

<?php if(isset($_GET['success'])){ ?>
    <div class="success-msg">
        Action completed successfully!
    </div>
<?php } ?>

<h2 class="page-title">Manage Positions</h2>

<h3>Add Position</h3>

<form method="POST">
    <input type="text" name="position_name" placeholder="Enter position name" required>
    <button name="add" class="btn btn-primary">Add Position</button>
</form>

<hr>

<h3>Existing Positions</h3>

<table>
<tr>
    <th>ID</th>
    <th>Position Name</th>
    <th>Actions</th>
</tr>

<?php while($row = $positions->fetch_assoc()) { ?>
<tr>
    <td><?= $row['id']; ?></td>
    <td>

<?php if(isset($_GET['edit']) && $_GET['edit'] == $row['id']){ ?>
    <form method="POST" style="display:flex; gap:5px;">
        <input type="hidden" name="id" value="<?= $row['id']; ?>">
        <input type="text" name="position_name" value="<?= $row['position_name']; ?>" required>
        <button name="update" class="btn btn-success">Save</button>
        <a href="positions.php" class="btn btn-cancel">Cancel</a>
    </form>
<?php } else { 
    echo $row['position_name']; 
} ?>

    </td>
    <td>
<?php if(!isset($_GET['edit'])){ ?>
    <a href="positions.php?edit=<?= $row['id']; ?>" class="btn btn-success">Edit</a>
    <a href="positions.php?delete=<?= $row['id']; ?>" 
       class="btn btn-danger"
       onclick="return confirm('Are you sure you want to delete this position?')">
       Delete
    </a>
<?php } ?>
    </td>
</tr>
<?php } ?>
</table>

</div>
</div>

</body>
</html>