<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: contacts.php");
    exit();
}

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === "admin" && $password === "123456") {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['login_time'] = date('Y-m-d H:i:s');

        header("Location: contacts.php");
        exit();
    } else {
        $login_error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Manajemen Kontak</title>
    <style>
        .login-form { border:1px solid #ccc; padding:20px; max-width:400px; }
        .error { color:red; }
    </style>
</head>
<body>
    <h2>Login Sistem Manajemen Kontak</h2>

    <div class="login-form">
        <?php if (!empty($login_error)): ?>
            <p class="error"><?php echo htmlspecialchars($login_error); ?></p>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" value="admin" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" value="123456" required><br><br>

            <input type="submit" value="Login">
        </form>

        <p><small>Hint: username = admin, password = 123456</small></p>
    </div>
</body>
</html>
