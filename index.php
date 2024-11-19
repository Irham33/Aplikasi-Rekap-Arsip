<?php
session_start();

// Mengecek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Menangani logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard</title>
    <style>
        body {
            background-image: url('gedunginspek.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
        }
        .dashboard-container {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <!-- Navbar dengan Menu Logout -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">Welcome, <?php echo $_SESSION['username']; ?>!</span>
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
            </div>
        </div>
    </nav>

    <!-- Modal Konfirmasi Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin logout dari aplikasi?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="?logout=true" class="btn btn-danger">Ya, Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>


<?php

// Koneksi Database
$server = "localhost";
$user = "root";
$password = "";
$database = "dbarsip";

// Create Koneksi
$koneksi = mysqli_connect($server, $user, $password, $database) or die(mysqli_error($koneksi));

// Jika Tombol Simpan di Klik
if (isset($_POST['bsimpan'])) {

    // Pengujian Apakah Data akan Di edit atau disimpan baru
    if (isset($_GET['hal']) && $_GET['hal'] == "edit") {
        // data akan di edit
        $edit = mysqli_query($koneksi, "UPDATE t_arsip SET
            no_npd = '$_POST[tnnpd]',
            tgl_npd = '$_POST[ttanggalnpd]',
            no_sp = '$_POST[tnsp]',
            tgl_sp = '$_POST[ttanggalsp]',
            kegiatan = '$_POST[tsubkegiatan]',
            kel_dokumen = '$_POST[tkelengkapan]',
            sts_dokumen = '$_POST[tstatus]',
            catatan = '$_POST[tcatatan]'
            WHERE id_dokumen = '$_GET[id]'");

        // uji jika edit data sukses
        if ($edit) {
            echo "<script>
                alert('Yeayy!! Data Berhasil Diubah');
                document.location='index.php';
                </script>";
              exit();
        } else {
            echo "<script>
                alert('Edit Data Gagal!');
                document.location='index.php';
                </script>";
              exit();
        }
    } else {
        // Data Akan Disimpan Baru
        $simpan = mysqli_query($koneksi, "INSERT INTO t_arsip (no_npd, tgl_npd, no_sp, tgl_sp, kegiatan, kel_dokumen, sts_dokumen, catatan)
            VALUES ('$_POST[tnnpd]', '$_POST[ttanggalnpd]', '$_POST[tnsp]', '$_POST[ttanggalsp]', '$_POST[tsubkegiatan]', '$_POST[tkelengkapan]', '$_POST[tstatus]', '$_POST[tcatatan]')");

        // uji jika simpan data sukses
        if ($simpan) {
            echo "<script>
                alert('Simpan Data Sukses!');
                document.location='index.php';
                </script>";
                exit();
        } else {
            echo "<script>
                alert('Simpan Data Gagal!');
                document.location='index.php';
                </script>";
                exit();
        }
    }
}

// Deklarasi variabel untuk menampung data yang akan diedit
$vno_npd = "";
$vtgl_npd = "";
$vno_sp = "";
$vtgl_sp = "";
$vkegiatan = "";
$vkel_dokumen = "";
$vsts_dokumen = "";
$vcatatan = "";

// Pengujian Jika Tombol Edit di Klik
if (isset($_GET['hal']) && $_GET['hal'] == "edit") {
  // tampilkan data yang akan diedit
  $tampil = mysqli_query($koneksi, "SELECT * FROM t_arsip WHERE id_dokumen = '$_GET[id]'");
  $data = mysqli_fetch_array($tampil);
  if ($data) {
      // Jika data ditemukan, maka data di tampung ke dalam variabel
      $vno_npd = $data['no_npd'];
      $vtgl_npd = $data['tgl_npd'];
      $vno_sp = $data['no_sp'];
      $vtgl_sp = $data['tgl_sp'];
      $vkegiatan = $data['kegiatan'];
      $vkel_dokumen = $data['kel_dokumen'];
      $vsts_dokumen = $data['sts_dokumen'];
      $vcatatan = $data['catatan'];
  }
} else if (isset($_GET['hal']) && $_GET['hal'] == "hapus") {
  // Persiapan Hapus Data
  $id_dokumen = $_GET['id'];
  $hapus = mysqli_query($koneksi, "DELETE FROM t_arsip WHERE id_dokumen = '$id_dokumen'");

  // Pesan jika penghapusan berhasil
  if ($hapus) {
      echo "<script>
          alert('Data berhasil dihapus!');
          document.location='index.php';
          </script>";
  } else {
      echo "<script>
          alert('Data gagal dihapus!');
          document.location='index.php';
          </script>";
  }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Kendali Perjadin - Inspektorat Pemprov Jabar</title>
    </head>
    <style>
            body {
                background-color: rgba(255, 255, 255, 0.8);
                background-image: url('gedunginspek.jpg');
                background-size: cover; /* Membuat gambar menutupi seluruh layar */
                background-repeat: no-repeat; /* Menghindari pengulangan gambar */
                background-attachment: fixed; /* Membuat gambar tetap di tempat saat scrolling */
                background-position: center; /* Menyesuaikan posisi gambar di tengah */
             /* Ubah warna teks agar terlihat dengan latar belakang */
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h3 class="text-center" style="color: white; font-size: 250%;"><b>KENDALI ARSIP DOKUMEN PERJALANAN DINAS</b></h3>
        <h3 class="text-center"style="color: white;">INSPEKTORAT DAERAH PROVINSI JAWA BARAT</h3>
        <br><br>

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header text-center bg-danger text-white p-3">
                        <h6 id="form">Formulir Input Data Pelaksanaan Perjalanan Dinas</h6>
                    </div>
                    <div class="card-body bg-danger-subtle p-3" id="formInput">
                        <form method="post">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nomor NPD</label>
                                    <input type="text" name="tnnpd" value="<?=$vno_npd?>" class="form-control fst-italic" placeholder="Contoh: 22/NPD-Keu.2-IBC/08/2024">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal NPD</label>
                                    <input type="date" name="ttanggalnpd" value="<?=$vtgl_npd?>" class="form-control fst-italic">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nomor Surat Perintah</label>
                                    <input type="text" name="tnsp" value="<?=$vno_sp?>" class="form-control fst-italic" placeholder="Contoh: 293/PW.02.01/Sekre4">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Surat Perintah</label>
                                    <input type="date" name="ttanggalsp" value="<?=$vtgl_sp?>" class="form-control fst-italic">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label">Sub Kegiatan</label>
                                    <select class="form-select" name="tsubkegiatan">
                                        <option value="<?=$vkegiatan?>"><?="$vkegiatan"?></option>
                                        <option value="6.01.02.1.01.0002 Pengawasan Keuangan Pemerintah Daerah">6.01.02.1.01.0002 Pengawasan Keuangan Pemerintah Daerah</option>
                                        <option value="6.01.03.1.02.0004 Pendampingan, Asistensi, dan Verifikasi Penegakkan Integritas">6.01.03.1.02.0004 Pendampingan, Asistensi, dan Verifikasi Penegakkan Integritas</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Kelengkapan Dokumen</label>
                                    <select class="form-select" name="tkelengkapan">
                                        <option value="<?=$vkel_dokumen?>"><?=$vkel_dokumen?></option>
                                        <option value="Lengkap">Lengkap</option>
                                        <option value="Belum Lengkap">Belum Lengkap</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status Dokumen</label>
                                    <select class="form-select" name="tstatus">
                                        <option value="<?=$vsts_dokumen?>"><?=$vsts_dokumen?></option>
                                        <option value="Sudah Diarsipkan">Sudah Diarsipkan kedalam Bantex</option>
                                        <option value="Belum Diarsipkan">Belum Diarsipkan kedalam Bantex</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label">*Catatan (Bila Tidak Lengkap, jika lengkap isikan "-")</label>
                                    <input type="text" name="tcatatan" value="<?=$vcatatan?>" class="form-control fst-italic" placeholder="Catatan terkait ketidaklengkapan dokumen">
                                </div>
                            </div>

                            <div class="text-center">
                                <hr>
                                <button class="btn btn-danger me-2" name="bsimpan" type="submit">Simpan</button>
                                <button class="btn btn-warning" name="breset" type="reset">Reset</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-danger text-white p-3">
                        <p style="font-size: 0.7rem;">&copy; 2024 Irham Fauzan (215134046). All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4" style="padding: 10px;">
            <div class="card-header bg-danger text-white p-3">
                <h6>Data Arsip Dokumen Administrasi Perjalanan Dinas</h6>
            </div>
            <div class="card-body bg-danger-subtle p-3">
            <div class="col-md-6">
              <form method="POST">
                            <div class="input-group mb-3">
                <input type="text" name="tcari" value="<?php echo isset($_POST['tcari']) ? $_POST['tcari'] : ''; ?>" class="form-control" placeholder="Masukkan Kata Kunci">
                <button class="btn btn-danger" name="bcari" type="submit">Cari</button>
                <button class="btn btn-warning" name="breset" type="submit">Reset</button>
                </div>
                  </form>
            </div>
            </div>   
            
                <table class="table table-striped table-hover table-bordered" style="margin: 0 auto;">
                    <tr>
                        <th>No.</th>
                        <th>No. NPD</th>
                        <th>Tanggal NPD</th>
                        <th>No. SP</th>
                        <th>Tanggal SP</th>
                        <th>Sub Kegiatan</th>
                        <th>Kelengkapan Dokumen</th>
                        <th>Status Dokumen</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                    <?php
                    $no = 1;

                    //Untuk Pencarian data
                    //Jika Tombol Cari diklik
                    if (isset($_POST['bcari'])) {
                      $keyword = mysqli_real_escape_string($koneksi, $_POST['tcari']);
                      $q = "SELECT * FROM t_arsip WHERE no_npd LIKE '%$keyword%' OR sts_dokumen LIKE '%$keyword%' ORDER BY id_dokumen DESC";
                  } else {
                      $q = "SELECT * FROM t_arsip ORDER BY id_dokumen DESC";
                  }
                  
                    $tampil = mysqli_query($koneksi,$q);
                    while ($data = mysqli_fetch_array($tampil)) :
                    ?>
                        <tr>
                            <td><?=$no++;?></td>
                            <td><?=$data['no_npd']?></td>
                            <td><?=$data['tgl_npd']?></td>
                            <td><?=$data['no_sp']?></td>
                            <td><?=$data['tgl_sp']?></td>
                            <td><?=$data['kegiatan']?></td>
                            <td><?=$data['kel_dokumen']?></td>
                            <td><?=$data['sts_dokumen']?></td>
                            <td><?=$data['catatan']?></td>
                            <td>
                                <a href="index.php?hal=edit&id=<?=$data['id_dokumen']?>" class="btn btn-warning" onclick="scrollToForm()">Edit</a>
                                <a href="index.php?hal=hapus&id=<?=$data['id_dokumen']?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk smooth scroll -->
    <script>
        function scrollToForm() {
            document.getElementById("formInput").scrollIntoView({ behavior: "smooth" });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
