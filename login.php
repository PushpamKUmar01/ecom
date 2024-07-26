<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $user_sql = "SELECT * FROM users WHERE phone='$phone'";
    $admin_sql = "SELECT * FROM admin WHERE email='$phone'";

    $user_result = $conn->query($user_sql);
    $admin_result = $conn->query($admin_sql);

    if ($user_result->num_rows > 0) {
        $row = $user_result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['is_admin'] = $row['is_admin'];
            header("Location: index.php");
        } else {
            echo "Invalid password";
        }
    } elseif ($admin_result->num_rows > 0) {
        $row = $admin_result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            header("Location: admin.php");
        } else {
            echo "Invalid password";
        }
    } else {
        echo "No user found with that phone number or email";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/login_out.css">
</head>
<body>
    <form method="POST" action="">
        <h2>Login</h2>
        Phone or Email: <input type="text" name="phone" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Login</button>
        <a href="register.php">Create an account</a>
    </form>
</body>
</html>