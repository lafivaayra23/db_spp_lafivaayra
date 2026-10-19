<?php
include_once "../src/koneksi.php";

// Membaca variabel kode pada URL
if (isset($_GET['id_petugas'])) {

    $id_petugas = $_GET['id_petugas'];

    // Hapus data sesuai kode yang terbaca
    $sql = mysqli_query(
        $koneksi,
        "DELETE FROM petugas WHERE id_petugas='$id_petugas'"
    );

    if ($sql) {

        // Refresh halaman
        echo "<center>
                <b>
                    <font color='red' size='4'>
                        <p>Data Berhasil dihapus</p>
                    </font>
                </b>
              </center>
              <br>";

        echo "<meta http-equiv='refresh' content='2; url=?open=data_petugas'>";
    }

} else {

    // Jika tidak ada kode ditemukan di URL
    echo "<b>Data yang dihapus tidak ada</b>";

}
?>