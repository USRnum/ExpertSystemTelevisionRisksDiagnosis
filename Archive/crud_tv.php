<?php

include 'C:\xampp\htdocs\ExpertSystemTelevisionRisksDiagnosis\koneksi.php';

/**
 * Crud class
 * turunan dario class koneksi
 */
class Crud extends koneksi
{
    // untuk mengambil function dari parent(koneksi)
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * function readGejala
     * mengambil tabel dari gejala
     * return array isi tabel
     */
    public function readGejala()
    {
        $sql = "SELECT * FROM gejala_kerusakan";
        $result = $this->conn->query($sql);

        // merubah data tabel menjadi array
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * funtion getGejala
     * mengambil data sebagian dari tabel gejala
     */
    public function getGejala($value)
    {
        $sql = "SELECT * FROM gejala_kerusakan WHERE ID_GEJALA IN ($value)";
        $result = $this->conn->query($sql);

        // merubah data tabel menjadi array
        $row = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getKerusakan($value = null)
{
    if ($value === null || $value === '' || $value === []) {
        $sql = "SELECT * FROM kerusakan";
    } else {
        $sql = "SELECT * FROM kerusakan WHERE ID_KERUSAKAN IN ($value)";
    }
    $result = $this->conn->query($sql);

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

    /**
     * Gets the group pengetahuan.
     *
     * mengambil salah satu nama kerusakan bila terdapat nama kerusakan sama
     */
    public function getGroupPengetahuan($value)
    {
        // p, g , krsk merupakan inisialisasi dari tabel yang dituju
        $sql = "SELECT krsk.NAMA_KERUSAKAN FROM pengetahuan_kerusakan p
        JOIN gejala_kerusakan g ON p.ID_GEJALA = g.ID_GEJALA
        JOIN kerusakan krsk ON p.ID_KERUSAKAN = krsk.ID_KERUSAKAN
        WHERE p.ID_GEJALA IN ($value)
        GROUP BY p.ID_KERUSAKAN  ORDER BY p.ID_KERUSAKAN ASC";

        $result = $this->conn->query($sql);

        if (isset($result)) {
            // merubah data tabel menjadi array
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }
    }

    /**
     * Gets the kemungkinan kerusakan.
     *
     * mengambil data pengetahuan bila terdapat gejala
     */
    public function getKemungkinanKerusakan($sql)
    {
        // p, g , krsk merupakan inisialisasi dari tabel yang dituju
        $sql = "SELECT krsk.NAMA_KERUSAKAN, p.ID_PENGETAHUAN FROM pengetahuan_kerusakan p
        JOIN GEJALA_KERUSAKAN g ON p.ID_GEJALA = g.ID_GEJALA
        JOIN kerusakan krsk ON p.ID_KERUSAKAN = krsk.ID_KERUSAKAN
        WHERE g.ID_GEJALA IN ($sql)";


        $result = $this->conn->query($sql);

        $rows=[];
        if ($result){
            while($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
        /*if (isset($result)) {
            // merubah data tabel menjadi array
            $row = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }*/
    }

    public function getListKerusakan($value)
    {
        // p, g , krsk merupakan inisialisasi dari tabel yang dituju
        // Ensure $value is properly formatted as a comma-separated string of quoted values
        $formattedValue = implode(',', array_map(function ($v) {
            return "'" . $this->conn->real_escape_string($v) . "'";
        }, explode(',', $value)));
        
        $sql = "SELECT * FROM pengetahuan_kerusakan p
        JOIN gejala_kerusakan g ON p.ID_GEJALA = g.ID_GEJALA
        JOIN kerusakan krsk ON p.ID_KERUSAKAN = krsk.ID_KERUSAKAN
        WHERE p.ID_PENGETAHUAN IN ($formattedValue)";

        $result = $this->conn->query($sql);

        if (isset($result)) {
            // merubah data tabel menjadi array
            $row = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }
    }

    public function hasilCFTertinggi($daftar_cf, $groupKemungkinanKerusakan)
    {
        echo "<br/>";
        for ($i = 0; $i < count($groupKemungkinanKerusakan); $i++) {
            $namaKerusakan = $groupKemungkinanKerusakan[$i]['NAMA_KERUSAKAN'];
            echo "<td>" . $namaKerusakan . "</td>";
            // Langsung ambil nilai float dan ubah ke persen bulat tanpa koma
            $persenCF = intval($daftar_cf[$namaKerusakan] * 100);
            echo "<td>" . $persenCF . " %" . "</td>";
            echo "<tr>" . "</tr>";
        }
    }

    public function hasilAkhir($daftar_cf, $groupKemungkinanKerusakan)
    {
        $merubahIndexCF = [];

        // Hitung nilai CF per kerusakan
        foreach ($groupKemungkinanKerusakan as $i => $kerusakan) {
            $nama = $kerusakan['NAMA_KERUSAKAN'];
            $merubahIndexCF[$i] = $daftar_cf[$nama];
        }

        $rows = [];
        $found = false;

        foreach ($groupKemungkinanKerusakan as $i => $kerusakan) {
            $cfValue = $merubahIndexCF[$i];
            $persenCF = intval($cfValue * 100);

            // Tampilkan hanya jika persenCF >= 60
            if ($persenCF >= 60 && $persenCF <= 100) {
                $found = true;
                $nama = $kerusakan['NAMA_KERUSAKAN'];
                echo '<li>' . $nama . ' (' . $persenCF . ' %)</li>';
                echo '<input type="hidden" name="diagnosa_kerusakan[]" value="' . $nama . '">';
                echo '<input type="hidden" name="persentase[]" value="' . $persenCF . '">';

                $sql = "SELECT SARAN_PERBAIKAN FROM kerusakan WHERE NAMA_KERUSAKAN = '" . $this->conn->real_escape_string($nama) . "'";
                $result = $this->conn->query($sql);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $rows[] = $row;
                    }
                }
            }
        }

        // Jika tidak ada yang memenuhi, tampilkan pesan
        if (!$found) {
            echo '<li>Tidak ada kerusakan dengan tingkat kepercayaan minimal 60%.</li>';
        }

        foreach ($rows as $row) {
            if (isset($row["SARAN_PERBAIKAN"])) {
                echo '<p>Saran Perbaikan: ' . $row["SARAN_PERBAIKAN"] . '</p>';
                echo '<input type="hidden" name="saran[]" value="' . $row["SARAN_PERBAIKAN"] . '">';
            }
        }
    }
}
