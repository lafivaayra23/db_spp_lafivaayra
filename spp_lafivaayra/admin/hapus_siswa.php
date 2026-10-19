<?php
include_once "../src/koneksi.php";

// Membaca variabel Kode pada URL
if (isset($_GET['nisn'])) {

    $nisn = $_GET['nisn'];

    // Hapus data sesuai kode yang terbaca
    $sql = mysqli_query(
        $koneksi,
        "DELETE FROM siswa WHERE nisn='$nisn'"
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

        echo "<meta http-equiv='refresh' content='2; url=?open=data_siswa'>";
    }

} else {

    // Jika tidak ada data kode ditemukan di URL
    echo "<b>Data yang dihapus tidak ada</b>";

}
?>