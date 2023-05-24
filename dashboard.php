<?php 
session_start();

require_once "authCookieSessionValidate.php";

if(!$isLoggedIn) {
    header("Location: ./");
}
?>
<style>
.member-dashboard {
    padding: 40px;
    background: #D2EDD5;
    color: #555;
    border-radius: 4px;
    display: inline-block;
}

.member-dashboard a {
    color: #09F;
    text-decoration: none;
}
</style>
<div class="member-dashboard">
    You have Successfully logged in!. <a href="logout.php">Logout</a>
</div>

<?php 
function curl($url){ //pembuatan fungsi cURL
    $ch = curl_init(); //melakukan inisiasi 
    curl_setopt($ch, CURLOPT_URL, $url); // memberikan nilai options seperti alamat URL destinasi, format hasil, header dan lainnya
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $output = curl_exec($ch);  // melakukan HTTP Request sesuai dengan options yang diberikan dan mengeksekusinya dengan fungsi
    curl_close($ch);  //setelah selesai mengeksekusi cURL sudah tidak digunakan lagi dan ditutup dengan fungsi
    return $output; //menghasilkan output cURL
}

$curl = curl("https://anoboy.monster/2019/02/black-clover-ep-1-sd-100-streaming/"); //alamat http yang diambil 

// mengubah JSON menjadi array
$data = json_decode($curl, TRUE);

?>
    
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <title>Tugas PWEB</title>
  </head>
  <body class="p-3 mb-2 bg-dark text-white">
    <div class="container mt-3 shadow-lg p-3 mb-5 bg-dark rounded">
        <h1 style="text-align:center;">Tugas PemroWeb</h1>
        <h3 style="text-align:center;">[ Mengambil data menggunakan CURL ]</h3>
        <br></br>
        <h5 style="text-align:center;">[ Tugas Pertama : Menampilkan Informasi dari website lain berupa text ] </h5>
        <br></br>
        <div class="table-responsive">
        <!-- <table class="table table-dark table-hover">
        <tr>
            <th> Jadwal </th>
            <th> Liga </th>
            <th> Channel TV </th>
        </tr>
        <tr>1</tr>
        <?php
        // URL TARGET
        $url = 'http://blog.kristiandes.com/grabbing-jadwal-bola-hari-ini/';
        //end
        // get / mengambil content berdasarkan url yang akan di curi datanya
        $content = file_get_contents($url);
        //
        // STEP 1 mengambil syntax pembuka
        $first_step = explode( "<table border='1' class='mainhati'>" , $content );
        //
        // STEP 2 mengambil syntax penutup
        $second_step = explode("</table>" , $first_step[1] );
        //
        // Replace syntax </tbody> dengan </tbody></table>
        $text1 = $second_step[0];
        //Tampilkan 
        echo $text1;
        ?>
        </table> -->
        </div>
        <br></br>
        <h5 style="text-align:center;"> [ Tugas : Menampilkan Informasi dari layanan lain menggunakan CURL ] </h5>
        <div class="table-responsive">
        <table class="table table-dark table-hover">  
        <thead class="table-dark "> 
        <tr>
            <th> ID </th>
            <th> Title </th>
            <th> URL </th>
        </tr>
        </thead>

        <!-- Tugas -->
        <!-- <?php foreach($data as $row){ ?> -->
        <!--  -->
        <tr>
            <td><?php echo $row["ID"]; ?></td>
            <td><?php echo $row["Title"]; ?></td>
            <td><a href="<?php echo $row["URL"]; ?>" target="_blank"><?php echo $row["URL"] ?></a></td>
        </tr>
        <?php } ?>
        </table>
        </div>
    </div>
   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  </body>
</html>
