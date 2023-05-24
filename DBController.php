<?php
// Deklarasi atribut koneksi dan informasi database
class DBController {
	private $host = "localhost";
	private $user = "root";
    private $password = "";
    private $database = "db_auth";
	private $conn;
	
    // Konstruktor dan metode connect()
    // onstruktor kelas ini dipanggil ketika objek 
    // DBController dibuat. Metode connect() digunakan 
    // untuk membuat koneksi ke database dengan menggunakan 
    // fungsi mysqli_connect(). Hasil koneksi disimpan dalam atribut $conn.
    function __construct() {
        $this->conn = $this->connect();
    }	
    function connect () {
        // Metode ini digunakan untuk menjalankan 
        // query basis yang tidak memerlukan binding 
        // parameter. Query dieksekusi dengan menggunakan 
        // fungsi mysqli_query(), dan hasilnya diperoleh 
        // dalam bentuk array asosiatif. Jika ada hasil 
        // yang ditemukan, hasilnya akan dikembalikan.
        $conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        return $conn;
    }
	
    function runBaseQuery($query) {
    // menjalankan query basis yang tidak memerlukan 
    // binding parameter. Query dieksekusi dengan menggunakan 
    // fungsi mysqli_query(), dan hasilnya diperoleh dalam 
    // bentuk array asosiatif. Jika ada hasil yang ditemukan, 
        $result = mysqli_query($this->conn,$query);
        while($row=mysqli_fetch_assoc($result)) {
        $resultset[] = $row;
        }		
        if(!empty($resultset))
        return $resultset;// hasilnya akan dikembalikan.
    }
    
    
    
    function runQuery($query, $param_type, $param_value_array) {
    // menjalankan query dengan binding parameter. 
    // Pertama, metode ini mempersiapkan pernyataan 
     // menggunakan objek koneksi ($conn) dan query yang 
     // diberikan. Kemudian, parameter yang diikat (binding) 
     // menggunakan metode bindQueryParams(). Setelah itu, 
     // pernyataan SQL dijalankan dengan metode execute(), 
     // dan hasilnya diperoleh dalam bentuk objek result. 
     // Jika ada hasil yang ditemukan, hasilnya akan 
     // dikembalikan dalam bentuk array asosiatif.    
        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
        $result = $sql->get_result();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }
        }
        
        if(!empty($resultset)) {
            return $resultset;
        }
    }
    
    function bindQueryParams($sql, $param_type, $param_value_array) {
    // menjalankan query dengan binding parameter. 
    // Pertama, metode ini mempersiapkan pernyataan 
    // SQL menggunakan objek koneksi ($conn) dan 
    // query yang diberikan. Kemudian, parameter 
    // yang diikat (binding) menggunakan metode 
    // bindQueryParams(). Setelah itu, pernyataan SQL dijalankan 
    // dengan metode execute(), dan hasilnya diperoleh dalam 
    // bentuk objek result. Jika ada hasil yang ditemukan, 
    // hasilnya akan dikembalikan dalam bentuk array asosiatif.
        $param_value_reference[] = & $param_type;
        for($i=0; $i<count($param_value_array); $i++) {
            $param_value_reference[] = & $param_value_array[$i];
        }
        call_user_func_array(array(
            $sql,
            'bind_param'
        ), $param_value_reference);
    }
    
    function insert($query, $param_type, $param_value_array) {
        //untuk menjalankan pernyataan INSERT 
        // pada database. Prosesnya mirip dengan metode 
        // runQuery(), tetapi tidak mengembalikan hasil 
        // apapun karena hanya menjalankan pernyataan INSERT.
        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
    }
    
    function update($query, $param_type, $param_value_array) {
        // UPDATE pada database. Prosesnya mirip 
        // dengan metode runQuery() dan insert(), 
        // tetapi tidak mengembalikan hasil apapun 
        // karena hanya menjalankan pernyataan UPDATE.
        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
    }
}
?>