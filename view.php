<?php
require 'cekLogin.php';

if(isset($_GET['idp'])){
    $idp = esc($conn, $_GET['idp']);

     $ambilnamapelanggan = mysqli_query($conn, "SELECT * FROM pesanan p, pelanggan pl WHERE p.id_pelanggan = pl.id_pelanggan AND p.id_pesanan = '$idp'");
    $np = mysqli_fetch_array($ambilnamapelanggan);
     $namapelanggan = $np['nama_pelanggan'];
} else {
 header('Location: index.php');
exit;
}

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
      <h1 class="mt-4">Data Pesanan: <?=$idp;?></h1>
      <h4 class="mt-4">Nama Pelanggan: <?=$namapelanggan;?></h4>
      
       
       <div class="row">
       <div class="col-xl-3 col-md-6">
        <!-- button to open the modal -->
        <button type="button" class="btn btn-info mb-4" data-bs-toggle="modal" data-bs-target="#myModal">
         tambah barang
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
           <th>No</th>
           <th>Nama Produk</th>
           <th>Harga Satuan</th>
           <th>Jumlah</th>
           <th>subtotal</th>
           <th>Aksi</th>
          </tr>
         </thead>
         <tbody>

         <?php
                  $get = mysqli_query($conn, "SELECT * FROM detail_pesanan p, produk pr WHERE p.id_produk = pr.id_produk and id_pesanan='$idp'");
                  $i = 1;

                  while($pesanan = mysqli_fetch_array($get)){
                  $id_detail_pesanan = $pesanan['id_detail_pesanan'];
                  $id_produk = $pesanan['id_produk'];
                  $qty = $pesanan['qty'];
                  $harga = $pesanan['harga'];
                  $nama_produk = $pesanan['nama_produk'];
                  $deskripsi = $pesanan['deskripsi'];
                  $subtotal = $qty * $harga;

                  ?>
                      <tr>
                          <td> <?=$i++;?></td>
                          <td> <?=$nama_produk;?> (<?=$deskripsi;?>)</td>
                          <td>Rp<?=number_format($harga);?></td>
                          <td><?=number_format($qty);?></td>
                          <td>Rp<?=number_format($subtotal);?></td>
                          <td>
                              <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?=$id_detail_pesanan;?>">Edit</button>
                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete<?=$id_detail_pesanan;?>">Delete</button>
                          </td>
                      </tr>

                      <!-- Modal Edit Item Pesanan -->
                      <div class="modal fade" id="edit<?=$id_detail_pesanan;?>">
                          <div class="modal-dialog">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h4 class="modal-title">Edit Jumlah Barang</h4>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                  </div>
                                  <form method="post" action="function.php">
                                      <div class="modal-body">
                                          <label class="mb-1 fw-bold">Nama Produk</label>
                                          <input type="text" class="form-control mb-3" value="<?=$nama_produk;?>" disabled>

                                          <label class="mb-1 fw-bold">Jumlah</label>
                                          <input type="number" name="qty" class="form-control" value="<?=$qty;?>" min="1" required>

                                          <input type="hidden" name="id_detail_pesanan" value="<?=$id_detail_pesanan;?>">
                                          <input type="hidden" name="id_produk" value="<?=$id_produk;?>">
                                          <input type="hidden" name="idp" value="<?=$idp;?>">
                                      </div>
                                      <div class="modal-footer">
                                          <button type="submit" class="btn btn-success" name="edit_item_pesanan">Simpan</button>
                                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                      </div>
                                  </form>
                              </div>
                          </div>
                      </div>

                      <!-- Modal Hapus Item Pesanan -->
                      <div class="modal fade" id="delete<?=$id_detail_pesanan;?>">
                          <div class="modal-dialog">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h4 class="modal-title">Hapus Barang dari Pesanan</h4>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                  </div>
                                  <form method="post" action="function.php">
                                      <div class="modal-body">
                                          Apakah Anda yakin ingin menghapus <strong><?=$nama_produk;?></strong> dari pesanan ini?
                                          <br><small class="text-danger">*Stok produk akan dikembalikan otomatis.</small>
                                          <input type="hidden" name="id_detail_pesanan" value="<?=$id_detail_pesanan;?>">
                                          <input type="hidden" name="id_produk" value="<?=$id_produk;?>">
                                          <input type="hidden" name="qty" value="<?=$qty;?>">
                                          <input type="hidden" name="idp" value="<?=$idp;?>">
                                      </div>
                                      <div class="modal-footer">
                                          <button type="submit" class="btn btn-danger" name="hapus_item_pesanan">Hapus</button>
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
  <h4 class="modal-title">tambah barang</h4>
  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
  </div>

 <form method="post" action="function.php">
 <!-- Modal body -->
  <div class="modal-body">
  Pilih Barang
  <select name="id_produk" class="form-control">
  
  <?php
  $get_produk = mysqli_query($conn, "SELECT * FROM produk where id_produk NOT IN (SELECT id_produk FROM detail_pesanan WHERE id_pesanan = '$idp')");
  while($produk = mysqli_fetch_array($get_produk)){
   $nama_produk = $produk['nama_produk'];
   $stok = $produk['stok'];
   $deskripsi = $produk['deskripsi'];
   $id_produk = $produk['id_produk'];
  ?>

  <option value="<?=$id_produk;?>"><?=$nama_produk;?> - <?=$deskripsi;?> (Stok: <?=$stok;?>)</option>

  <?php
  }
        ?>

  </select>

  <input type="number" name="qty" class="form-control mt-4" placeholder="jumlah" min="1" required>
  <input type="hidden" name="idp" value="<?=$idp;?>">
    </div>
 <!-- Modal footer -->
  <div class="modal-footer">
  <button type="submit" class="btn btn-success" name="add_produk">Submit</button>
  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
  </div>

 </form>

  

 </div>
 </div>
</div>

</html>