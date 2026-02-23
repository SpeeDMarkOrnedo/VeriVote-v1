<?php 
include '../config.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

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
        align-items: center; /* center logos and inputs */
    }

    /* Container for two logos side by side */
    .logo-container {
        display: flex;
        gap: 15px; /* space between logos */
        margin-bottom: 20px;
    }

    .logo-container img {
        width: 100px;
        height: 100px;
        object-fit: contain;
    }

    input[type="text"], input[type="password"] {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        width: 100%;
    }

    input[type="text"]:focus, input[type="password"]:focus {
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
        width: 100%;
    }

    button:hover {
        background-color: #0056b3;
    }

    .message {
        margin-top: 10px;
        font-weight: bold;
        color: red;
        text-align: center;
    }
</style>

<form method="POST">
    <!-- Two logos side by side -->
    <div class="logo-container">
 
    <img src="../includes/MinSULogo.png" alt="Logo 1">
        <img src="../includes/Comelec.png" alt="Logo 2">
    </div>

    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>

<?php
if(isset($_POST['login'])){
    $user = $_POST['username'];
    $pass = md5($_POST['password']);
    
    $result = $conn->query("SELECT * FROM admin WHERE username='$user' AND password='$pass'");
    
    if($result->num_rows > 0){
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        echo "<div class='message'>Invalid login</div>";
    }
}
?>