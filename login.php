<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE username='$user'");

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();

        if (password_verify($pass, $row['password'])) {
            $_SESSION['user'] = $user;
            header("Location: index.php");
        } else {
            $error = "Wrong Password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Weather App</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            width: 320px;
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
            background: #ff7b00;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #ff5500;
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

<div class="login-box">

    <h2>🌦 Weather Login</h2>

    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="👤 Username" required>
        <input type="password" name="password" placeholder="🔒 Password" required>

        <button name="login">Login</button>
    </form>

    <a href="register.php">Create new account</a>

</div>

</body>
</html>