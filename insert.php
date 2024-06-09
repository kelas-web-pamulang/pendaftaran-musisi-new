<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pendaftaran Musisi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php
        date_default_timezone_set('Asia/Jakarta');
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);

        require_once 'config_db.php';

        $db = new ConfigDB();
        $conn = $db->connect();
    ?>
    <div class="container">
        <h1 class="text-center mt-5">Pendaftaran Musisi</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="namaMusisi">Nama Musisi</label>
                <input type="text" class="form-control" id="namaMusisi" name="nama_musisi" placeholder="Masukkan Nama" required>
            </div>
            <div class="form-group">
                <label for="nimMusisi">Nomor Identitas Musisi</label>
                <input type="text" class="form-control" id="nimMusisi" name="nim_musisi" placeholder="Masukkan Nomor Identitas" required>
            </div>
            <div class="form-group">
                <label for="emailMusisi">Email</label>
                <input type="email" class="form-control" id="emailMusisi" name="email_musisi" placeholder="Masukkan Email" required>
            </div>
            <div class="form-group">
                <label for="alamatMusisi">Alamat</label>
                <textarea class="form-control" id="alamatMusisi" name="alamat_musisi" placeholder="Masukkan Alamat" required></textarea>
            </div>
            <div class="form-group">
                <label for="noHpMusisi">No. HP</label>
                <input type="text" class="form-control" id="noHpMusisi" name="hp_musisi" placeholder="Masukkan No. HP" required>
            </div>
            <div class="form-group">
                <label for="GenreMusik">Genre Musik</label>
                <?php
                    $programStudi = $conn->query("SELECT id_genre, nama_genre FROM table_genre_musik");
                    echo "<select class='form-control' id='IdProgramStudi' name='id_genre'>";
                    echo "<option value=''>Pilih Genre Musik</option>";
                    while ($row = $programStudi->fetch_assoc()) {
                        echo "<option value='{$row['id_genre']}'>{$row['nama_genre']}</option>";
                    }
                    echo "</select>";
                ?>
            </div>
            <div class="form-group">
                <label for="pengalaman">Pengalaman Bermusisi</label>
                <input type="number" step="1" class="form-control" id="pengalaman" name="pengalaman" placeholder="Masukkan Pengalaman Bermusisi Anda" required>
            </div>
            <div class="form-group">
                <label for="usia">Masukan Usia</label>
                <input type="number" step="1" class="form-control" id="usia" name="usia" placeholder="Masukkan Usia Anda" required>
            </div>
            <div class="form-group">
                <label for="pilihanInstrumen">Pilihan Instrumen</label>
                <?php
                    $pilihanBeasiswa = $conn->query("SELECT id_pilihan_instrumen, nama_instrumen FROM pilihan_instrumen");
                    echo "<select class='form-control' id='pilihanInstrumen' name='id_pilihan_instrumen'>";
                    echo "<option value=''>Pilih Instrumen</option>";
                    while ($row = $pilihanBeasiswa->fetch_assoc()) {
                        echo "<option value='{$row['id_pilihan_instrumen']}'>{$row['nama_instrumen']}</option>";
                    }
                    echo "</select>";
                ?>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-success">Kembali</a>
        </form>

        <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nama = $_POST['nama_musisi'];
                $nim = $_POST['nim_musisi'];
                $email = $_POST['email_musisi'];
                $alamat = $_POST['alamat_musisi'];
                $no_hp = $_POST['hp_musisi'];
                $id_program_studi = $_POST['id_genre'];
                $semester = $_POST['pengalaman'];
                $ipk = $_POST['usia'];
                $id_pilihan_beasiswa = $_POST['id_pilihan_instrumen'];
                $tanggal_tambah_data = date('Y-m-d H:i:s');

                $query = "INSERT INTO pendaftar (nama_musisi, nim_musisi, email_musisi, alamat_musisi, hp_musisi, id_genre, pengalaman, usia, id_pilihan_instrumen, tanggal_tambah_data) 
                         VALUES ('$nama', '$nim', '$email', '$alamat', '$no_hp', '$id_program_studi', '$semester', '$ipk', '$id_pilihan_beasiswa', '$tanggal_tambah_data')";

                if ($conn->query($query) === TRUE) {
                    echo "<div class='alert alert-success mt-3' role='alert'>Pendaftaran berhasil</div>";
                } else {
                    echo "<div class='alert alert-danger mt-3' role='alert'>Error: " . $query . "<br>" . $conn->error . "</div>";
                }
            }
            $conn->close();
        ?>
    </div>
</body>
</html>