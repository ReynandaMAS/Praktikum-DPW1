<?php
// Bagian ini menginisialisasi sesi, memuat 
//file-file yang diperlukan (seperti Auth.php dan Util.php), dan memeriksa validitas cookie sesi pengguna.
session_start();

require_once "Auth.php";
require_once "Util.php";

$auth = new Auth();
$db_handle = new DBController();
$util = new Util();

require_once "authCookieSessionValidate.php";
// end

// Bagian ini memeriksa apakah pengguna sudah login. 
// Jika iya, pengguna akan diarahkan ke halaman dashboard.
if ($isLoggedIn) {
    $util->redirect("dashboard.php");
}
// end


// Bagian ini menangani aksi login yang dikirimkan melalui metode POST dari form login.
if (! empty($_POST["login"])) {

    // Bagian ini memeriksa keautentikan pengguna dengan membandingkan 
    // username dan password yang dimasukkan dengan data pengguna yang ada di database.
    $isAuthenticated = false;
    
    $username = $_POST["member_name"];
    $password = $_POST["member_password"];
    
    $user = $auth->getMemberByUsername($username);
    if (password_verify($password, $user[0]["member_password"])) {
        $isAuthenticated = true;
    }
    
    // Bagian ini menangani logika setelah autentikasi pengguna berhasil atau gagal. 
    // Jika autentikasi berhasil, session member_id akan disimpan dan cookie otentikasi akan 
    // di diatur jika opsi "Remember Me" diaktifkan. Jika autentikasi gagal, pesan kesalahan akan ditampilkan.
    if ($isAuthenticated) {
        $_SESSION["member_id"] = $user[0]["member_id"];
        
        // Set Auth Cookies if 'Remember Me' checked
        if (! empty($_POST["remember"])) {
            setcookie("member_login", $username, $cookie_expiration_time);
            
            $random_password = $util->getToken(16);
            setcookie("random_password", $random_password, $cookie_expiration_time);
            
            $random_selector = $util->getToken(32);
            setcookie("random_selector", $random_selector, $cookie_expiration_time);
            
            $random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);
            $random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);
            
            $expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);
            
            // mark existing token as expired
            $userToken = $auth->getTokenByUsername($username, 0);
            if (! empty($userToken[0]["id"])) {
                $auth->markAsExpired($userToken[0]["id"]);
            }
            // Insert new token
            $auth->insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date);
        } else {
            $util->clearAuthCookie();
        }
        $util->redirect("dashboard.php");
    } else {
        $message = "Invalid Login";
    }
}
?>

<!-- Bagian terakhir adalah kode HTML yang menampilkan form login. Di dalamnya,
terdapat logika PHP untuk menampilkan pesan kesalahan, mengisi nilai input dengan 
cookie yang tersimpan (jika ada), dan mengatur cookie "Remember Me". -->
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <title>Tugas PWEB</title>
  </head>
  <body class="p-3 mb-2 bg-dark text-white">
    <div class="container mt-3 shadow-lg p-3 mb-5 bg-dark rounded">
        <h1 style="text-align:center;">Tugas PemroWeb ( Login Form With Session Cookies )</h1>
        <br></br>
        <div class="mx-auto" style="width: 200px;">
<form action="" method="post" id="frmLogin" >
    <div class="error-message"><?php if(isset($message)) { echo $message; } ?></div>
    <div class="mb-3">
        <div>
            <label for="login" class="form-label">Username</label>
        </div>
        <div>
            <input name="member_name" type="text"
                value="<?php if(isset($_COOKIE["member_login"])) { echo $_COOKIE["member_login"]; } ?>"
                class="input-field">
        </div>
    </div>
    <div class="mb-3">
        <div>
            <label for="password" class="form-label">Password</label>
        </div>
        <div>
            <input name="member_password" type="password"
                value="<?php if(isset($_COOKIE["member_password"])) { echo $_COOKIE["member_password"]; } ?>"
                class="input-field">
        </div>
    </div>
    <div class="mb-3">
        <div>
            <input type="checkbox" name="remember" id="remember"
                <?php if(isset($_COOKIE["member_login"])) { ?> checked
                <?php } ?> /> <label for="remember-me">Remember me</label>
        </div>
    </div>
    <div class="mb-3">
        <div>
            <input type="submit" name="login" value="Login"
                class="form-submit-button"></span>
        </div>
    </div>
    <div>
            <label for="info" class="form-label">Default Username : admin</label>
        </div>
    <div>
            <label for="info" class="form-label">Default Password : admin</label>
    </div>
 </form>
 </div>
</div>
   
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
 </body>
</html>