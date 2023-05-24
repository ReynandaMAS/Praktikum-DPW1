<?php 
class Util {
   
    public function getToken($length)
    { 
    // menghasilkan token acak dengan panjang tertentu. 
    // Token dihasilkan dengan menggabungkan karakter-karakter 
    // dari set alfabet dan digit.   
        $token = "";

        // Variabel $codeAlphabet berisi 
        // karakter-karakter yang digunakan untuk token.
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";

        // Variabel $max 
        // menentukan indeks maksimum yang digunakan untuk mengambil 
        // karakter dari $codeAlphabet.
        // Selanjutnya, dalam loop for, 
        // metode cryptoRandSecure digunakan untuk menghasilkan indeks 
        // cak dari $codeAlphabet dan karakter yang sesuai ditambahkan 
        // ke variabel $token. Akhirnya, $token dikembalikan sebagai hasil.
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i ++) {
            $token .= $codeAlphabet[$this->cryptoRandSecure(0, $max)];
        }
        return $token;
    }
    
    public function cryptoRandSecure($min, $max)
    {
        // menghasilkan bilangan acak yang aman antara nilai $min dan $max. 
        // Variabel $range menentukan rentang bilangan acak yang akan 
        // dihasilkan. Jika rentang kurang dari 1, maka nilai $min dikembali
        // kan. Selanjutnya, metode menghitung panjang dalam byte ($bytes) dan bit ($bits) 
        // berdasarkan rentang yang diberikan. Variabel $filter digunakan 
        // untuk mengatur semua bit lebih rendah menjadi 1. Di dalam loop 
        // do-while, metode openssl_random_pseudo_bytes digunakan untuk 
        // menghasilkan bilangan acak dalam bentuk byte, kemudian diubah 
        // menjadi bilangan heksadesimal dengan bin2hex, dan akhirnya 
        // diubah menjadi bilangan desimal dengan hexdec. Bilangan acak 
        // ini kemudian disesuaikan dengan rentang yang diberikan dengan 
        // melakukan operasi bitwise & dengan $filter. Loop tersebut 
        // berjalan sampai nilai acak yang dihasilkan berada di dalam 
        // rentang yang valid. Nilai akhir yang dihasilkan adalah $min 
        // ditambah dengan nilai acak yang telah disesuaikan.
        $range = $max - $min;
        if ($range < 1) {
            return $min; // not so random...
        }
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }
    
    public function redirect($url) {
        // mengarahkan pengguna ke URL yang ditentukan menggunakan 
        // fungsi header dalam PHP. Setelah mengarahkan pengguna, 
        // metode ini menghentikan eksekusi program dengan exit. 
        // Dengan demikian, pengguna akan diarahkan ke halaman baru yang ditentukan oleh URL.
        header("Location:" . $url);
        exit;
    }
    
    public function clearAuthCookie() {
        // digunakan untuk menghapus cookie yang terkait 
        // dengan otentikasi. Metode ini memeriksa keberadaan 
        // cookie "member_login", "random_password", dan "random_selector" 
        // pada perangkat pengguna. Jika cookie tersebut tidak ada, 
        // maka cookie diatur nilainya menggunakan isset. Jika cookie 
        // tersebut ada, maka cookie diatur nilainya menjadi string kosong 
        // dengan menggunakan setcookie. Dengan melakukan ini, 
        // cookie tersebut akan dihapus dari perangkat pengguna.
        if (isset($_COOKIE["member_login"])) {
            setcookie("member_login", "");
        }
        if (isset($_COOKIE["random_password"])) {
            setcookie("random_password", "");
        }
        if (isset($_COOKIE["random_selector"])) {
            setcookie("random_selector", "");
        }
    }
}
?>