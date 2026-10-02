<?php
session_start();


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Tugas Rutin 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card dashboard-card">
        <h2>Selamat Datang, <?= $user['name'] ?>! 👋</h2>
        <p>Anda berhasil login ke sistem.</p>
        <hr>
        <div class="user-info">
            <p><strong>Nama:</strong> <?= $user['name'] ?></p>
            <p><strong>Email:</strong> <?= $user['email'] ?></p>
        </div>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>