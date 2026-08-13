<?php
require 'cekLogin.php';

//hitung jumlah pesanan
$h1 = mysqli_query($conn, "SELECT * FROM pesanan");
$h2 = mysqli_num_rows($h1); //jumlah pesanan

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Data Pesanan</title>
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
                            <a class="nav-link active" href="index.php">
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
                             <a class="nav-link" href="pelanggan.php">
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
                        <h1 class="mt-4">Data Pesanan</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Selamat datang di kasir ketjil!</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Jumlah Pesanan: <?=$h2; ?></div>
                                    
                                    </div>
                                </div>
                            </div>
                            
                             <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <!-- button to open the modal -->
                                <button type="button" class="btn btn-info mb-4" data-bs-toggle="modal" data-bs-target="#myModal">
                                    tambah pesanan baru
                                </button>
                            </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Data pesanan
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID Pesanan</th>
                                            <th>Tanggal</th>
                                            <th>Nama Pelanggan</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                       <?php
                                    $get = mysqli_query($conn, "SELECT * FROM pesanan p, pelanggan pl WHERE p.id_pelanggan = pl.id_pelanggan");
                                    

                                    while($pesanan = mysqli_fetch_array($get)){
                                    $id_pesanan = $pesanan['id_pesanan'];
                                    $tanggal = $pesanan['tanggal'];
                                    $nama_pelanggan = $pesanan['nama_pelanggan'];
                                    $alamat = $pesanan['alamat'];
                                    
                                    //hitung jumlah
                                    $hitung_jumlah = mysqli_query($conn, "SELECT * from detail_pesanan where id_pesanan = '$id_pesanan'");
                                    $jumlah = mysqli_num_rows($hitung_jumlah);    
                                    ?>
                                        <tr>
                                            <td> <?=$id_pesanan;?></td>
                                            <td><?=$tanggal;?></td>
                                            <td><?=$nama_pelanggan;?> - <?=$alamat;?></td>
                                            <td><?=$jumlah;?></td>
                                            <td>
                                                <a href="view.php?idp=<?=$id_pesanan;?>" class="btn btn-primary btn-sm" target="blank"> Tampilkan </a>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete<?=$id_pesanan;?>">Delete</button>
                                            </td>
                                        </tr>

                                        <!-- Modal Hapus Pesanan -->
                                        <div class="modal fade" id="delete<?=$id_pesanan;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus Pesanan</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="post" action="function.php">
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus pesanan <strong>#<?=$id_pesanan;?></strong> milik <strong><?=$nama_pelanggan;?></strong>?
                                                            <br><small class="text-danger">*Semua item di pesanan ini akan ikut terhapus dan stok produk akan dikembalikan.</small>
                                                            <input type="hidden" name="id_pesanan" value="<?=$id_pesanan;?>">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-danger" name="hapus_pesanan">Hapus</button>
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

    <!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">tambah pesanan baru</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

    <form method="post" action="function.php">
    <!-- Modal body -->
      <div class="modal-body">
        Pilih Pelanggan
        <select name="id_pelanggan" class="form-control">
        
        <?php
        $get_pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");
        while($pelanggan = mysqli_fetch_array($get_pelanggan)){
            $nama_pelanggan = $pelanggan['nama_pelanggan'];
            $id_pelanggan = $pelanggan['id_pelanggan'];
            $alamat = $pelanggan['alamat'];
        ?>

        <option value="<?=$id_pelanggan;?>"><?=$nama_pelanggan;?> - <?=$alamat;?></option>

        <?php
        }
        ?>

        </select>
        </div>

     <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" name="tambah_pesanan">Submit</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

    </form>

      

    </div>
  </div>
</div>

</html>
