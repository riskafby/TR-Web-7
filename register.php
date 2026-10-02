<?php
session_start();
 
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';


$dirPath = __DIR__ . '/data';
$jsonFile = $dirPath . '/users.json';

if (!is_dir($dirPath)) {
    mkdir($dirPath, 0777, true);
}


if (!file_exists($jsonFile)) {
    file_put_contents($jsonFile, json_encode([]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    
    if (empty($name) || empty($email) || empty($password)) {
        $error = "Semua kolom wajib diisi!";
    } 

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
     
        $users = json_decode(file_get_contents($jsonFile), true) ?? [];

        $emailExists = false;
        foreach ($users as $u) {
            if (strtolower($u['email']) === strtolower($email)) {
                $emailExists = true;
                break;
            }
        }

        if ($emailExists) {
            $error = "Email sudah terdaftar!";
        } else {
 
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $newUser = [
                'id' => uniqid(),
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword
            ];

            $users[] = $newUser;
            file_put_contents($jsonFile, json_encode($users, JSON_PRETTY_PRINT));

            $success = "Registrasi berhasil! Silakan login.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Tugas Rutin 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card">
        <h2>Register</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="index.php">Login di sini</a></p>
    </div>
</body>
</html>