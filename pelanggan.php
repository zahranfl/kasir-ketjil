<?php
require 'cekLogin.php';

// hitung jumlah pelanggan
$h1 = mysqli_query($conn, "SELECT * FROM pelanggan");
$h2 = mysqli_num_rows($h1); //jumlah pelanggan

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Kelola Pelanggan</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">kasir ketjil</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->

        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Order
                            </a>
                            <a class="nav-link" href="stok.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Stok Barang
                            </a>
                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link active" href="pelanggan.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Kelola Pelanggan
                            </a>
                            <a class="nav-link" href="logout.php">
                                Logout
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Kelola Pelanggan</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">data pelanggan kasir ketjil</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Jumlah pelanggan: <?=$h2;?></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <!-- button to open the modal -->
                                <button type="button" class="btn btn-info mb-4" data-bs-toggle="modal" data-bs-target="#myModal">
                                    tambah pelanggan baru
                                </button>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Data Pelanggan
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pelanggan</th>
                                            <th>No Telp</th>
                                            <th>Alamat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                    $get = mysqli_query($conn, "SELECT * FROM pelanggan");
                                    $i = 1;

                                    while($pelanggan = mysqli_fetch_array($get)){
                                    $id_pelanggan = $pelanggan['id_pelanggan'];
                                    $nama_pelanggan = $pelanggan['nama_pelanggan'];
                                    $no_telp = $pelanggan['no_telp'];
                                    $alamat = $pelanggan['alamat'];

                                    ?>
                                        <tr>
                                            <td> <?=$i++;?></td>
                                            <td><?=$nama_pelanggan;?></td>
                                            <td><?=$no_telp;?></td>
                                            <td><?=$alamat;?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?=$id_pelanggan;?>">Edit</button>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete<?=$id_pelanggan;?>">Delete</button>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit Pelanggan -->
                                        <div class="modal fade" id="edit<?=$id_pelanggan;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Pelanggan</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="post" action="function.php">
                                                        <div class="modal-body">
                                                            <label class="mb-1 fw-bold">Nama Pelanggan</label>
                                                            <input type="text" name="nama_pelanggan" class="form-control mb-2" value="<?=$nama_pelanggan;?>" required>

                                                            <label class="mb-1 fw-bold">No Telp</label>
                                                            <input type="text" name="no_telp" class="form-control mb-2" value="<?=$no_telp;?>">

                                                            <label class="mb-1 fw-bold">Alamat</label>
                                                            <input type="text" name="alamat" class="form-control mb-2" value="<?=$alamat;?>">

                                                            <input type="hidden" name="id_pelanggan" value="<?=$id_pelanggan;?>">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success" name="edit_pelanggan">Simpan</button>
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Hapus Pelanggan -->
                                        <div class="modal fade" id="delete<?=$id_pelanggan;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus Pelanggan</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="post" action="function.php">
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus pelanggan <strong><?=$nama_pelanggan;?></strong>?
                                                            <input type="hidden" name="id_pelanggan" value="<?=$id_pelanggan;?>">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-danger" name="hapus_pelanggan">Hapus</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }; //end of while

                                    ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; kasir ketjil 2026</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>

<!-- The Modal: Tambah Pelanggan -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">tambah pelanggan baru</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

    <form method="post" action="function.php">
    <!-- Modal body -->
      <div class="modal-body">
        <input type="text" name="nama_pelanggan" class="form-control" placeholder="nama pelanggan" required>
        <input type="text" name="no_telp" class="form-control mt-2" placeholder="no telp">
        <input type="text" name="alamat" class="form-control mt-2" placeholder="alamat">
    </div>

     <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" name="tambah_pelanggan">Submit</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

    </form>

    </div>
  </div>
</div>

</html>
