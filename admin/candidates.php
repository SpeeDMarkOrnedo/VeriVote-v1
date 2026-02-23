<?php 
include '../config.php';

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

if(isset($_POST['add'])){
    $name = $conn->real_escape_string($_POST['name']);
    $position = (int)$_POST['position_id'];

    $conn->query("INSERT INTO candidates (name, position_id) 
                  VALUES ('$name', $position)");

    header("Location: candidates.php?success=added");
    exit();
}

if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];

    $conn->query("DELETE FROM candidates WHERE id=$id");

    header("Location: candidates.php?success=deleted");
    exit();
}

if(isset($_POST['update'])){
    $id = (int)$_POST['id'];
    $name = $conn->real_escape_string($_POST['name']);
    $position = (int)$_POST['position_id'];

    $conn->query("UPDATE candidates 
                  SET name='$name', position_id=$position 
                  WHERE id=$id");

    header("Location: candidates.php?success=updated");
    exit();
}

$candidates = $conn->query("
    SELECT candidates.*, positions.position_name
    FROM candidates
    LEFT JOIN positions ON candidates.position_id = positions.id
    ORDER BY candidates.id DESC
");
?>

<!DOCTYPE html>
<html>
    <head>
    <title>Manage Candidates</title>
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

<h2 class="page-title">Manage Candidates</h2>

<h3>Add Candidate</h3>

<form method="POST">
    <input type="text" name="name" placeholder="Candidate Name" required>

    <select name="position_id" required>
        <?php
        $positions = $conn->query("SELECT * FROM positions");
        while($p = $positions->fetch_assoc()){
            echo "<option value='".$p['id']."'>".$p['position_name']."</option>";
        }
        ?>
    </select>

    <button type="submit" name="add" class="btn btn-primary">
        Add Candidate
    </button>
</form>

<hr>

<h3>Existing Candidates</h3>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Position</th>
    <th>Actions</th>
</tr>

<?php while($row = $candidates->fetch_assoc()){ ?>
<tr>
    <td><?= $row['id']; ?></td>

    <td>
    <?php if(isset($_GET['edit']) && $_GET['edit'] == $row['id']){ ?>
        
        <form method="POST" style="display:flex; gap:5px;">
            <input type="hidden" name="id" value="<?= $row['id']; ?>">
            <input type="text" name="name" value="<?= $row['name']; ?>" required>

            <select name="position_id">
                <?php
                $positions2 = $conn->query("SELECT * FROM positions");
                while($p2 = $positions2->fetch_assoc()){
                    $selected = ($p2['id'] == $row['position_id']) ? "selected" : "";
                    echo "<option value='".$p2['id']."' $selected>".$p2['position_name']."</option>";
                }
                ?>
            </select>

            <button type="submit" name="update" class="btn btn-success">Save</button>
            <a href="candidates.php" class="btn btn-cancel">Cancel</a>
        </form>

    <?php } else { 
        echo $row['name']; 
    } ?>
    </td>

    <td><?= $row['position_name']; ?></td>

    <td>
    <?php if(!isset($_GET['edit'])){ ?>
        <a href="candidates.php?edit=<?= $row['id']; ?>" class="btn btn-success">Edit</a>
        <a href="candidates.php?delete=<?= $row['id']; ?>" 
           onclick="return confirm('Delete this candidate?')" 
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