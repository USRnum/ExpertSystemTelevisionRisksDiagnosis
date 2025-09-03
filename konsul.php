<?php
include_once 'crud_tvRev.php';
$crud = new Crud;
$arrayName = $crud->readGejala(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Konsultasi Kerusakan Televisi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-weight: 600;
        }

        .header h3 {
            margin-top: 10px;
            font-weight: 400;
        }

        .table-container {
            margin-top: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-success {
            font-size: 18px;
            padding: 10px;
        }

        .back-link {
            display: block;
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Konsultasi Kerusakan Televisi</h1>
        <h3>Silahkan pilih gejala di bawah ini:</h3>
    </div>
    <div class="container mt-4">
        <div class="table-container">
            <form method="POST" action="Archive\hasil_konsulRev2.php">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col" style="width: 10%;">Pilih Gejala</th>
                            <th scope="col">Nama Gejala</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($arrayName as $r) { ?>
                            <tr>
                                <td>
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input" id="gejala<?php echo $r['ID_GEJALA']; ?>" name="gejala[]" type="checkbox" value="<?php echo $r['ID_GEJALA']; ?>" style="width: 1.5em; height:1.5em;">
                                    </div>
                                </td>
                                <td class="text-start ps-3"><?php echo $r["NAMA_GEJALA"]; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="d-grid gap-2 mt-3">
                    <button class="btn btn-danger btn-lg" type="reset">Reset Pilihan Gejala</button>
                </div>
                <div class="d-grid gap-2 mt-3">
                    <button class="btn btn-success btn-lg" type="submit" name="submit">Selesai</button>
                </div>
                                
                <a class="back-link text-decoration-none text-primary mt-2" href="index.php">Kembali ke Halaman Utama</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const maxChecked = 10;
            const checkboxes = document.querySelectorAll('input[name="gejala[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const checkedCount = document.querySelectorAll('input[name="gejala[]"]:checked').length;
                    if (checkedCount > maxChecked) {
                        this.checked = false;
                        alert('Loh Awas ‼️ Maksimal hanya boleh memilih 10 gejala!');
                    }
                });
            });
        });
        document.querySelector('form').addEventListener('submit', function(e) {
            const checkedCount = document.querySelectorAll('input[name="gejala[]"]:checked').length;
            if (checkedCount === 0) {
                alert('Silakan pilih minimal 1 gejala sebelum melanjutkan!');
                e.preventDefault();
            }
        });
    </script>
</body>

</html>
