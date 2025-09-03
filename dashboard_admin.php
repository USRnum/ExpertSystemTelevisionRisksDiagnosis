<?php
include 'koneksi.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Arial', sans-serif;
      background: #f4f6f9;
    }
    .vh-100 {
      height: 100vh !important;
    }
    .sidebar {
      min-height: 100vh;
      background: linear-gradient(45deg, #007bff, #0056b3);
      color: white;
      position: relative;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }
    .sidebar h1 {
      font-size: 1.8rem;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
    }
    .sidebar .nav-link {
      font-size: 1rem;
      padding: 10px 15px;
      border-radius: 5px;
      transition: background 0.3s, color 0.3s;
    }
    .sidebar .nav-link:hover {
      background: rgba(255, 255, 255, 0.2);
      color: #f8f9fa;
    }
    .sidebar .logout-btn {
      font-size: 1rem;
      margin-top: auto;
      padding: 10px 15px;
      background: linear-gradient(45deg, #007bff, #0056b3);
      border: none;
      border-radius: 5px;
      transition: background 0.3s, transform 0.2s;
      position: absolute;
      bottom: 20px;
      left: 20px;
      color: white;
    }
    .sidebar .logout-btn:hover {
      background: #c82333;
      transform: scale(1.05);
    }
    .main-content {
      background-color: #ffffff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }
    footer {
      background: #e9ecef;
      font-size: 0.9rem;
      padding: 10px 0;
    }
    .exit-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      width: 30px;
      height: 30px;
      text-align: center;
      padding: 0;
      color: white;
      background: linear-gradient(45deg, #007bff, #0056b3);
      border: none;
      border-radius: 50%;
      font-size: 1rem;
      line-height: 30px;
      transition: background 0.3s, transform 0.2s;
    }
    .exit-btn:hover {
      background: #c82333;
      transform: scale(1.1);
    }
    @media (max-width: 768px) {
      .sidebar {
        position: absolute;
        z-index: 1000;
        width: 100%;
        display: none;
      }
      .sidebar.active {
        display: block;
      }
    }
  </style>
</head>
<body>
  <div class="d-flex vh-100 flex-column flex-md-row">
    <!-- Sidebar -->
    <nav class="sidebar p-3 flex-shrink-0" id="sidebar">
      <h1>Admin Dashboard</h1>
      <ul class="nav flex-column mt-3">
        <li class="nav-item"><a class="nav-link text-white" href="dashboard_admin.php">Home</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="edit_gejala.php">Edit Gejala</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="edit_kerusakan.php">Edit Kerusakan</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="edit_pengetahuan.php">Edit Pengetahuan</a></li>
      </ul>
      <form action="logout.php" method="POST" style="display: inline;">
        <button type="submit" class="logout-btn">Logout</button>
      </form>
      <button class="exit-btn d-md-none" id="exitSidebar">X</button>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
      <button class="btn btn-primary d-md-none mb-3" id="toggleSidebar">Toggle Menu</button>
      <section class="main-content mb-4">
        <h2 class="h4">Welcome, Admin</h2>
        <p>Gunakan menu navigasi untuk mengakses beberapa halaman</p>
        <p>Petunjuk Penggunaan:</p>
        <ul>
          <li>Untuk mengedit gejala, klik pada menu "Edit Gejala".</li>
          <li>Untuk mengedit kerusakan, klik pada menu "Edit Kerusakan".</li>
          <li>Untuk mengedit pengetahuan, klik pada menu "Edit Pengetahuan".</li>
          <li>Untuk logout, klik tombol "Logout" di bagian bawah sidebar.</li>
        </ul>
        <p>Jika anda pengguna perangkat mobile seperti android atau iphone <br> anda dapat menekan tombol toggle menu untuk menampilkan menu - menu yang tersedia</p>
      </section>
    </main>
  </div>


  <!-- Bootstrap JS -->
  <script src="js/bootstrap.bundle.min.js"></script>
  <script>


    // Toggle sidebar for small screens
    const toggleSidebar = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const exitSidebar = document.getElementById('exitSidebar');

    toggleSidebar.addEventListener('click', () => {
      sidebar.classList.toggle('active');
    });

    exitSidebar.addEventListener('click', () => {
      sidebar.classList.remove('active');
    });
  </script>
</body>
</html>
