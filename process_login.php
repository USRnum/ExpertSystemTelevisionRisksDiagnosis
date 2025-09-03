<?php
session_start();
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'); // Mencegah XSS
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'); // Mencegah XSS

    // Menggunakan prepared statements untuk mencegah SQL Injection
    $query = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['username'] = $username;
        header("Location: dashboard_admin.php"); // Redirect ke halaman dashboard admin
        exit();
    } else {
        echo "Username atau password salah.";
    }

    $stmt->close();
}

$conn->close();
?>
