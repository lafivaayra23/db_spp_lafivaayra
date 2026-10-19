<?php
include_once "../src/koneksi.php";
?>

<h3>Transaksi Pembayaran SPP</h3>

<form method="get" action="" autocomplete="off">

    NISN:
    <input
        type="text"
        name="nisn"
        autocomplete="off"
    >

    <input
        type="hidden"
        name="open"
        value="data_pembayaran"
    >

    <input
        type="submit"
        name="cari"
        value="Cari Siswa"
    >

</form>

<?php

if (isset($_GET['nisn']) && $_GET['nisn'] != '') {

    $nisn_cari = $_GET['nisn'];

    // Mencari data siswa
    $sqlSiswa = mysqli_query(
        $koneksi,
        "SELECT siswa.*, kelas.nama_kelas, spp.nominal
         FROM siswa
         JOIN kelas ON siswa.id_kelas = kelas.id_kelas
         JOIN spp ON siswa.id_spp = spp.id_spp
         WHERE siswa.nisn='$nisn_cari'"
    );

    if (!$sqlSiswa) {
        die("Query siswa gagal : " . mysqli_error($koneksi));
    }

    // Jika siswa tidak ditemukan
    if (mysqli_num_rows($sqlSiswa) == 0) {

        echo "<p><b>Data siswa tidak ditemukan.</b></p>";

    } else {

        $dataSiswa = mysqli_fetch_array($sqlSiswa);

        $nisn = $dataSiswa['nisn'];

?>

<br>

<h3>Biodata Siswa</h3>

<table cellspacing="1" cellpadding="3">

    <tr>
        <td>NISN</td>
        <td>:</td>
        <td>
            <?php echo $dataSiswa['nisn']; ?>
        </td>
    </tr>

    <tr>
        <td>Nama Siswa</td>
        <td>:</td>
        <td>
            <?php echo $dataSiswa['nama']; ?>
        </td>
    </tr>

    <tr>
        <td>Kelas</td>
        <td>:</td>
        <td>
            <?php echo $dataSiswa['nama_kelas']; ?>
        </td>
    </tr>

</table>

<br>

<h3>Tagihan SPP Siswa</h3>

<table border="1" cellspacing="1" cellpadding="5">

    <tr>

        <th>No</th>
        <th>Bulan</th>
        <th>Tahun</th>
        <th>No. Bayar</th>
        <th>Tgl. Bayar</th>
        <th>Jumlah</th>
        <th>Keterangan</th>
        <th>Bayar</th>

    </tr>

<?php

    // Mengambil tagihan pembayaran
    $sql = mysqli_query(
        $koneksi,
        "SELECT pembayaran.*, spp.nominal
         FROM pembayaran
         JOIN spp ON pembayaran.id_spp = spp.id_spp
         WHERE pembayaran.nisn='$nisn'
         ORDER BY pembayaran.id_pembayaran ASC"
    );

    if (!$sql) {
        die("Query pembayaran gagal : " . mysqli_error($koneksi));
    }

    $no = 1;

    while ($data = mysqli_fetch_array($sql)) {

?>

    <tr>

        <td>
            <?php echo $no; ?>
        </td>

        <td>
            <?php echo $data['bulan_tagihan']; ?>
        </td>

        <td>
            <?php echo $data['tahun_tagihan']; ?>
        </td>

        <td>
            <?php

            if ($data['no_bayar'] == NULL || $data['no_bayar'] == '') {
                echo "-";
            } else {
                echo $data['no_bayar'];
            }

            ?>
        </td>

        <td>
            <?php

            if ($data['tgl_pembayaran'] == NULL || $data['tgl_pembayaran'] == '') {
                echo "-";
            } else {
                echo $data['tgl_pembayaran'];
            }

            ?>
        </td>

        <td>
            <?php echo $data['nominal']; ?>
        </td>

        <td>
            <?php

            if ($data['keterangan'] == NULL || $data['keterangan'] == '') {

                echo "-";

            } else {

                echo $data['keterangan'];

            }

            ?>
        </td>

        <td align="center">

<?php

        // Kalau belum dibayar
        if (
            $data['no_bayar'] == NULL ||
            $data['no_bayar'] == ''
        ) {

?>

            <a
                href="proses_pembayaran.php?act=bayar&nisn=<?php echo $nisn; ?>&id_pembayaran=<?php echo $data['id_pembayaran']; ?>"
                onclick="return confirm('Yakin ingin melakukan pembayaran?')"
            >
                Bayar
            </a>

<?php

        } else {

            echo "<b>Lunas</b>";

        }

?>

        </td>

    </tr>

<?php

        $no++;

    }

?>

</table>

<?php

    }

}

?>

<br>

<p>
    Pembayaran SPP dilakukan dengan cara mencari tagihan siswa
    berdasarkan NISN melalui form di atas.
</p>