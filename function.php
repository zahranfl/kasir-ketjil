<?php
session_start();

// Bikin koneksi
$conn = mysqli_connect("localhost", "root", "", "kasir");
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Helper kecil buat escape input sebelum masuk query (mencegah SQL Injection)
function esc($conn, $val) {
    return mysqli_real_escape_string($conn, $val);
}

// ==========================
// LOGIN
// ==========================
if (isset($_POST['login'])) {
    $username = esc($conn, $_POST['username']);
    $password = esc($conn, $_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' AND password = '$password'");

    if (mysqli_num_rows($result) === 1) {
        $_SESSION['login'] = 'true';
        header('Location: index.php');
        exit;
    } else {
        echo '
        <script>
            alert("Username atau password salah!");
            window.location.href = "login.php";
        </script>';
        exit;
    }
}

// ==========================
// PRODUK (stok.php)
// ==========================

// Tambah Barang
if (isset($_POST['tambah_barang'])) {
    $nama_produk = esc($conn, $_POST['nama_produk']);
    $deskripsi   = esc($conn, $_POST['deskripsi']);
    $stok        = esc($conn, $_POST['stok']);
    $harga       = esc($conn, $_POST['harga']);

    $insert = mysqli_query($conn, "INSERT INTO produk (nama_produk, deskripsi, stok, harga) VALUES ('$nama_produk', '$deskripsi', '$stok', '$harga')");

    if ($insert) {
        header('Location: stok.php');
        exit;
    } else {
        echo '
        <script>
            alert("Gagal menambahkan barang baru!");
            window.location.href = "stok.php";
        </script>';
        exit;
    }
}

// Edit Barang
if (isset($_POST['edit_barang'])) {
    $id_produk   = esc($conn, $_POST['id_produk']);
    $nama_produk = esc($conn, $_POST['nama_produk']);
    $deskripsi   = esc($conn, $_POST['deskripsi']);
    $stok        = esc($conn, $_POST['stok']);
    $harga       = esc($conn, $_POST['harga']);

    $update = mysqli_query($conn, "UPDATE produk SET nama_produk = '$nama_produk', deskripsi = '$deskripsi', stok = '$stok', harga = '$harga' WHERE id_produk = '$id_produk'");

    if ($update) {
        header('Location: stok.php');
        exit;
    } else {
        echo '
        <script>
            alert("Gagal memperbarui barang!");
            window.location.href = "stok.php";
        </script>';
        exit;
    }
}

// Hapus Barang
if (isset($_POST['hapus_barang'])) {
    $id_produk = esc($conn, $_POST['id_produk']);

    $delete = mysqli_query($conn, "DELETE FROM produk WHERE id_produk = '$id_produk'");

    if ($delete) {
        header('Location: stok.php');
        exit;
    } else {
        echo '
        <script>
            alert("Gagal menghapus barang! (mungkin barang ini masih dipakai di transaksi lain)");
            window.location.href = "stok.php";
        </script>';
        exit;
    }
}

// ==========================
// PELANGGAN (pelanggan.php)
// ==========================

// Tambah Pelanggan
if (isset($_POST['tambah_pelanggan'])) {
    $nama_pelanggan = esc($conn, $_POST['nama_pelanggan']);
    $no_telp        = esc($conn, $_POST['no_telp']);
    $alamat         = esc($conn, $_POST['alamat']);

    $insert = mysqli_query($conn, "INSERT INTO pelanggan (nama_pelanggan, no_telp, alamat) VALUES ('$nama_pelanggan', '$no_telp', '$alamat')");

    if ($insert) {
        header('Location: pelanggan.php');
        exit;
    } else {
        echo '
        <script>
            alert("Gagal menambahkan pelanggan baru!");
            window.location.href = "pelanggan.php";
        </script>';
        exit;
    }
}

// Edit Pelanggan
if (isset($_POST['edit_pelanggan'])) {
    $id_pelanggan   = esc($conn, $_POST['id_pelanggan']);
    $nama_pelanggan = esc($conn, $_POST['nama_pelanggan']);
    $no_telp        = esc($conn, $_POST['no_telp']);
    $alamat         = esc($conn, $_POST['alamat']);

    $update = mysqli_query($conn, "UPDATE pelanggan SET nama_pelanggan = '$nama_pelanggan', no_telp = '$no_telp', alamat = '$alamat' WHERE id_pelanggan = '$id_pelanggan'");

    if ($update) {
        header('Location: pelanggan.php');
        exit;
    } else {
        echo '
        <script>
            alert("Gagal memperbarui pelanggan!");
            window.location.href = "pelanggan.php";
        </script>';
        exit;
    }
}

// Hapus Pelanggan
if (isset($_POST['hapus_pelanggan'])) {
    $id_pelanggan = esc($conn, $_POST['id_pelanggan']);

    $delete = mysqli_query($conn, "DELETE FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");

    if ($delete) {
        header('Location: pelanggan.php');
        exit;
    } else {
        echo '
        <script>
            alert("Gagal menghapus pelanggan! (mungkin pelanggan ini masih punya pesanan)");
            window.location.href = "pelanggan.php";
        </script>';
        exit;
    }
}


// Tambah Pesanan
if (isset($_POST['tambah_pesanan'])) {
    $id_pelanggan = esc($conn, $_POST['id_pelanggan']);

    $insert = mysqli_query($conn, "INSERT INTO pesanan (id_pelanggan, tanggal) VALUES ('$id_pelanggan', NOW())");

    if ($insert) {
        header('Location: index.php');
        exit;
    } else {
        echo '
        <script>
            alert("Gagal menambahkan pesanan baru!");
            window.location.href = "index.php";
        </script>';
        exit;
    }
}

// Hapus Pesanan (sekaligus kembalikan stok & hapus detail_pesanan-nya)
if (isset($_POST['hapus_pesanan'])) {
    $id_pesanan = esc($conn, $_POST['id_pesanan']);

    // kembalikan stok tiap produk yang ada di pesanan ini
    $items = mysqli_query($conn, "SELECT * FROM detail_pesanan WHERE id_pesanan = '$id_pesanan'");
    while ($item = mysqli_fetch_array($items)) {
        $id_produk = $item['id_produk'];
        $qty       = $item['qty'];
        mysqli_query($conn, "UPDATE produk SET stok = stok + '$qty' WHERE id_produk = '$id_produk'");
    }

    mysqli_query($conn, "DELETE FROM detail_pesanan WHERE id_pesanan = '$id_pesanan'");
    $delete = mysqli_query($conn, "DELETE FROM pesanan WHERE id_pesanan = '$id_pesanan'");

    if ($delete) {
        header('Location: index.php');
        exit;
    } else {
        echo '
        <script>
            alert("Gagal menghapus pesanan!");
            window.location.href = "index.php";
        </script>';
        exit;
    }
}



// Tambah Produk ke Pesanan
if (isset($_POST['add_produk'])) {
    $id_produk = esc($conn, $_POST['id_produk']);
    $idp       = esc($conn, $_POST['idp']);
    $qty       = esc($conn, $_POST['qty']);

    $hitung1       = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id_produk'");
    $hitung2       = mysqli_fetch_array($hitung1);
    $stok_sekarang = $hitung2['stok'];

    if ($stok_sekarang >= $qty) {
        $cek_detail = mysqli_query($conn, "SELECT * FROM detail_pesanan WHERE id_pesanan = '$idp' AND id_produk = '$id_produk'");
        $ada        = mysqli_num_rows($cek_detail);

        if ($ada > 0) {
            $detail       = mysqli_fetch_array($cek_detail);
            $qty_sekarang = $detail['qty'];
            $qty_baru     = $qty_sekarang + $qty;

            $query_detail = mysqli_query($conn, "UPDATE detail_pesanan SET qty = '$qty_baru' WHERE id_pesanan = '$idp' AND id_produk = '$id_produk'");
        } else {
            $query_detail = mysqli_query($conn, "INSERT INTO detail_pesanan (id_pesanan, id_produk, qty) VALUES ('$idp', '$id_produk', '$qty')");
        }

        $selisih     = $stok_sekarang - $qty;
        $update_stok = mysqli_query($conn, "UPDATE produk SET stok = '$selisih' WHERE id_produk = '$id_produk'");

        if ($query_detail && $update_stok) {
            header('Location: view.php?idp=' . $idp);
            exit;
        } else {
            echo '
            <script>
                alert("Gagal menambahkan pesanan baru");
                window.location.href = "view.php?idp=' . $idp . '";
            </script>';
            exit;
        }
    } else {
        echo '
        <script>
            alert("Stok barang tidak cukup!");
            window.location.href = "view.php?idp=' . $idp . '";
        </script>';
        exit;
    }
}

// Edit item di Pesanan (ubah qty, stok produk otomatis menyesuaikan selisihnya)
if (isset($_POST['edit_item_pesanan'])) {
    $id_detail_pesanan = esc($conn, $_POST['id_detail_pesanan']);
    $id_produk         = esc($conn, $_POST['id_produk']);
    $idp                = esc($conn, $_POST['idp']);
    $qty_baru           = esc($conn, $_POST['qty']);

    $detail_now = mysqli_query($conn, "SELECT * FROM detail_pesanan WHERE id_detail_pesanan = '$id_detail_pesanan'");
    $detail_row = mysqli_fetch_array($detail_now);
    $qty_lama   = $detail_row['qty'];

    $produk_now = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id_produk'");
    $produk_row = mysqli_fetch_array($produk_now);
    $stok_sekarang = $produk_row['stok'];

    $selisih = $qty_baru - $qty_lama; // kalau positif berarti nambah qty, butuh stok lebih

    if ($selisih <= $stok_sekarang) {
        $update_detail = mysqli_query($conn, "UPDATE detail_pesanan SET qty = '$qty_baru' WHERE id_detail_pesanan = '$id_detail_pesanan'");
        $stok_baru     = $stok_sekarang - $selisih;
        $update_stok   = mysqli_query($conn, "UPDATE produk SET stok = '$stok_baru' WHERE id_produk = '$id_produk'");

        if ($update_detail && $update_stok) {
            header('Location: view.php?idp=' . $idp);
            exit;
        } else {
            echo '
            <script>
                alert("Gagal memperbarui jumlah barang!");
                window.location.href = "view.php?idp=' . $idp . '";
            </script>';
            exit;
        }
    } else {
        echo '
        <script>
            alert("Stok barang tidak cukup!");
            window.location.href = "view.php?idp=' . $idp . '";
        </script>';
        exit;
    }
}

// Hapus item dari Pesanan (stok produk dikembalikan)
if (isset($_POST['hapus_item_pesanan'])) {
    $id_detail_pesanan = esc($conn, $_POST['id_detail_pesanan']);
    $id_produk         = esc($conn, $_POST['id_produk']);
    $idp                = esc($conn, $_POST['idp']);
    $qty                = esc($conn, $_POST['qty']);

    $delete      = mysqli_query($conn, "DELETE FROM detail_pesanan WHERE id_detail_pesanan = '$id_detail_pesanan'");
    $update_stok = mysqli_query($conn, "UPDATE produk SET stok = stok + '$qty' WHERE id_produk = '$id_produk'");

    if ($delete && $update_stok) {
        header('Location: view.php?idp=' . $idp);
        exit;
    } else {
        echo '
        <script>
            alert("Gagal menghapus barang dari pesanan!");
            window.location.href = "view.php?idp=' . $idp . '";
        </script>';
        exit;
    }
}

//menambah barang masuk
if(isset($_POST['barang_masuk'])){
    $id_produk = esc($conn, $_POST['id_produk']);
    $qty = esc($conn, $_POST['qty']);

    // Insert ke tabel masuk
    $insertb = mysqli_query($conn, "INSERT INTO masuk (id_produk, qty) VALUES ('$id_produk', '$qty')");
    
    // Tambah stok produk
    $update_stok = mysqli_query($conn, "UPDATE produk SET stok = stok + '$qty' WHERE id_produk = '$id_produk'");

    if ($insertb && $update_stok) {
        header('Location: masuk.php');
        exit;
    } else {
        echo '
        <script>
            alert("Gagal menambahkan barang masuk!");
            window.location.href = "masuk.php";
        </script>';
        exit;
    }
}

// Edit Barang Masuk
if(isset($_POST['edit_masuk'])){
    $id_masuk = esc($conn, $_POST['id_masuk']);
    $id_produk = esc($conn, $_POST['id_produk']);
    $qty_baru = esc($conn, $_POST['qty']);
    $qty_lama = esc($conn, $_POST['qty_lama']);

    // Hitung selisih
    $selisih = $qty_baru - $qty_lama;

    // Update tabel masuk
    $update = mysqli_query($conn, "UPDATE masuk SET qty = '$qty_baru' WHERE id_masuk = '$id_masuk'");
    
    // Update stok produk (sesuai selisih)
    $update_stok = mysqli_query($conn, "UPDATE produk SET stok = stok + '$selisih' WHERE id_produk = '$id_produk'");

    if ($update && $update_stok) {
        header('Location: masuk.php');
        exit;
    } else {
        echo '
        <script>
            alert("Gagal memperbarui barang masuk!");
            window.location.href = "masuk.php";
        </script>';
        exit;
    }
}

// Hapus Barang Masuk
if(isset($_POST['hapus_masuk'])){
    $id_masuk = esc($conn, $_POST['id_masuk']);
    $id_produk = esc($conn, $_POST['id_produk']);
    $qty = esc($conn, $_POST['qty']);

    // Hapus dari tabel masuk
    $delete = mysqli_query($conn, "DELETE FROM masuk WHERE id_masuk = '$id_masuk'");
    
    // Kurangi stok produk
    $update_stok = mysqli_query($conn, "UPDATE produk SET stok = stok - '$qty' WHERE id_produk = '$id_produk'");

    if ($delete && $update_stok) {
        header('Location: masuk.php');
        exit;
    } else {
        echo '
        <script>
            alert("Gagal menghapus barang masuk!");
            window.location.href = "masuk.php";
        </script>';
        exit;
    }
}


?>
