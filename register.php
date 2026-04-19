<?php
include "db.php";

if (isset($_POST['register'])) {

    $user = $_POST['username'];
    $pass = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Password match check
    if ($pass !== $confirm) {
        $error = "Passwords do not match!";
    } else {

        // Check if user already exists
        $check = $conn->query("SELECT * FROM users WHERE username='$user'");

        if ($check->num_rows > 0) {
            $error = "Username already exists!";
        } else {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);

            $conn->query("INSERT INTO users (username, password) VALUES ('$user','$hashed')");
            header("Location: login.php");
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Weather App</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-box {
            width: 340px;
            padding: 30px;
            border-radius: 20px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            text-align: center;
            color: white;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        h2 {
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 10px;
            border: none;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: none;
            background: #00c851;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #009933;
        }

        a {
            color: #fff;
            display: block;
            margin-top: 15px;
            text-decoration: none;
        }

        .error {
            background: rgba(255,0,0,0.2);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="register-box">

    <h2>🌦 Create Account</h2>

    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="👤 Username" required>
        <input type="password" name="password" placeholder="🔒 Password" required>
        <input type="password" name="confirm_password" placeholder="🔒 Confirm Password" required>

        <button name="register">Register</button>
    </form>

    <a href="login.php">Already have account? Login</a>

</div>

</body>
</html>