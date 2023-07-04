<?php

class ParkirSystem {
    private $conn;
    private $adminLoggedIn;

    public function __construct() {
        // Inisialisasi koneksi database (sesuaikan dengan pengaturan database Anda)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "uas";

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Koneksi ke database gagal: " . $this->conn->connect_error);
        }

        // Memeriksa apakah admin sudah login
        session_start();
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            $this->adminLoggedIn = true;
        } else {
            $this->adminLoggedIn = false;
        }
    }

    public function loginAdmin($username, $password) {
        // Lakukan validasi login admin
        // (Anda dapat mengganti metode validasi sesuai kebutuhan, misalnya dengan mengambil data dari tabel admin)
        if ($username === "admin" && $password === "admin123") {
            $_SESSION['admin_logged_in'] = true;
            $this->adminLoggedIn = true;
        } else {
            echo "<p>Login gagal. Silakan coba lagi.</p>";
        }
    }

    public function isAdminLoggedIn() {
        return $this->adminLoggedIn;
    }

    public function parkirMasuk($platNomor, $jenisKendaraanId) {
        // Simpan data parkir masuk ke tabel parkir
        $sql = "INSERT INTO parkir (plat_nomor, waktu_masuk, jenis_kendaraan_id) VALUES ('$platNomor', NOW(), '$jenisKendaraanId')";

        if ($this->conn->query($sql) === true) {
            echo "<p>Kendaraan dengan plat nomor $platNomor telah masuk parkir.</p>";
        } else {
            echo "<p>Error: " . $sql . "<br>" . $this->conn->error . "</p>";
        }
    }

public function parkirKeluar($platNomor) {
    // Menghitung biaya parkir
    $biayaParkir = $this->hitungBiayaParkir($platNomor);

    // Menampilkan alert dengan biaya parkir
    echo "<script>alert('Biaya parkir untuk kendaraan dengan plat nomor $platNomor adalah Rp. $biayaParkir');</script>";

    // Hapus data parkir keluar dari tabel parkir
    $sql = "DELETE FROM parkir WHERE plat_nomor='$platNomor'";

    if ($this->conn->query($sql) === false) {
        echo "<p>Error: " . $sql . "<br>" . $this->conn->error . "</p>";
    }
}

    private function hitungBiayaParkir($platNomor) {
    // Ambil data jenis kendaraan dari tabel parkir
    $sql = "SELECT jk.id FROM jenis_kendaraan jk JOIN parkir p ON jk.id = p.jenis_kendaraan_id WHERE p.plat_nomor='$platNomor'";
    $result = $this->conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $jenisKendaraanId = $row['id'];

        // Ambil data harga parkir per jam berdasarkan jenis kendaraan
        $sqlJenisKendaraan = "SELECT harga_per_jam FROM jenis_kendaraan WHERE id='$jenisKendaraanId'";
        $resultJenisKendaraan = $this->conn->query($sqlJenisKendaraan);

        if ($resultJenisKendaraan->num_rows > 0) {
            $rowJenisKendaraan = $resultJenisKendaraan->fetch_assoc();
            $hargaPerJam = $rowJenisKendaraan['harga_per_jam'];

            // Hitung biaya parkir berdasarkan lama durasi parkir
            $sqlDurasi = "SELECT TIMESTAMPDIFF(HOUR, waktu_masuk, NOW()) AS lama_durasi FROM parkir WHERE plat_nomor='$platNomor'";
            $resultDurasi = $this->conn->query($sqlDurasi);

            if ($resultDurasi->num_rows > 0) {
                $rowDurasi = $resultDurasi->fetch_assoc();
                $lamaDurasi = $rowDurasi['lama_durasi'];

                // Hitung biaya parkir berdasarkan jenis kendaraan
                if ($lamaDurasi < 1) {
                    $biayaParkir = $hargaPerJam;
                } else {
                    $biayaParkir = $hargaPerJam * $lamaDurasi;
                }

                return $biayaParkir;
            }
        }
    }
}



    public function getStatusParkir() {
        // Dapatkan status parkir dari tabel parkir
        $sql = "SELECT * FROM parkir";
        $result = $this->conn->query($sql);

        $totalParkir = $result->num_rows;
        $terparkir = $totalParkir;

        echo "<h2>Status Parkir</h2>";
        echo "<p>Total Parkir: $totalParkir</p>";
        echo "<p>Terparkir: $terparkir</p>";
        echo "<p>Detail Kendaraan:</p>";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p>Plat Nomor: " . $row["plat_nomor"] . "</p>";
            }
        } else {
            echo "<p>Tidak ada kendaraan yang terparkir.</p>";
        }
    }

    public function getJenisKendaraan() {
        // Ambil daftar jenis kendaraan dari tabel jenis_kendaraan
        $sql = "SELECT * FROM jenis_kendaraan";
        $result = $this->conn->query($sql);

        $jenisKendaraan = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $jenisKendaraan[] = $row;
            }
        }

        return $jenisKendaraan;
    }
}

class Kendaraan {
    private $conn;

    public function __construct() {
        // Inisialisasi koneksi database (sesuaikan dengan pengaturan database Anda)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "uas";

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Koneksi ke database gagal: " . $this->conn->connect_error);
        }
    }

        }
return 0;