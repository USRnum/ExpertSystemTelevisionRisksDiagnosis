<?php
include 'koneksi.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body, html { height: 100%; margin: 0; font-family: 'Arial', sans-serif; background: #f4f6f9; }
    .vh-100 { height: 100vh !important; }
    .sidebar {
      min-height: 100vh; background: linear-gradient(45deg, #007bff, #0056b3); color: white;
      position: relative; box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }
    .sidebar h1 { font-size: 1.8rem; font-weight: bold; text-align: center; margin-bottom: 20px; }
    .sidebar .nav-link {
      font-size: 1rem; padding: 10px 15px; border-radius: 5px;
      transition: background 0.3s, color 0.3s;
    }
    .sidebar .nav-link:hover { background: rgba(255,255,255,0.2); color: #f8f9fa; }
    .sidebar .logout-btn {
      font-size: 1rem; margin-top: auto; padding: 10px 15px;
      background: linear-gradient(45deg, #007bff, #0056b3); border: none; border-radius: 5px;
      transition: background 0.3s, transform 0.2s; position: absolute; bottom: 20px; left: 20px; color: white;
    }
    .sidebar .logout-btn:hover { background: #c82333; transform: scale(1.05); }
    .main-content {
      background-color: #fff; border-radius: 10px; padding: 20px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px;
    }
    footer { background: #e9ecef; font-size: 0.9rem; padding: 10px 0; }
    .exit-btn {
      position: absolute; top: 10px; right: 10px; width: 30px; height: 30px; text-align: center; padding: 0;
      color: white; background: linear-gradient(45deg, #007bff, #0056b3); border: none; border-radius: 50%;
      font-size: 1rem; line-height: 30px; transition: background 0.3s, transform 0.2s;
    }
    .exit-btn:hover { background: #c82333; transform: scale(1.1); }
    @media (max-width: 768px) {
      .sidebar { position: absolute; z-index: 1000; width: 100%; display: none; }
      .sidebar.active { display: block; }
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
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4">Edit Kerusakan</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKerusakanModal">
          Tambah Kerusakan
        </button>
      </div>
      <?php
      // Handle delete request
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ID_KERUSAKAN']) && isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id_kerusakan = $_POST['ID_KERUSAKAN'];
        $delete_query = "DELETE FROM kerusakan WHERE ID_KERUSAKAN = '$id_kerusakan'";
        if (mysqli_query($conn, $delete_query)) {
          echo "<script>alert('Data deleted successfully'); window.location.href='edit_kerusakan.php';</script>";
        } else {
          echo "<script>alert('Failed to delete data');</script>";
        }
      }

      // Handle update request
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ID_KERUSAKAN']) && isset($_POST['action']) && $_POST['action'] === 'update') {
        $id_kerusakan = $_POST['ID_KERUSAKAN'];
        $nama_kerusakan = mysqli_real_escape_string($conn, $_POST['NAMA_KERUSAKAN']);
        $saran_perbaikan = mysqli_real_escape_string($conn, $_POST['SARAN_PERBAIKAN']);

        $update_query = "UPDATE kerusakan SET NAMA_KERUSAKAN = ?, SARAN_PERBAIKAN = ? WHERE ID_KERUSAKAN = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $nama_kerusakan, $saran_perbaikan, $id_kerusakan);
        $result = $stmt->execute();

        if ($result) {
          echo "<script>alert('Data updated successfully'); window.location.href='edit_kerusakan.php';</script>";
        } else {
          echo "<script>alert('Failed to update data');</script>";
        }
      }

      // Handle insert request
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['NAMA_KERUSAKAN']) && isset($_POST['SARAN_PERBAIKAN']) && !isset($_POST['action'])) {
        $nama_kerusakan = mysqli_real_escape_string($conn, $_POST['NAMA_KERUSAKAN']);
        $saran_perbaikan = mysqli_real_escape_string($conn, $_POST['SARAN_PERBAIKAN']);

        $insert_query = "INSERT INTO kerusakan (NAMA_KERUSAKAN, SARAN_PERBAIKAN) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ss", $nama_kerusakan, $saran_perbaikan);
        $result = $stmt->execute();

        if ($result) {
          echo "<script>alert('Data added successfully'); window.location.href='edit_kerusakan.php';</script>";
        } else {
          echo "<script>alert('Failed to add data');</script>";
        }
      }

      $limit = 10;
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      if ($page < 1) $page = 1;
      $offset = ($page - 1) * $limit;

      $countQuery = "SELECT COUNT(*) as total FROM kerusakan";
      $countResult = mysqli_query($conn, $countQuery);
      $totalData = 0;
      if ($countResult) {
        $row = mysqli_fetch_assoc($countResult);
        $totalData = $row['total'];
      }
      $totalPages = ceil($totalData / $limit);

      $query = "SELECT * FROM kerusakan LIMIT $limit OFFSET $offset";
      $result = mysqli_query($conn, $query);
      ?>

      <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
          <thead class="bg-primary text-white">
            <tr>
              <th>No</th>
              <th>Nama Kerusakan</th>
              <th>Saran Perbaikan</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $query = "SELECT * FROM kerusakan";
            $result = mysqli_query($conn, $query);
            $no = 1 + $offset;
            while ($row = mysqli_fetch_assoc($result)) {
              if (isset($_GET['edit']) && $_GET['edit'] == $row['ID_KERUSAKAN']) {
                // Edit mode
                echo "<tr>";
                echo "<form method='POST'>";
                echo "<td>" . $no++ . "</td>";
                echo "<td><input type='text' name='NAMA_KERUSAKAN' value='" . htmlspecialchars($row['NAMA_KERUSAKAN']) . "' class='form-control'></td>";
                echo "<td><input type='text' name='SARAN_PERBAIKAN' value='" . htmlspecialchars($row['SARAN_PERBAIKAN']) . "' class='form-control'></td>";
                echo "<td>
                  <input type='hidden' name='ID_KERUSAKAN' value='" . $row['ID_KERUSAKAN'] . "'>
                  <input type='hidden' name='action' value='update'>
                  <button type='submit' class='btn btn-success btn-sm'>Save</button>
                  <a href='edit_kerusakan.php' class='btn btn-secondary btn-sm'>Cancel</a>
                </td>";
                echo "</form>";
                echo "</tr>";
              } else {
                // View mode
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . htmlspecialchars($row['NAMA_KERUSAKAN']) . "</td>";
                echo "<td>" . htmlspecialchars($row['SARAN_PERBAIKAN']) . "</td>";
                echo "<td>
                  <a href='edit_kerusakan.php?edit=" . $row['ID_KERUSAKAN'] . "' class='btn btn-warning btn-sm'>Edit</a>
                  <form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this data?\");'>
                    <input type='hidden' name='ID_KERUSAKAN' value='" . $row['ID_KERUSAKAN'] . "'>
                    <input type='hidden' name='action' value='delete'>
                    <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                  </form>
                </td>";
                echo "</tr>";
              }
            }
            ?>
          </tbody>
        </table>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="addKerusakanModal" tabindex="-1" aria-labelledby="addKerusakanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addKerusakanModalLabel">Tambah Kerusakan</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
              <div class="modal-body">
                <div class="mb-3">
                  <label for="nama_kerusakan" class="form-label">Nama Kerusakan</label>
                  <input type="text" class="form-control" id="NAMA_KERUSAKAN" name="NAMA_KERUSAKAN" required>
                </div>
                <div class="mb-3">
                  <label for="saran_perbaikan" class="form-label">Saran Perbaikan</label>
                  <input type="text" id="SARAN_PERBAIKAN" name="SARAN_PERBAIKAN" class="form-control" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Tambah Kerusakan</button>
              </div>
            </form>
          </div>
        </div>
      </div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
