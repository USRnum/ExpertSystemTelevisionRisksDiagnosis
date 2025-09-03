<?php
include('crud_tvRev.php');
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
                            for ($x = 0; $x < count($listIdKemungkinan[$namaKerusakan]); $x++) {
                                $daftarKemungkinanKerusakan = $crud->getListKerusakan($listIdKemungkinan[$namaKerusakan][$x]);
                                $mb = $daftarKemungkinanKerusakan[0]['MB'];
                                $md = $daftarKemungkinanKerusakan[0]['MD'];
                                $cf = $mb - $md;
                                

                                if ($x == 0) {
                                    $cf_baru = $cf_accumulative + ($cf * (1 - $cf_accumulative));
                                    $cf_accumulative = $cf_baru;
                                    $daftar_cf[$namaKerusakan][] = $cf_baru;
                                }
                            }
                        }
                    }
                }
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Kerusakan</th>
                                <th>Nilai CF</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $crud->hasilCFTertinggi($daftar_cf, $groupKemungkinanKerusakan); ?>
                        </tbody>
                    </table>
                </div>

                <h2 class="mt-4">Kerusakan Anda adalah:</h2>
                <form name="form_diagnosis" action="saran.php" method="POST">
                    <ul class="list-group mb-4" style="margin-left: 20px;">
                        <?php $crud->hasilAkhir($daftar_cf, $groupKemungkinanKerusakan); ?>
                    </ul>
                </form>
                <a href="konsul.php" class="btn btn-secondary w-100">Kembali ke Konsultasi</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
