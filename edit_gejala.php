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
                <h2 class="h4">Edit Gejala</h2>
                <form method="POST">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover ">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Gejala</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // PAGINATION SETUP
                                $limit = 6; // jumlah data per halaman
                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                if ($page < 1) $page = 1;
                                $offset = ($page - 1) * $limit;

                                // Hitung total data
                                $countQuery = "SELECT COUNT(*) as total FROM gejala_kerusakan";
                                $countResult = mysqli_query($conn, $countQuery);
                                $totalData = 0;
                                if ($countResult) {
                                    $row = mysqli_fetch_assoc($countResult);
                                    $totalData = $row['total'];
                                }
                                $totalPages = ceil($totalData / $limit);

                                // Query data dengan LIMIT dan OFFSET
                                $query = "SELECT * FROM gejala_kerusakan LIMIT $limit OFFSET $offset";
                                $result = mysqli_query($conn, $query);
                                $no = 1 + $offset;

                                // Handle delete request
                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ID_GEJALA']) && isset($_POST['delete'])) {
                                    $id_gejala = $_POST['ID_GEJALA'];
                                    $delete_query = "DELETE FROM gejala_kerusakan WHERE ID_GEJALA = '$id_gejala'";
                                    if (mysqli_query($conn, $delete_query)) {
                                        echo "<script>alert('Data deleted successfully'); window.location.href='edit_gejala.php';</script>";
                                    } else {
                                        echo "<script>alert('Failed to delete data');</script>";
                                    }
                                }

                                // Handle update request
                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ID_GEJALA']) && isset($_POST['update'])) {
                                    $id_gejala = $_POST['ID_GEJALA'];
                                    $nama_gejala = mysqli_real_escape_string($conn, $_POST['NAMA_GEJALA']);
                                    $update_query = "UPDATE gejala_kerusakan SET NAMA_GEJALA = '$nama_gejala' WHERE ID_GEJALA = '$id_gejala'";
                                    if (mysqli_query($conn, $update_query)) {
                                        echo "<script>alert('Data updated successfully'); window.location.href='edit_gejala.php';</script>";
                                    } else {
                                        echo "<script>alert('Failed to update data');</script>";
                                    }
                                }

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    if (isset($_POST['edit']) && $_POST['ID_GEJALA'] == $row['ID_GEJALA']) {
                                        echo "<td>
                                                <input type='hidden' name='ID_GEJALA' value='" . $row['ID_GEJALA'] . "'>
                                                <input type='text' class='form-control' name='NAMA_GEJALA' value='" . $row['NAMA_GEJALA'] . "' required>
                                              </td>";
                                        echo "<td>
                                                <button type='submit' name='update' class='btn btn-success btn-sm'>Save</button>
                                                <a href='edit_gejala.php' class='btn btn-secondary btn-sm'>Cancel</a>
                                              </td>";
                                    } else {
                                        echo "<td>" . $row['NAMA_GEJALA'] . "</td>";
                                        echo "<td>
                                                <form method='POST' style='display:inline;'>
                                                    <input type='hidden' name='ID_GEJALA' value='" . $row['ID_GEJALA'] . "'>
                                                    <button type='submit' name='edit' class='btn btn-warning btn-sm'>Edit</button>
                                                </form>
                                                <form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this data?\");'>
                                                    <input type='hidden' name='ID_GEJALA' value='" . $row['ID_GEJALA'] . "'>
                                                    <button type='submit' name='delete' class='btn btn-danger btn-sm'>Delete</button>
                                                </form>
                                              </td>";
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <form action="" method="POST" class="mt-4">
                    <div class="mb-3">
                        <label for="nama_gejala" class="form-label">Nama Gejala</label>
                        <input type="text" class="form-control" id="NAMA_GEJALA" name="NAMA_GEJALA" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Gejala</button>
                </form><br>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['NAMA_GEJALA']) && !isset($_POST['update']) && !isset($_POST['delete'])) {
                    $nama_gejala = mysqli_real_escape_string($conn, $_POST['NAMA_GEJALA']);
                    $insert_query = "INSERT INTO gejala_kerusakan (NAMA_GEJALA) VALUES ('$nama_gejala')";
                    if (mysqli_query($conn, $insert_query)) {
                        echo "<script>alert('Data added successfully'); window.location.href='edit_gejala.php';</script>";
                    } else {
                        echo "<script>alert('Failed to add data');</script>";
                    }
                }
                ?>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav>
                  <ul class="pagination justify-content-center">
                    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                      <a class="page-link" href="?page=<?php echo $page-1; ?>">Sebelumnya</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                      <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                      </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
                      <a class="page-link" href="?page=<?php echo $page+1; ?>">Selanjutnya</a>
                    </li>
                  </ul>
                </nav>
                <?php endif; ?>
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
