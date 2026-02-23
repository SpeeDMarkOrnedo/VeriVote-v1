<?php include '../config.php'; ?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    form {
        background-color: #fff;
        padding: 30px 40px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
        gap: 15px;
        width: 300px;
    }

    input[type="text"] {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    input[type="text"]:focus {
        border-color: #007BFF;
        outline: none;
        box-shadow: 0 0 5px rgba(0,123,255,0.5);
    }

    button {
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #007BFF;
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }

    .message {
        margin-top: 10px;
        font-weight: bold;
        color: green;
    }

    .error {
        color: red;
    }
</style>

<form method="POST">
    <img src="Logo.png" alt="">
    <input type="text" name="student_id" placeholder="Student ID" required>
    <input type="text" name="number" placeholder="Number" required>
    <button type="submit" name="register">Register</button>
</form>

<?php
if(isset($_POST['register'])){
    $student_id = $_POST['student_id'];
    $number = $_POST['number'];

    $sql = "INSERT INTO students (student_id, number) VALUES ('$student_id', '$number')";
    if($conn->query($sql)){
        echo "<div class='message'>Registered successfully!</div>";
    } else {
        echo "<div class='message error'>Error or already registered!</div>";
    }
}
?>