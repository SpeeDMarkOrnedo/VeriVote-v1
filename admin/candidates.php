<?php
include '../config.php';

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

date_default_timezone_set("Asia/Manila");

/* ================= ADD ================= */
if(isset($_POST['add'])){

    $name = $conn->real_escape_string($_POST['name']);
    $position = (int)$_POST['position_id'];
    $photoName = "";

    if(!empty($_FILES['photo']['name'])){
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];

        if(in_array($ext,$allowed)){
            $photoName = time() . "_" . rand(100,999) . "." . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/".$photoName);
        }
    }

    $conn->query("INSERT INTO candidates (name, position_id, photo)
                  VALUES ('$name', $position, '$photoName')");

    header("Location: candidates.php?success=added");
    exit();
}

/* ================= DELETE ================= */
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];

    $old = $conn->query("SELECT photo FROM candidates WHERE id=$id")->fetch_assoc();
    if($old && !empty($old['photo']) && file_exists("../uploads/".$old['photo'])){
        unlink("../uploads/".$old['photo']);
    }

    $conn->query("DELETE FROM candidates WHERE id=$id");

    header("Location: candidates.php?success=deleted");
    exit();
}

/* ================= UPDATE ================= */
if(isset($_POST['update'])){
    $id = (int)$_POST['id'];
    $name = $conn->real_escape_string($_POST['name']);
    $position = (int)$_POST['position_id'];

    $photoSQL = "";

    if(!empty($_FILES['photo']['name'])){

        $old = $conn->query("SELECT photo FROM candidates WHERE id=$id")->fetch_assoc();
        if($old && !empty($old['photo']) && file_exists("../uploads/".$old['photo'])){
            unlink("../uploads/".$old['photo']);
        }

        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];

        if(in_array($ext,$allowed)){
            $newName = time() . "_" . rand(100,999) . "." . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/".$newName);
            $photoSQL = ", photo='$newName'";
        }
    }

    $conn->query("UPDATE candidates 
                  SET name='$name', position_id=$position $photoSQL
                  WHERE id=$id");

    header("Location: candidates.php?success=updated");
    exit();
}

/* ================= FETCH ================= */
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
    <div class="success-msg">Action completed successfully.</div>
<?php } ?>

<h2 class="page-title">Manage Candidates</h2>

<!-- ================= ADD FORM ================= -->
<h3>Add Candidate</h3>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Candidate Name" required>

    <select name="position_id" required>
        <?php
        $positions = $conn->query("SELECT * FROM positions");
        while($p = $positions->fetch_assoc()){
            echo "<option value='".$p['id']."'>".$p['position_name']."</option>";
        }
        ?>
    </select>

    <input type="file" name="photo" accept="image/*" required>

    <button type="submit" name="add" class="btn btn-primary">
        Add Candidate
    </button>
</form>

<hr>

<!-- ================= TABLE ================= -->
<h3>Existing Candidates</h3>

<table>
<tr>
    <th>Photo</th>
    <th>ID</th>
    <th>Name</th>
    <th>Position</th>
    <th>Actions</th>
</tr>

<?php while($row = $candidates->fetch_assoc()){ ?>
<tr>

<td>
<?php if(!empty($row['photo'])){ ?>
    <img src="../uploads/<?= $row['photo']; ?>"
         width="50"
         height="50"
         style="border-radius:50%; object-fit:cover;">
<?php } ?>
</td>

<td><?= $row['id']; ?></td>

<td>
<?php if(isset($_GET['edit']) && $_GET['edit'] == $row['id']){ ?>
    
    <form method="POST" enctype="multipart/form-data" style="display:flex; gap:5px; align-items:center;">
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

        <input type="file" name="photo" accept="image/*">

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