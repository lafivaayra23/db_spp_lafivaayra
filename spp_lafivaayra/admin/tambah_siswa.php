<?php
include_once "../src/koneksi.php";

// =====================================================
// PROSES SIMPAN DATA SISWA
// =====================================================

if (isset($_POST['btnSimpan'])) {

    $nisn     = $_POST['nisn'];
    $nis      = $_POST['nis'];
    $nama     = $_POST['nama'];
    $id_kelas = $_POST['id_kelas'];
    $alamat   = $_POST['alamat'];
    $no_telp  = $_POST['no_telp'];
    $id_spp   = $_POST['id_spp'];

    // =====================================================
    // VALIDASI
    // =====================================================

    $pesanError = array();

    if (trim($nisn) == "") {
        $pesanError[] = "Data <b>NISN</b> tidak boleh kosong!";
    }

    if (trim($nis) == "") {
        $pesanError[] = "Data <b>NIS</b> tidak boleh kosong!";
    }

    if (trim($nama) == "") {
        $pesanError[] = "Data <b>NAMA</b> tidak boleh kosong!";
    }

    if ($id_kelas == "" || $id_kelas == "Kosong") {
        $pesanError[] = "Data <b>KELAS</b> harus dipilih!";
    }

    if (trim($alamat) == "") {
        $pesanError[] = "Data <b>ALAMAT</b> tidak boleh kosong!";
    }

    if (trim($no_telp) == "") {
        $pesanError[] = "Data <b>NOMOR TELEPON</b> tidak boleh kosong!";
    }

    if ($id_spp == "" || $id_spp == "Kosong") {
        $pesanError[] = "Data <b>SPP</b> harus dipilih!";
    }


    // =====================================================
    // JIKA ADA ERROR
    // =====================================================

    if (count($pesanError) > 0) {

        $noPesan = 0;

        foreach ($pesanError as $pesan_tampil) {

            $noPesan++;

            echo "&nbsp;&nbsp;".$noPesan.". ".$pesan_tampil."<br>";
        }

        echo "<br>";

    } else {


        // =====================================================
        // CEK NISN SUDAH ADA ATAU BELUM
        // =====================================================

        $cekSiswa = mysqli_query(
            $koneksi,
            "SELECT * FROM siswa WHERE nisn='$nisn'"
        );

        if (mysqli_num_rows($cekSiswa) > 0) {

            echo "<center>
                    <b>
                        <font color='red' size='4'>
                            NISN sudah terdaftar!
                        </font>
                    </b>
                  </center>";

            exit;
        }


        // =====================================================
        // SIMPAN DATA SISWA
        // =====================================================

        $sqlSiswa = mysqli_query(
            $koneksi,
            "INSERT INTO siswa
            (nisn, nis, nama, id_kelas, alamat, no_telp, id_spp)
            VALUES
            ('$nisn', '$nis', '$nama', '$id_kelas', '$alamat', '$no_telp', '$id_spp')"
        );


        // =====================================================
        // JIKA DATA SISWA BERHASIL DISIMPAN
        // =====================================================

        if ($sqlSiswa) {


            // =================================================
            // MEMBUAT 12 BULAN TAGIHAN SPP
            // =================================================

            $arraybulan = array(
                "Juli",
                "Agustus",
                "September",
                "Oktober",
                "Nopember",
                "Desember",
                "Januari",
                "Februari",
                "Maret",
                "April",
                "Mei",
                "Juni"
            );


            // Tahun awal
            $tahunsekarang = date("Y");


            // Jumlah tagihan yang berhasil dibuat
            $jumlahTagihan = 0;


            // =================================================
            // INSERT 12 TAGIHAN KE TABEL PEMBAYARAN
            // =================================================

            for ($i = 0; $i < 12; $i++) {

                $bulan = $arraybulan[$i];


                // Januari sampai Juni masuk tahun berikutnya
                if ($i == 6) {
                    $tahunsekarang++;
                }


                $sqlPembayaran = mysqli_query(
                    $koneksi,
                    "INSERT INTO pembayaran
                    (
                        nisn,
                        bulan_tagihan,
                        tahun_tagihan,
                        id_spp
                    )
                    VALUES
                    (
                        '$nisn',
                        '$bulan',
                        '$tahunsekarang',
                        '$id_spp'
                    )"
                );


                // Kalau berhasil
                if ($sqlPembayaran) {

                    $jumlahTagihan++;

                } else {

                    // Kalau gagal, tampilkan error MySQL
                    echo "<p style='color:red;'>
                            Gagal membuat tagihan bulan $bulan.
                            <br>
                            Error:
                            ".mysqli_error($koneksi)."
                          </p>";

                }
            }


            // =================================================
            // HASIL
            // =================================================

            if ($jumlahTagihan == 12) {

                echo "
                <center>
                    <b>
                        <font color='red' size='4'>
                            Data Siswa dan 12 Tagihan Berhasil Disimpan
                        </font>
                    </b>
                </center>
                <br>";

                echo "
                <meta http-equiv='refresh'
                      content='2; url=?open=data_siswa'>";

            } else {

                echo "
                <center>
                    <b>
                        <font color='red' size='4'>
                            Data siswa berhasil disimpan,
                            tetapi tagihan tidak lengkap.
                        </font>
                    </b>
                </center>
                <br>";

                echo "
                <p>
                    Jumlah tagihan yang berhasil dibuat:
                    <b>$jumlahTagihan dari 12</b>
                </p>";

            }


        } else {

            echo "
            <center>
                <b>
                    <font color='red' size='4'>
                        Data Siswa Gagal Disimpan!
                    </font>
                </b>
            </center>
            <br>";

            echo "Error: " . mysqli_error($koneksi);
        }

        exit;
    }
}

?>

<!-- =====================================================
     FORM TAMBAH SISWA
===================================================== -->

<form
    action=""
    method="post"
>

<table cellspacing="1" cellpadding="3">

    <tr>
        <td bgcolor="#CCCCCC" colspan="3">
            <strong>TAMBAH DATA SISWA</strong>
        </td>
    </tr>


    <!-- NISN -->

    <tr>
        <td>NISN</td>
        <td>:</td>
        <td>
            <input
                name="nisn"
                type="text"
                size="20"
                maxlength="20"
            />
        </td>
    </tr>


    <!-- NIS -->

    <tr>
        <td>NIS</td>
        <td>:</td>
        <td>
            <input
                name="nis"
                type="text"
                size="20"
                maxlength="20"
            />
        </td>
    </tr>


    <!-- NAMA -->

    <tr>
        <td>NAMA</td>
        <td>:</td>
        <td>
            <input
                name="nama"
                type="text"
                size="50"
                maxlength="50"
            />
        </td>
    </tr>


    <!-- KELAS -->

    <tr>
        <td>KELAS</td>
        <td>:</td>
        <td>

            <select name="id_kelas">

                <option value="Kosong">
                    -- Pilih Kelas --
                </option>

                <?php

                $tampilkanKelas = mysqli_query(
                    $koneksi,
                    "SELECT * FROM kelas ORDER BY id_kelas ASC"
                );

                while ($bacaData = mysqli_fetch_array($tampilkanKelas)) {

                ?>

                    <option
                        value="<?php echo $bacaData['id_kelas']; ?>"
                    >
                        <?php echo $bacaData['nama_kelas']; ?>
                    </option>

                <?php

                }

                ?>

            </select>

        </td>
    </tr>


    <!-- ALAMAT -->

    <tr>
        <td>ALAMAT</td>
        <td>:</td>
        <td>
            <input
                name="alamat"
                type="text"
                size="50"
                maxlength="100"
            />
        </td>
    </tr>


    <!-- NO TELEPON -->

    <tr>
        <td>NO. TELEPON</td>
        <td>:</td>
        <td>
            <input
                name="no_telp"
                type="text"
                size="30"
                maxlength="30"
            />
        </td>
    </tr>


    <!-- SPP -->

    <tr>
        <td>SPP</td>
        <td>:</td>
        <td>

            <select name="id_spp">

                <option value="Kosong">
                    -- Pilih SPP --
                </option>

                <?php

                $tampilkanSPP = mysqli_query(
                    $koneksi,
                    "SELECT * FROM spp ORDER BY id_spp ASC"
                );

                while ($bacaData = mysqli_fetch_array($tampilkanSPP)) {

                ?>

                    <option
                        value="<?php echo $bacaData['id_spp']; ?>"
                    >
                        <?php echo $bacaData['nominal']; ?>
                    </option>

                <?php

                }

                ?>

            </select>

        </td>
    </tr>


    <!-- TOMBOL -->

    <tr>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

        <td>

            <input
                name="btnSimpan"
                type="submit"
                value="Simpan"
            />

        </td>

    </tr>

</table>

</form>