<?php

session_start();

include "../src/koneksi.php";

if (
    isset($_GET['act']) &&
    $_GET['act'] == 'bayar'
) {

    $id_pembayaran = $_GET['id_pembayaran'];
    $nisn = $_GET['nisn'];

    // Cek ID petugas
    if (!isset($_SESSION['id_petugas'])) {

        die("ID Petugas tidak ditemukan. Silakan login kembali.");

    }

    $admin = $_SESSION['id_petugas'];

    // Membuat nomor pembayaran
    $today = date("ymd");

    $query = mysqli_query(
        $koneksi,
        "SELECT MAX(no_bayar) AS last
         FROM pembayaran
         WHERE no_bayar LIKE '$today%'"
    );

    if (!$query) {
        die("Query nomor pembayaran gagal : " . mysqli_error($koneksi));
    }

    $data = mysqli_fetch_array($query);

    $lastNoBayar = $data['last'];

    if (
        $lastNoBayar == NULL ||
        $lastNoBayar == ''
    ) {

        $lastNoUrut = 0;

    } else {

        $lastNoUrut = intval(substr($lastNoBayar, 6, 4));

    }

    $nextNoUrut = $lastNoUrut + 1;

    $nextNoBayar = $today . sprintf(
        '%04d',
        $nextNoUrut
    );

    $tgl_pembayaran = date('Y-m-d');

    // Update pembayaran
    $sql = mysqli_query(
        $koneksi,
        "UPDATE pembayaran SET
            no_bayar='$nextNoBayar',
            tgl_pembayaran='$tgl_pembayaran',
            keterangan='LUNAS',
            id_petugas='$admin'
         WHERE id_pembayaran='$id_pembayaran'"
    );

    if ($sql) {

        header(
            "Location: halaman_admin.php?open=data_pembayaran&nisn=$nisn"
        );

        exit;

    } else {

        die(
            "Pembayaran gagal diproses : "
            . mysqli_error($koneksi)
        );

    }

}

?>