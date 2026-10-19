<?php
include_once "../src/koneksi.php";

// Ambil data berdasarkan ID
$id_spp = $_GET['id_spp'];
$sql = mysqli_query($koneksi, "SELECT * FROM spp WHERE id_spp='$id_spp'");
$data = mysqli_fetch_array($sql);

// SKRIP TOMBOL SIMPAN DIKLIK
if (isset($_POST['btnSimpan'])) {

    $id_spp  = $_POST['id_spp'];
    $tahun   = $_POST['tahun'];
    $nominal = $_POST['nominal'];

    // Validasi Form Inputs
    $pesanError = array();

    if (trim($id_spp) == "") {
        $pesanError[] = "Data <b>ID SPP</b> tidak boleh kosong !";
    }

    if (trim($tahun) == "") {
        $pesanError[] = "Data <b>Tahun</b> tidak boleh kosong !";
    }

    if (trim($nominal) == "") {
        $pesanError[] = "Data <b>Nominal</b> tidak boleh kosong !";
    }

    // Menampilkan Pesan Error dari Form
    if (count($pesanError) >= 1) {
        $noPesan = 0;
        foreach ($pesanError as $indeks => $pesan_tampil) {
            $noPesan++;
            echo "&nbsp; $noPesan. $pesan_tampil <br>";
        }
        echo "<br>";
    } else {

        // UPDATE DATA
        $sql = mysqli_query($koneksi, "UPDATE spp SET
            tahun='$tahun',
            nominal='$nominal'
            WHERE id_spp='$id_spp'");

        if ($sql) {
            echo "<center><b><font color='red' size='4'>
                  <p>Data Berhasil Diubah</p>
                  </font></b></center><br>
                  <meta http-equiv='refresh' content='2; url=?open=data_spp'>";
        } else {
            echo "<center><b><font color='red' size='4'>
                  <p>Data Gagal Diubah!</p>
                  </font></b></center>
                  <meta http-equiv='refresh' content='2; url=?open=data_spp'>";
        }

        exit;
    }
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>?open=edit_spp&id_spp=<?php echo $data['id_spp']; ?>" method="post">
<table cellspacing="1" cellpadding="3">

<tr>
    <td bgcolor="#CCCCCC">
        <strong>EDIT DATA SPP</strong>
    </td>
</tr>

<tr>
    <td>ID SPP</td>
    <td>:</td>
    <td>
        <input name="id_spp" type="text" size="10" maxlength="4"
        value="<?php echo $data['id_spp']; ?>" readonly />
    </td>
</tr>

<tr>
    <td>Tahun</td>
    <td>:</td>
    <td>
        <input name="tahun" type="text" size="4" maxlength="4"
        value="<?php echo $data['tahun']; ?>" />
    </td>
</tr>

<tr>
    <td>Nominal</td>
    <td>:</td>
    <td>
        <input name="nominal" type="number" size="10" maxlength="10"
        value="<?php echo $data['nominal']; ?>" />
    </td>
</tr>

<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>
        <input name="btnSimpan" type="submit" value="Simpan" />
    </td>
</tr>

</table>
</form>