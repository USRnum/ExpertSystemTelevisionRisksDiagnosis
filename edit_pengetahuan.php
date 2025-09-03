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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
      <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4">Data Pengetahuan Kerusakan</h2>
        <!-- Button to trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDataModal">
        Tambah Data
        </button>
      </div>
      <table class="table table-bordered table-striped table-hover mt-3">
        <thead>
        <tr>
          <th>ID Pengetahuan</th>
          <th>ID Gejala</th>
          <th>ID Kerusakan</th>
          <th>MB</th>
          <th>MD</th>
          <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // PAGINATION SETUP
        $limit = 10; // jumlah data per halaman
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        // Hitung total data
        $countQuery = "SELECT COUNT(*) as total FROM pengetahuan_kerusakan";
        $countResult = mysqli_query($conn, $countQuery);
        $totalData = 0;
        if ($countResult) {
            $row = mysqli_fetch_assoc($countResult);
            $totalData = $row['total'];
        }
        $totalPages = ceil($totalData / $limit);

        // Query data dengan LIMIT dan OFFSET
        $query = "SELECT ID_PENGETAHUAN, ID_GEJALA, ID_KERUSAKAN, MB, MD FROM pengetahuan_kerusakan LIMIT $limit OFFSET $offset";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row['ID_PENGETAHUAN']) . "</td>";
          echo "<td>" . htmlspecialchars($row['ID_GEJALA']) . "</td>";
          echo "<td>" . htmlspecialchars($row['ID_KERUSAKAN']) . "</td>";
          echo "<td>" . htmlspecialchars($row['MB']) . "</td>";
          echo "<td>" . htmlspecialchars($row['MD']) . "</td>";
          echo "<td>";
          echo "<button type='button' class='btn btn-warning btn-sm me-2' data-bs-toggle='modal' data-bs-target='#editDataModal' data-id='" . htmlspecialchars($row['ID_PENGETAHUAN']) . "' data-gejala='" . htmlspecialchars($row['ID_GEJALA']) . "' data-kerusakan='" . htmlspecialchars($row['ID_KERUSAKAN']) . "' data-mb='" . htmlspecialchars($row['MB']) . "' data-md='" . htmlspecialchars($row['MD']) . "'>Edit</button>";
          echo "<form action='' method='POST' style='display:inline-block;'>";
          echo "<input type='hidden' name='delete_id' value='" . htmlspecialchars($row['ID_PENGETAHUAN']) . "'>";
          echo "<button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this item?\");'>Delete</button>";
          echo "</form>";
          echo "</td>";
          echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='6' class='text-center'>No data available</td></tr>";
        }

        // Handle delete request
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
          $delete_id = mysqli_real_escape_string($conn, $_POST['delete_id']);
          $deleteQuery = "DELETE FROM pengetahuan_kerusakan WHERE ID_PENGETAHUAN = '$delete_id'";
          if (mysqli_query($conn, $deleteQuery)) {
          echo "<script>alert('Data deleted successfully'); window.location.href='edit_pengetahuan.php';</script>";
          } else {
          echo "<script>alert('Failed to delete data');</script>";
          }
        }
        ?>
        </tbody>
      </table>
      <!-- Pagination -->
      <nav>
        <ul class="pagination justify-content-center">
          <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
            <a class="page-link" href="?page=<?php echo $page - 1; ?>" tabindex="-1">Sebelumnya</a>
          </li>
          <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php if($page == $i){ echo 'active'; } ?>">
              <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?php if($page >= $totalPages){ echo 'disabled'; } ?>">
            <a class="page-link" href="?page=<?php echo $page + 1; ?>">Selanjutnya</a>
          </li>
        </ul>
      </nav>
      </section>
      <!-- Add Data Modal -->
      <div class="modal fade" id="addDataModal" tabindex="-1" aria-labelledby="addDataModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addDataModalLabel">Tambah Data Pengetahuan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pengetahuan'])) {
          $id_pengetahuan = mysqli_real_escape_string($conn, $_POST['id_pengetahuan']);
          $id_gejala = mysqli_real_escape_string($conn, $_POST['id_gejala']);
          $id_kerusakan = mysqli_real_escape_string($conn, $_POST['id_kerusakan']);
          $mb = mysqli_real_escape_string($conn, $_POST['mb']);
          $md = mysqli_real_escape_string($conn, $_POST['md']);

          $checkQuery = "SELECT ID_PENGETAHUAN FROM pengetahuan_kerusakan WHERE ID_PENGETAHUAN = '$id_pengetahuan'";
          $checkResult = mysqli_query($conn, $checkQuery);

          if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            echo "<div class='alert alert-danger'>ID Pengetahuan sudah ada. Gunakan ID yang berbeda.</div>";
          } else {
            $insertQuery = "INSERT INTO pengetahuan_kerusakan (ID_PENGETAHUAN, ID_GEJALA, ID_KERUSAKAN, MB, MD) VALUES ('$id_pengetahuan', '$id_gejala', '$id_kerusakan', '$mb', '$md')";

            if (mysqli_query($conn, $insertQuery)) {
            echo "<script>alert('Data added successfully'); window.location.href='edit_pengetahuan.php';</script>";
            } else {
            echo "<script>alert('Failed to add data');</script>";
            }
          }
          }
          ?>
          <form action="" method="POST">
          <div class="mb-3">
            <label for="id_pengetahuan" class="form-label">ID Pengetahuan</label>
            <input type="text" name="id_pengetahuan" id="id_pengetahuan" class="form-control">
          </div>
          <div class="mb-3">
            <label for="id_gejala" class="form-label">ID Gejala</label>
            <select name="id_gejala" id="id_gejala" class="form-control" required>
            <option value="" disabled selected>Pilih ID Gejala</option>
            <?php
            $gejalaQuery = "SELECT ID_GEJALA, NAMA_GEJALA FROM gejala_kerusakan";
            $gejalaResult = mysqli_query($conn, $gejalaQuery);

            while ($gejalaRow = mysqli_fetch_assoc($gejalaResult)) {
              echo "<option value='" . htmlspecialchars($gejalaRow['ID_GEJALA']) . "'>" . htmlspecialchars($gejalaRow['NAMA_GEJALA']) . "</option>";
            }
            ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="id_kerusakan" class="form-label">ID Kerusakan</label>
            <select name="id_kerusakan" id="id_kerusakan" class="form-control" required>
            <option value="" disabled selected>Pilih ID Kerusakan</option>
            <?php
            $kerusakanQuery = "SELECT ID_KERUSAKAN, NAMA_KERUSAKAN FROM kerusakan";
            $kerusakanResult = mysqli_query($conn, $kerusakanQuery);

            while ($kerusakanRow = mysqli_fetch_assoc($kerusakanResult)) {
              echo "<option value='" . htmlspecialchars($kerusakanRow['ID_KERUSAKAN']) . "'>" . htmlspecialchars($kerusakanRow['NAMA_KERUSAKAN']) . "</option>";
            }
            ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="mb" class="form-label">MB</label>
            <input type="number" step="0.01" class="form-control" id="mb" name="mb" required>
          </div>
          <div class="mb-3">
            <label for="md" class="form-label">MD</label>
            <input type="number" step="0.01" class="form-control" id="md" name="md" required>
          </div>
          <button type="submit" class="btn btn-primary">Tambah Data</button>
          </form>
        </div>
        </div>
      </div>
      </div>
      <!-- Edit Data Modal -->
      <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editDataModalLabel">Edit Data Pengetahuan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="POST">
          <input type="hidden" name="id_pengetahuan" id="edit_id_pengetahuan">
          <div class="mb-3">
        <label for="edit_id_gejala" class="form-label">ID Gejala</label>
        <select name="id_gejala" id="edit_id_gejala" class="form-control" required>
        <option value="" disabled selected>Pilih ID Gejala</option>
        <?php
        $gejalaQuery = "SELECT ID_GEJALA, NAMA_GEJALA FROM gejala_kerusakan";
        $gejalaResult = mysqli_query($conn, $gejalaQuery);

        while ($gejalaRow = mysqli_fetch_assoc($gejalaResult)) {
          echo "<option value='" . htmlspecialchars($gejalaRow['ID_GEJALA']) . "'>" . htmlspecialchars($gejalaRow['NAMA_GEJALA']) . "</option>";
        }
        ?>
        </select>
          </div>
          <div class="mb-3">
        <label for="edit_id_kerusakan" class="form-label">ID Kerusakan</label>
        <select name="id_kerusakan" id="edit_id_kerusakan" class="form-control" required>
        <option value="" disabled selected>Pilih ID Kerusakan</option>
        <?php
        $kerusakanQuery = "SELECT ID_KERUSAKAN, NAMA_KERUSAKAN FROM kerusakan";
        $kerusakanResult = mysqli_query($conn, $kerusakanQuery);

        while ($kerusakanRow = mysqli_fetch_assoc($kerusakanResult)) {
          echo "<option value='" . htmlspecialchars($kerusakanRow['ID_KERUSAKAN']) . "'>" . htmlspecialchars($kerusakanRow['NAMA_KERUSAKAN']) . "</option>";
        }
        ?>
        </select>
          </div>
          <div class="mb-3">
        <label for="edit_mb" class="form-label">MB</label>
        <input type="number" step="0.01" class="form-control" id="edit_mb" name="mb" required>
          </div>
          <div class="mb-3">
        <label for="edit_md" class="form-label">MD</label>
        <input type="number" step="0.01" class="form-control" id="edit_md" name="md" required>
          </div>
          <button type="submit" name="update_data" class="btn btn-primary">Simpan Perubahan</button>
          </form>
        </div>
        </div>
      </div>
      </div>

      <?php
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_data'])) {
          $id_pengetahuan = mysqli_real_escape_string($conn, $_POST['id_pengetahuan']);
          $id_gejala = mysqli_real_escape_string($conn, $_POST['id_gejala']);
          $id_kerusakan = mysqli_real_escape_string($conn, $_POST['id_kerusakan']);
          $mb = mysqli_real_escape_string($conn, $_POST['mb']);
          $md = mysqli_real_escape_string($conn, $_POST['md']);

          $updateQuery = "UPDATE pengetahuan_kerusakan SET ID_GEJALA = '$id_gejala', ID_KERUSAKAN = '$id_kerusakan', MB = '$mb', MD = '$md' WHERE ID_PENGETAHUAN = '$id_pengetahuan'";

          if (mysqli_query($conn, $updateQuery)) {
          echo "<script>alert('Data updated successfully'); window.location.href='edit_pengetahuan.php';</script>";
          } else {
          echo "<script>alert('Failed to update data');</script>";
          }
      }
      ?>
      </div>
      </div>
    </main>

    <script>
      // Populate edit modal with data
      const editDataModal = document.getElementById('editDataModal');
      editDataModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const id = button.getAttribute('data-id');
      const gejala = button.getAttribute('data-gejala');
      const kerusakan = button.getAttribute('data-kerusakan');
      const mb = button.getAttribute('data-mb');
      const md = button.getAttribute('data-md');

      document.getElementById('edit_id_pengetahuan').value = id;
      document.getElementById('edit_id_gejala').value = gejala;
      document.getElementById('edit_id_kerusakan').value = kerusakan;
      document.getElementById('edit_mb').value = mb;
      document.getElementById('edit_md').value = md;
      });
    </script>
  </div>
  <!-- Bootstrap JS -->
  <script src="js/bootstrap.bundle.min.js"></script>
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
