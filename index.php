<?php
session_start();

$jsonFile = __DIR__ . '/data/users.json';

if (!isset($_SESSION['user']) && isset($_COOKIE['remember_user'])) {
    $email = $_COOKIE['remember_user'];
    $users = json_decode(file_get_contents($jsonFile), true) ?? [];
    foreach ($users as $u) {
        if ($u['email'] === $email) {
            $_SESSION['user'] = [
                'id' => $u['id'],
                'name' => $u['name'],
                'email' => $u['email']
            ];
            break;
        }
    }
}

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $error = "Semua kolom wajib diisi!";
    } else {
        $users = json_decode(file_get_contents($jsonFile), true) ?? [];
        $foundUser = null;

        foreach ($users as $u) {
            if (strtolower($u['email']) === strtolower($email)) {
                $foundUser = $u;
                break;
            }
        }

        if ($foundUser && password_verify($password, $foundUser['password'])) {
           
            $_SESSION['user'] = [
                'id' => $foundUser['id'],
                'name' => $foundUser['name'],
                'email' => $foundUser['email']
            ];

            if ($remember) {
                setcookie('remember_user', $foundUser['email'], time() + (86400 * 30), "/"); // 30 hari
            }

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Email atau password salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Tugas Rutin 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card">
        <h2>Login</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group checkbox-group">
                <label><input type="checkbox" name="remember"> Remember Me</label>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>