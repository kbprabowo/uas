<!DOCTYPE html>
<html>
<head>
    <title>Sistem Informasi Parkir</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Sistem Informasi Parkir</h1>

        <?php
            // Menghubungkan dengan skrip PHP
            include 'parkirSystem.php';

            // Inisialisasi objek ParkirSystem
            $parkirSystem = new ParkirSystem();

            // Memeriksa apakah form dikirimkan
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Memeriksa apakah form login dikirimkan
                if (isset($_POST['login'])) {
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $parkirSystem->loginAdmin($username, $password);
                }

                // Memeriksa apakah form parkir masuk dikirimkan
                if (isset($_POST['parkir_masuk'])) {
                    $platNomor = $_POST['plat_nomor'];
                    $jenisKendaraan = $_POST['jenis_kendaraan'];
                    $parkirSystem->parkirMasuk($platNomor, $jenisKendaraan);
                }

                // Memeriksa apakah form parkir keluar dikirimkan
                if (isset($_POST['parkir_keluar'])) {
                    $platNomor = $_POST['plat_nomor'];
                    $parkirSystem->parkirKeluar($platNomor);
                }
            }

            // Menampilkan status parkir
            $parkirSystem->getStatusParkir();
        ?>

        <h2>Login Admin</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <?php if ($parkirSystem->isAdminLoggedIn()) { ?>
            <h2>Parkir Masuk</h2>
            <form method="post">
                <input type="text" name="plat_nomor" placeholder="Plat Nomor" required>
                <select name="jenis_kendaraan" required>
                    <option value="">Pilih Jenis Kendaraan</option>
                    <?php foreach ($parkirSystem->getJenisKendaraan() as $jenis) { ?>
                        <option value="<?php echo $jenis['id']; ?>"><?php echo $jenis['nama']; ?></option>
                    <?php } ?>
                </select>
                <button type="submit" name="parkir_masuk">Parkir Masuk</button>
            </form>
            
            <h2>Parkir Keluar</h2>
            <form method="post">
                <input type="text" name="plat_nomor" placeholder="Plat Nomor" required>
                <button type="submit" name="parkir_keluar">Parkir Keluar</button>
            </form>
        <?php } else {
            echo "<p>Anda harus login untuk mengakses fitur parkir.</p>";
        } ?>
    </div>
</body>
</html>