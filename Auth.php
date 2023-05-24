<?php
require "DBController.php";
class Auth {
    function getMemberByUsername($username) {
        //  untuk mendapatkan data anggota (member) berdasarkan 
        // nama pengguna (username). Metode ini membuat instance baru 
        // dari kelas DBController untuk berinteraksi dengan database. 
        // Kemudian, metode menjalankan query SQL untuk memilih semua 
        // kolom dari tabel "members" di mana kolom "member_name" sama 
        // dengan nama pengguna yang diberikan. Query dijalankan menggunakan 
        // metode runQuery dari objek $db_handle dengan menggunakan parameter 
        // placeholder "?" dan nilai yang sesuai yang disediakan dalam array. 
        // Hasil query kemudian dikembalikan sebagai hasil.
        $db_handle = new DBController();
        $query = "Select * from members where member_name = ?";
        $result = $db_handle->runQuery($query, 's', array($username));
        return $result;
    }
    
	function getTokenByUsername($username,$expired) {
        // untuk mendapatkan token berdasarkan nama 
        // pengguna dan status kadaluwarsa. Metode ini juga 
        // menggunakan objek $db_handle untuk berinteraksi 
        // dengan database. Metode menjalankan query SQL 
        // untuk memilih semua kolom dari tabel "tbl_token_auth" 
        // di mana kolom "username" sama dengan nama pengguna 
        // yang diberikan dan kolom "is_expired" sama dengan 
        // status kadaluwarsa yang diberikan. Query dijalankan 
        // menggunakan metode runQuery dengan menggunakan parameter 
        // placeholder "?" dan tipe data "s" (string) dan "i" (integer) 
        // untuk parameter. Hasil query kemudian dikembalikan sebagai hasil.
	    $db_handle = new DBController();
	    $query = "Select * from tbl_token_auth where username = ? and is_expired = ?";
	    $result = $db_handle->runQuery($query, 'si', array($username, $expired));
	    return $result;
    }
    
    function markAsExpired($tokenId) {
        // untuk menandai token sebagai kadaluwarsa berdasarkan 
        // ID token. Metode membuat instance baru dari kelas DBController 
        // untuk berinteraksi dengan database. Metode menjalankan query 
        // SQL untuk memperbarui tabel "tbl_token_auth" dengan mengatur 
        // kolom "is_expired" menjadi 1 (kadaluwarsa) di mana kolom "id" 
        // sama dengan ID token yang diberikan. Query dijalankan menggunakan 
        // metode update dari objek $db_handle dengan menggunakan 
        // parameter placeholder "?" dan tipe data "i" (integer) 
        // untuk parameter. Hasil query kemudian dikembalikan sebagai hasil.
        $db_handle = new DBController();
        $query = "UPDATE tbl_token_auth SET is_expired = ? WHERE id = ?";
        $expired = 1;
        $result = $db_handle->update($query, 'ii', array($expired, $tokenId));
        return $result;
    }
    
    function insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date) {
        // untuk menyisipkan token baru ke dalam tabel 
        // "tbl_token_auth". Metode membuat instance baru 
        // dari kelas DBController untuk berinteraksi dengan 
        // database. Metode menjalankan query SQL untuk 
        // menyisipkan data ke dalam tabel dengan menggunakan 
        // parameter placeholder "?" dan nilai yang sesuai 
        // yang disediakan dalam array. Query dijalankan menggunakan 
        // metode insert dari objek $db_handle dengan menggunakan 
        // parameter placeholder "?" dan tipe data "s" (string) 
        // untuk parameter. Hasil query kemudian dikembalikan sebagai hasil.
        $db_handle = new DBController();
        $query = "INSERT INTO tbl_token_auth (username, password_hash, selector_hash, expiry_date) values (?, ?, ?,?)";
        $result = $db_handle->insert($query, 'ssss', array($username, $random_password_hash, $random_selector_hash, $expiry_date));
        return $result;
    }
    
    function update($query, $paramType, $paramValueArray) {
        // Parameter kedua, $paramType, adalah string yang 
        // menentukan tipe data dari setiap parameter dalam query. 
        // Misalnya, "s" untuk string, "i" untuk integer, "d" 
        // untuk double, dan sebagainya. Parameter ketiga, $paramValueArray, 
        // adalah array yang berisi nilai-nilai yang akan diikatkan 
        // ke parameter dalam query. Metode ini memanggil metode update 
        // dari objek $db_handle dengan menggunakan parameter 
        // $query, $paramType, dan $paramValueArray yang diteruskan 
        // langsung dari metode pemanggil. Hasil query kemudian 
        // dikembalikan sebagai hasil.
        $db_handle = new DBController();
        $result = $db_handle->update($query, $paramType, $paramValueArray);
        return $result;
    }
}
?>