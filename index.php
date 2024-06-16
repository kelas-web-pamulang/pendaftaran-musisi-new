<?php
session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    setcookie('clientId', '', time() - 3600, '/');
    setcookie('clientSecret', '', time() - 3600, '/');
    header('Location: login.php');
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    require_once 'config_db.php';
    $db = new ConfigDB();
    $conn = $db->connect();

    $delete_id = $_GET['delete'];
    $query = "UPDATE pendaftar SET tanggal_hapus_data = ? WHERE id_pendaftar = ?";
    $stmt = $conn->prepare($query);
    $current_datetime = date('Y-m-d H:i:s');
    $stmt->bind_param('si', $current_datetime, $delete_id);
    $stmt->execute();
    $stmt->close();
    $db->close();

    header('Location: index.php');
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pendaftaran Musisi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            flex: 1;
        }
        footer {
            background-color: #FFBF7A;
            padding: 20px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-5">Musisi Terdaftar</h1>
        <div class="row">
            <div class="d-flex justify-content-between">
                <form action="" method="get" class="d-flex align-items-center">
                    <input class="form-control" placeholder="Cari Data" name="search"/>
                    <select name="search_by" class="form-select">
                        <option value="">Pilih Berdasarkan</option>
                        <option value="nama_musisi">Nama</option>
                        <option value="nim_musisi">Nomor Identitas Musisi</option>
                    </select>
                    <button type="submit" class="btn btn-success mx-2">Cari</button>
                </form>
                <a href="insert.php" class="ml-auto mb-2"><button class="btn btn-success">Tambah Data</button></a>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Nomor Identitas Musisi</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th>No. HP</th>
                    <th>Genre Musik</th>
                    <th>Pengalaman</th>
                    <th>Usia</th>
                    <th>Instrumen</th>
                    <th>Tgl. Buat</th>
                    <th colspan="2">Pilihan</th>
                </tr>
                </thead>
                <tbody>
                <?php
                date_default_timezone_set('Asia/Jakarta');
                ini_set('display_errors', '1');
                ini_set('display_startup_errors', '1');
                error_reporting(E_ALL);

                require_once 'config_db.php';
                require 'vendor/autoload.php';


                \Sentry\init([
                'dsn' => 'https://3650a2f3829d3d9c1b09e6f219ec4bc4@o4507438558674944.ingest.us.sentry.io/4507438560575488',
                // Specify a fixed sample rate
                'traces_sample_rate' => 1.0,
                // Set a sampling rate for profiling - this is relative to traces_sample_rate
                'profiles_sample_rate' => 1.0,
                ]);

                $db = new ConfigDB();
                $conn = $db->connect();

                // function checkNum($number) {
                //     if($number>1) {
                //       throw new Exception("Value must be 1 or below");
                //     }
                //     return true;
                // }
        
                // function logError($error) {
                //     error_log($error, 3, 'error.log');
                //  }
                
                // try {
                //     echo checkNum(2);	
                // } catch (Exception $e) {
                //     logError($e->getMessage());
                //     echo 'Error : '.$e->getMessage();
                // }

                //   echo 'Finish';

                

                $conditional = [];
                if (isset($_GET['search'])) {
                    $search = $_GET['search'];
                    $search_by = $_GET['search_by'];
                    if ($search_by == 'nama_musisi') {
                        $conditional['AND nama_musisi LIKE'] = "%$search%";
                    } else if ($search_by == 'nim_musisi') {
                        $conditional['AND nim_musisi LIKE'] = "%$search%";
                    }
                } else if (isset($_GET['delete'])) {
                    $query = $db->update('pendaftar', [
                        'tanggal_hapus_data' => date('Y-m-d H:i:s')
                    ], $_GET['delete']);
                }

                // Pagination logic
                $limit = 5; // Jumlah data yang muncul perhalaman
                if (isset($_GET["page"])) {
                    $page  = $_GET["page"]; 
                } else { 
                    $page = 1; 
                }
                $start_from = ($page-1) * $limit;

                $query = "SELECT m.id_pendaftar, m.nama_musisi, m.nim_musisi, m.email_musisi, m.alamat_musisi, 
                                 m.hp_musisi, ps.id_genre, m.pengalaman, m.usia, 
                                 pb.nama_instrumen, m.tanggal_tambah_data 
                          FROM pendaftar m 
                          LEFT JOIN table_genre_musik ps ON m.id_genre = ps.id_genre
                          LEFT JOIN pilihan_instrumen pb ON m.id_pilihan_instrumen = pb.id_pilihan_instrumen
                          WHERE m.tanggal_hapus_data IS NULL";

                if (!empty($conditional)) {
                    foreach ($conditional as $key => $value) {
                        $query .= " $key '$value'";
                    }
                }

                $query .= " LIMIT $start_from, $limit";

                $result = $conn->query($query);
                $totalRows = $result->num_rows;

                if ($totalRows > 0) {
                    foreach ($result as $key => $row) {
                        echo "<tr>";
                        echo "<td>".($key + 1)."</td>";
                        echo "<td>".$row['nama_musisi']."</td>";
                        echo "<td>".$row['nim_musisi']."</td>";
                        echo "<td>".$row['email_musisi']."</td>";
                        echo "<td>".$row['alamat_musisi']."</td>";
                        echo "<td>".$row['hp_musisi']."</td>";
                        echo "<td>".$row['id_genre']."</td>";
                        echo "<td>".$row['pengalaman']."</td>";
                        echo "<td>".$row['usia']."</td>";
                        echo "<td>".$row['nama_instrumen']."</td>";
                        echo "<td>".$row['tanggal_tambah_data']."</td>";
                        echo "<td><a class='btn btn-sm btn-info' href='update.php?id=$row[id_pendaftar]'>Update</a></td>";
                        echo "<td><a class='btn btn-sm btn-danger delete-button' href='index.php?delete=$row[id_pendaftar]'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12' class='text-center'>Tidak ada data</td></tr>";
                }

                 // Pagination
                 $result_db = $conn->query("SELECT COUNT(id_pendaftar) FROM pendaftar WHERE tanggal_hapus_data IS NULL");
                 $row_db = $result_db->fetch_row(); 
                 $total_records = $row_db[0];  
                 $total_pages = ceil($total_records / $limit);
 
                 // Move $db->close() to after echoing pagination
                 ?>
                 </tbody>
             </table>
 
             <!-- Pagination -->
             <nav aria-label="Page navigation example">
                 <ul class="pagination justify-content-center">
                     <?php 
                     $pagLink = "";
 
                     for ($i=1; $i<=$total_pages; $i++) {
                         if ($i == $page) {
                             $pagLink .= "<li class='page-item active'><a class='page-link' href='index.php?page=".$i."'>".$i."</a></li>";
                         } else {
                             $pagLink .= "<li class='page-item'><a class='page-link' href='index.php?page=".$i."'>".$i."</a></li>";
                         }
                     }
                     echo $pagLink;
                     $db->close(); // Close connection after pagination
                     ?>

                
                </ul>
                    </nav>
        </div>
        <a href="logout.php" class="ml-auto mb-2"><button class="btn btn-danger">Logout</button></a>
    </div>    
    <script>
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const confirmed = confirm('Apakah Anda yakin ingin menghapus data ini?');
                if (confirmed) {
                    window.location.href = this.href;
                }
            });
        });

        document.getElementById('logout-button').addEventListener('click', function(event) {
            event.preventDefault();
            const confirmed = confirm('Apakah Anda yakin ingin logout?');
            if (confirmed) {
                window.location.href = this.href;
            }
        });
    </script>
</body>
</html>