<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    if ($is_admin) {
        $email = $_POST['email'];
        $sql = "INSERT INTO admin (name, email, password) VALUES ('$name', '$email', '$password')";
    } else {
        $sql = "INSERT INTO users (name, phone, password, is_admin) VALUES ('$name', '$phone', '$password', $is_admin)";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/login_out.css">
</head>
<body>
    <form method="POST" action="">
        <h2>Register</h2>
        Name: <input type="text" name="name" required><br>
        Phone: <input type="text" name="phone" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>