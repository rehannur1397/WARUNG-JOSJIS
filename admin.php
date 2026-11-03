<?php
session_start();

$usernameBenar = "admin";
$passwordBenar = "Josjis123!";

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username === $usernameBenar && $password === $passwordBenar) {
        $_SESSION["admin"] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $pesan = "Username atau password salah!";
    }
}

if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Warung Josjis</title>
</head>
<body>

<h2>Login Admin Warung Josjis</h2>

<?php if (!empty($pesan)) : ?>
    <p><?php echo $pesan; ?></p>
<?php endif; ?>
<?php if (!isset($_SESSION["admin"])): ?>
<form method="POST">
    <input type="text" name="username"
           placeholder="Username" required>

    <br><br>

    <input type="password" name="password"
           placeholder="Password" required>

    <br><br>

    <button type="submit" name="login">
        Login
    </button>
</form>
<?php else: ?>

<h2>Selamat Datang, Admin!</h2>
<p>Login berhasil.</p>

<a href="admin.php?logout=1">Logout</a>

<?php endif; ?>

</body>
</html>