<?php
include_once "../src/koneksi.php";


// =====================================================
// SKRIP TOMBOL SIMPAN DIKLIK
// =====================================================

if (isset($_POST['btnSimpan'])) {

    $id_petugas   = $_POST['id_petugas'];
    $username     = $_POST['username'];
    $password     = $_POST['password'];
    $nama_petugas = $_POST['nama_petugas'];
    $level        = $_POST['level'];


    // =====================================================
    // VALIDASI FORM INPUTS
    // =====================================================

    $pesanError = array();


    if (trim($id_petugas) == "") {
        $pesanError[] = "Data <b>ID PETUGAS</b> tidak boleh kosong !";
    }


    if (trim($username) == "") {
        $pesanError[] = "Data <b>USERNAME</b> tidak boleh kosong !";
    }


    if (trim($nama_petugas) == "") {
        $pesanError[] = "Data <b>NAMA PETUGAS</b> tidak boleh kosong !";
    }


    if (trim($level) == "" || $level == "Kosong") {
        $pesanError[] = "Data <b>LEVEL</b> harus dipilih !";
    }


    // =====================================================
    // MENAMPILKAN PESAN ERROR
    // =====================================================

    if (count($pesanError) >= 1) {

        $noPesan = 0;

        foreach ($pesanError as $indeks => $pesan_tampil) {

            $noPesan++;

            echo "&nbsp; $noPesan. $pesan_tampil <br>";
        }

        echo "<br>";


    } else {


        // =====================================================
        // UPDATE DATA PETUGAS
        // =====================================================

        // Jika password diisi, password akan diubah
        if (trim($password) != "") {

            $password = md5($password);

            $sqlEdit = mysqli_query(
                $koneksi,
                "UPDATE petugas SET
                username='$username',
                password='$password',
                nama_petugas='$nama_petugas',
                level='$level'
                WHERE id_petugas='$id_petugas'"
            );


        } else {

            // Jika password kosong,
            // password lama tetap digunakan

            $sqlEdit = mysqli_query(
                $koneksi,
                "UPDATE petugas SET
                username='$username',
                nama_petugas='$nama_petugas',
                level='$level'
                WHERE id_petugas='$id_petugas'"
            );
        }


        // =====================================================
        // JIKA DATA BERHASIL DIUBAH
        // =====================================================

        if ($sqlEdit) {

            echo "
            <center>
                <b>
                    <font color='red' size='4'>
                        <p>Data Berhasil Diubah</p>
                    </font>
                </b>
            </center>
            <br>

            <meta
                http-equiv='refresh'
                content='2; url=?open=data_petugas'
            >";


        } else {


            // =====================================================
            // JIKA DATA GAGAL DIUBAH
            // =====================================================

            echo "
            <center>
                <b>
                    <font color='red' size='4'>
                        <p>Data Gagal Diubah!</p>
                    </font>
                </b>
            </center>
            <br>

            <meta
                http-equiv='refresh'
                content='2; url=?open=data_petugas'
            >";
        }

        exit;
    }
}


// =====================================================
// MEMBACA DATA PETUGAS DARI DATABASE
// =====================================================

if (isset($_GET['id_petugas'])) {

    $id_petugas = $_GET['id_petugas'];

    $sql = mysqli_query(
        $koneksi,
        "SELECT * FROM petugas
         WHERE id_petugas='$id_petugas'"
    );

    $data = mysqli_fetch_array($sql);


} else {

    echo "<b>Data petugas tidak ditemukan!</b>";

    exit;
}

?>


<!-- =====================================================
     FORM EDIT DATA PETUGAS
===================================================== -->

<form
    action="<?php echo $_SERVER['PHP_SELF']; ?>?open=edit_petugas&id_petugas=<?php echo $data['id_petugas']; ?>"
    method="post"
>

<table cellspacing="1" cellpadding="3">


    <!-- =================================================
         JUDUL
    ================================================== -->

    <tr>

        <td bgcolor="#CCCCCC" colspan="3">

            <strong>
                EDIT DATA PETUGAS
            </strong>

        </td>

    </tr>


    <!-- =================================================
         ID PETUGAS
    ================================================== -->

    <tr>

        <td>
            ID PETUGAS
        </td>

        <td>
            :
        </td>

        <td>

            <input
                name="id_petugas"
                type="text"
                value="<?php echo $data['id_petugas']; ?>"
                size="10"
                maxlength="10"
                readonly="true"
            />

        </td>

    </tr>


    <!-- =================================================
         USERNAME
    ================================================== -->

    <tr>

        <td>
            USERNAME
        </td>

        <td>
            :
        </td>

        <td>

            <input
                name="username"
                type="text"
                value="<?php echo $data['username']; ?>"
                size="50"
                maxlength="50"
            />

        </td>

    </tr>


    <!-- =================================================
         PASSWORD
    ================================================== -->

    <tr>

        <td>
            PASSWORD
        </td>

        <td>
            :
        </td>

        <td>

            <input
                name="password"
                type="password"
                size="50"
                maxlength="50"
            />

            <br>

           

        </td>

    </tr>


    <!-- =================================================
         NAMA PETUGAS
    ================================================== -->

    <tr>

        <td>
            NAMA PETUGAS
        </td>

        <td>
            :
        </td>

        <td>

            <input
                name="nama_petugas"
                type="text"
                value="<?php echo $data['nama_petugas']; ?>"
                size="50"
                maxlength="50"
            />

        </td>

    </tr>


    <!-- =================================================
         LEVEL
    ================================================== -->

    <tr>

        <td>
            LEVEL
        </td>

        <td>
            :
        </td>

        <td>

            <select name="level">

                <option value="Kosong">
                    -- Pilih Level --
                </option>


                <option
                    value="Admin"
                    <?php
                    if ($data['level'] == "Admin") {
                        echo "selected";
                    }
                    ?>
                >
                    Admin
                </option>


                <option
                    value="Petugas"
                    <?php
                    if ($data['level'] == "Petugas") {
                        echo "selected";
                    }
                    ?>
                >
                    Petugas
                </option>

            </select>

        </td>

    </tr>


    <!-- =================================================
         TOMBOL SIMPAN
    ================================================== -->

    <tr>

        <td>
            &nbsp;
        </td>

        <td>
            &nbsp;
        </td>

        <td>

            <input
                name="btnSimpan"
                type="submit"
                id="btnSimpan"
                value="Simpan"
            />

        </td>

    </tr>


</table>

</form>