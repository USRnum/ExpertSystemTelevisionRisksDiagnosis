<?php
include('C:\xampp\htdocs\ExpertSystemTelevisionRisksDiagnosis\Archive\crud_tv.php');
$crud = new Crud();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hasil Konsultasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        .card-header {
            background: linear-gradient(90deg, #007bff, #0056b3);
        }

        .card-header h1 {
            font-weight: 600;
        }

        .table thead th {
            text-align: center;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #0056b3;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #003f7f;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #5a6268;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #4e555b;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-header text-white text-center">
                <h1 class="mt-2">Hasil Konsultasi</h1>
            </div>
            <div class="card-body">
                <?php
                if (isset($_POST['submit'])) {
                    if (!isset($_POST['gejala'])) {
                        header("Location: konsul.php");
                        die();
                    }

                    $groupKemungkinanKerusakan = $crud->getGroupPengetahuan(implode(",", $_POST['gejala']));
                    $sql = $_POST['gejala'];

                    if (isset($sql)) {
                        for ($h = 0; $h < count($sql); $h++) {
                            $kemungkinanKerusakan[] = $crud->getKemungkinanKerusakan($sql[$h]);
                            for ($x = 0; $x < count($kemungkinanKerusakan[$h]); $x++) {
                                for ($i = 0; $i < count($groupKemungkinanKerusakan); $i++) {
                                    $namaKerusakan = $groupKemungkinanKerusakan[$i]['NAMA_KERUSAKAN'];
                                    if ($kemungkinanKerusakan[$h][$x]['NAMA_KERUSAKAN'] == $namaKerusakan) {
                                        $listIdKemungkinan[$namaKerusakan][] = $kemungkinanKerusakan[$h][$x]['ID_PENGETAHUAN'];
                                    }
                                }
                            }
                        }

                        $id_kerusakan_terbesar = '';
                        $nama_kerusakan_terbesar = '';
                        $cf_accumulative = 0;


                        for ($h = 0; $h < count($groupKemungkinanKerusakan); $h++) {
                            $namaKerusakan = $groupKemungkinanKerusakan[$h]['NAMA_KERUSAKAN'];
                            $cf_accumulative = null; // Reset untuk setiap kerusakan
                            if (!empty($listIdKemungkinan[$namaKerusakan])) {
                                foreach ($listIdKemungkinan[$namaKerusakan] as $idPengetahuan) {
                                    $daftarKemungkinanKerusakan = $crud->getListKerusakan($idPengetahuan);
                                    $mb = $daftarKemungkinanKerusakan[0]['MB'];
                                    $md = $daftarKemungkinanKerusakan[0]['MD'];
                                    $cf = $mb - $md;
                                    if ($cf_accumulative === null) {
                                        $cf_accumulative = $cf;
                                        
                                    } else {
                                        $cf_accumulative = $cf_accumulative + ($cf * (1 - $cf_accumulative));
                                    }
                                }
                                $daftar_cf[$namaKerusakan] = $cf_accumulative;
                                // Tentukan kerusakan dengan CF terbesar
                                if (!isset($cf_terbesar) || $cf_accumulative > $cf_terbesar) {
                                    $cf_terbesar = $cf_accumulative;
                                    // Cek apakah key ID_KERUSAKAN ada
                                    $id_kerusakan_terbesar = isset($groupKemungkinanKerusakan[$h]['ID_KERUSAKAN']) ? $groupKemungkinanKerusakan[$h]['ID_KERUSAKAN'] : '';
                                    $nama_kerusakan_terbesar = $namaKerusakan;
                                }
                            }
                        }
                    }
                    // Ambil data gejala yang dipilih user
                    $gejala_terpilih = [];
                    if (!empty($_POST['gejala'])) {
                        $gejala_ids = implode(",", array_map('intval', $_POST['gejala']));
                        $gejala_terpilih = $crud->getGejala($gejala_ids);
                    }
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center align-middle">Nama Kerusakan</th>
                                <th class="text-center align-middle">Nilai CF</th>
                                <th class="text-center align-middle">Gejala yang Diinputkan</th>
                                <th class="text-center align-middle">Saran Perbaikan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ada_hasil = false;
                            foreach ($daftar_cf as $namaKerusakan => $cf) {
                                $persen = intval($cf * 100);
                                if ($persen >= 40 && $persen <= 100) {
                                    $ada_hasil = true;
                                    echo "<tr>";
                                    echo "<td>$namaKerusakan</td>";
                                    echo "<td>$persen %</td>";
                                    echo "<td>";
                                    // Ambil gejala yang berkontribusi pada kerusakan ini
                                    if (!empty($listIdKemungkinan[$namaKerusakan])) {
                                        echo "<ul>";
                                        foreach ($listIdKemungkinan[$namaKerusakan] as $idPengetahuan) {
                                            $dataPengetahuan = $crud->getListKerusakan($idPengetahuan);
                                            if (!empty($dataPengetahuan[0]['NAMA_GEJALA'])) {
                                                echo "<li>" . htmlspecialchars($dataPengetahuan[0]['NAMA_GEJALA']) . "</li>";
                                            }
                                        }
                                        echo "</ul>";
                                    } else {
                                        echo "-";
                                    }
                                    // Kolom Saran Perbaikan
                                    $saran = "-";
                                    // Ambil saran perbaikan berdasarkan nama kerusakan
                                    $sqlSaran = "SELECT SARAN_PERBAIKAN FROM kerusakan WHERE NAMA_KERUSAKAN = '" . $crud->conn->real_escape_string($namaKerusakan) . "'";
                                    $resultSaran = $crud->conn->query($sqlSaran);
                                    if ($resultSaran && $rowSaran = $resultSaran->fetch_assoc()) {
                                        if (!empty($rowSaran['SARAN_PERBAIKAN'])) {
                                            $saran = htmlspecialchars($rowSaran['SARAN_PERBAIKAN']);
                                        }
                                    }
                                    echo "<td>$saran</td>";
                                    echo "</tr>";
                                }
                            }
                            if (!$ada_hasil) {
                                echo '<tr><td colspan="4" class="text-center text-danger">Tidak ada kerusakan dengan tingkat kepercayaan minimal 60%.</td></tr>';
                            }
                        }
                            ?>
                        </tbody>
                    </table>
                </div>
                <a href="\ExpertSystemTelevisionRisksDiagnosis\konsul.php" class="btn btn-secondary w-100">Kembali ke Konsultasi</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js\bootstrap.bundle.min.js"></script>
</body>
</html>