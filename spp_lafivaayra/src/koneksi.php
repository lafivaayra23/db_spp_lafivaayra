<?Php
$koneksi = mysqli_connect("localhost","root","","db_spp_lafivaayra");

    // check connection
    if (mysqli_connect_errno()){
        echo "koneksi database gagal : " . mysqli_connect_error();
    }
    ?>