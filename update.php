<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Data Musisi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php
        date_default_timezone_set('Asia/Jakarta');
        require_once 'config_db.php';

        $db = new ConfigDB();
        $conn = $db->connect();

        $id_pendaftar = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_mahasiswa = $_POST['nama_musisi'];
            $nim_mahasiswa = $_POST['nim_musisi'];
            $email_mahasiswa = $_POST['email_musisi'];
            $alamat_mahasiswa = $_POST['alamat_musisi'];
            $no_hp_mahasiswa = $_POST['hp_musisi'];
            $id_program_studi = $_POST['id_genre'];
            $semester_mahasiswa = $_POST['pengalaman'];
            $ipk_terakhir_mahasiswa = $_POST['usia'];
            $id_pilihan_beasiswa = $_POST['id_pilihan_instrumen'];

            $data = [
                'nama_musisi' => $nama_mahasiswa,
                'nim_musisi' => $nim_mahasiswa,
                'email_musisi' => $email_mahasiswa,
                'alamat_musisi' => $alamat_mahasiswa,
                'hp_musisi' => $no_hp_mahasiswa,
                'id_genre' => $id_program_studi,
                'pengalaman' => $semester_mahasiswa,
                'usia' => $ipk_terakhir_mahasiswa,
                'id_pilihan_instrumen' => $id_pilihan_beasiswa
            ];

            // Mulai transaksi
            $conn->begin_transaction();

            $query = $db->update('pendaftar', $data, $id_pendaftar);

            if ($query) {
                // Commit transaksi jika berhasil
                $conn->commit();
                echo "<div class='alert alert-success mt-3' role='alert'>Data berhasil diperbahui</div>";
            } else {
                // Rollback transaksi jika gagal
                $conn->rollback();
                echo "<div class='alert alert-danger mt-3' role='alert'>Error: " . $conn->error . "</div>";
            }

            $result = $db->select("pendaftar", ['AND id_pendaftar=' => $id_pendaftar]);
        } else {
            $result = $db->select("pendaftar", ['AND id_pendaftar=' => $id_pendaftar]);
        }

        $pendaftar = $result[0];
    ?>
    <div class="container">
        <h1 class="text-center mt-5">Update Data Musisi</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="namaMusisi">Nama Musisi</label>
                <input type="text" class="form-control" id="namaMusisi" name="nama_musisi" placeholder="Masukkan Nama" required value="<?php echo $pendaftar['nama_musisi'] ?>">
            </div>
            <div class="form-group">
                <label for="nimMusisi">Nomor Identitas Musisi</label>
                <input type="text" class="form-control" id="nimMahasiswa" name="nim_musisi" placeholder="Masukkan Nomor Identitas" required value="<?php echo $pendaftar['nim_musisi'] ?>">
            </div>
            <div class="form-group">
                <label for="emailMusisi">Email</label>
                <input type="email" class="form-control" id="emailMusisi" name="email_musisi" placeholder="Masukkan Email" required value="<?php echo $pendaftar['email_musisi'] ?>">
            </div>
            <div class="form-group">
                <label for="alamatMMusisi">Alamat</label>
                <textarea class="form-control" id="alamatMusisi" name="alamat_musisi" placeholder="Masukkan Alamat" required><?php echo $pendaftar['alamat_musisi'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="noHpMahasiswa">No. HP</label>
                <input type="text" class="form-control" id="noHpMusisi" name="hp_musisi" placeholder="Masukkan No. HP" required value="<?php echo $pendaftar['hp_musisi'] ?>">
            </div>
            <div class="form-group">
                <label for="programStudi">Program Studi</label>
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
                <label for="pengalamanMusisi">Pengalaman Bermusik</label>
                <input type="number" step="1" class="form-control" id="pengalaman" name="pengalaman" placeholder="Masukkan Pengalaman Bermusisi Anda" required value="><?php echo $pendaftar['pengalaman'] ?>">
            </div>
            <div class="form-group">
                <label for="UsiaMusisi">Usia</label>
                <input type="text" class="form-control" id="UsiaMusisi" name="usia" placeholder="Masukkan Usia Anda" required value="<?php echo $pendaftar['usia'] ?>">
            </div>
            <div class="form-group">
                <label for="pilihanInstrumen">Pilihan Instrumen</label>
                <?php
                    $pilihanBeasiswa = $conn->query("SELECT id_pilihan_instrumen, nama_instrumen FROM pilihan_instrumen");
                    echo "<select class='form-control' id='pilihanBeasiswa' name='id_pilihan_instrumen' required>";
                    echo "<option value=''>Pilih instrumen</option>";
                    while ($row = $pilihanBeasiswa->fetch_assoc()) {
                        $selected = ($pendaftar['id_pilihan_instrumen'] == $row['id_pilihan_instrumen']) ? 'selected' : '';
                        echo "<option value='{$row['id_pilihan_instrumen']}' $selected>{$row['nama_instrumen']}</option>";
                    }
                    echo "</select>";
                ?>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-info">Kembali</a>
        </form>

        <?php
            $conn->close();
        ?>
    </div>
</body>
</html>