<?php 

// ###### Class Koneksi (PARENT CLASS) ###### //
class database{  							
 
	private $hostname = "localhost";
	private $username = "root";
	private $password = "";
	private $dbname = "warung_online";
	private $koneksi = "";

	public function __construct(){
		$this->koneksi = mysqli_connect($this->hostname, $this->username, $this->password,$this->dbname);
		if (mysqli_connect_errno()){
			echo "Koneksi database gagal : " . mysqli_connect_error();
		}
	}
	public function getConnection() {
        return $this->koneksi;
	}
}




// ###### Class Admin (CHILD CLASS) ###### //
class AdminClass extends database{

	// FUNGSI UNTUK MELOGIN SESSION
	public function login_admin(){
		$user = mysqli_real_escape_string($this->getConnection(), $_POST['user']);
        $pass = mysqli_real_escape_string($this->getConnection(), $_POST['pass']);
		
		$login = mysqli_query($this->getConnection(), "SELECT * FROM tb_admin WHERE username = '".$user."' AND password = '".MD5($pass)."'");
		return $login;
	}

	// FUNGSI UNTUK MENGUBAH PROFIL
	public function profile(){
		$query = mysqli_query($this->getConnection(), "SELECT * FROM tb_admin WHERE admin_id = '".$_SESSION['id']."'" );
    	$d = mysqli_fetch_object($query);
		
		$nama   = ucwords($_POST['nama']);
        $user   = $_POST['user'];
        $hp     = $_POST['hp'];
        $email  = $_POST['email'];
        $alamat = ucwords($_POST['alamat']);

        $update = mysqli_query($this->getConnection(), "UPDATE tb_admin SET
                    admin_name = '".$nama."',
                    username = '".$user."',
                    admin_telp = '".$hp."',
                    admin_email = '".$email."',
                    admin_address = '".$alamat."'
                    WHERE admin_id = '".$d->admin_id."' ");

		return $update;
	}

	// FUNGSI UNTUK MENGUBAH PASSWORD
	public function ubah_password(){
		if(isset($_POST['ubah_password'])){
			$query = mysqli_query($this->getConnection(), "SELECT * FROM tb_admin WHERE admin_id = '".$_SESSION['id']."'" );
    		$d = mysqli_fetch_object($query);

			$pass1   = $_POST['pass1'];
			$pass2   = $_POST['pass2'];
			
			if($pass2 != $pass1){
				echo "Password Tidak Sama!";
			}
			else{
				$u_pass = mysqli_query($this->getConnection(), "UPDATE tb_admin SET
							password = '".MD5($pass1)."'
							WHERE admin_id = '".$d->admin_id."' ");
				if($u_pass){
					echo '<script>alert("Data Berhasil Diubah")</script>';
					echo '<script>window.location="profile.php"</script>';
				}
				else{
					echo 'gagal'.mysqli_error($this->getConnection());
				}
			}
		}
	}

	//FUNGSI UNTUK MENAMPILKAN KONTAK
	public function tampil_kontak(){
		$kontak = mysqli_query($this->getConnection(), "SELECT * FROM tb_admin WHERE admin_id = 1");
		return $kontak;
	}
}



// ###### Class Kategori (CHILD CLASS) ###### //
class CategoryClass extends database{		

	// FUNGSI UNTUK MENAMPILKAN KATEGORI
	public function tampil_kategori(){  			
		$tampil  = mysqli_query($this->getConnection(), "SELECT * FROM tb_kategori ORDER BY category_id DESC");
		return $tampil;
	}

	// FUNGSI UNTUK MENAMBAH KATEGORI
	public function tambah_kategori($nama){		
		$tambah = mysqli_query($this->getConnection(), "INSERT INTO tb_kategori VALUES (null, '".$nama."')");
		return $tambah;

	}

	// FUNGSI UNTUK MENGAMBIL ID KATEGORI
	public function get_idkategori(){				
		$id = mysqli_query($this->getConnection(), "SELECT * FROM tb_kategori WHERE category_id = '".$_GET['id']."'");
		return $id;
	}

	// FUNGSI UNTUK MENGEDIT KATEGORI
	public function edit_kategori(){				
		$k = mysqli_fetch_object($this->get_idkategori());
		$nama   = ucwords($_POST['nama']);
		$edit = mysqli_query($this->getConnection(), "UPDATE tb_kategori SET category_name = '".$nama."' WHERE category_id = '".$k->category_id."'");
		return $edit;
	}

	// FUNGSI UNTUK MENGHAPUS KATEGORI
	public function hapus_kategori($idk){			
		$hapus = mysqli_query($this->getConnection(), "DELETE FROM tb_kategori WHERE category_id = '$idk' ");
		return $hapus;
	}
}




// ######  Class Produk (CHILD CLASS) ###### //
class ProductClass extends database{	

	// FUNGSI UNTUK MENAMPILKAN PRODUK
	public function tampil_produk(){
		$tampil = mysqli_query($this->getConnection(), "SELECT * FROM tb_product LEFT JOIN tb_kategori USING (category_id) ORDER BY product_id DESC");
		return $tampil;
	}

	// FUNGSI UNTUK MENAMBAH PRODUK
	public function tambah_produk(){
		// menampung inputan dari form
		$kategori 	= $_POST['kategori'];
		$nama 		= $_POST['nama'];
		$harga 		= $_POST['harga'];
		$deskripsi 	= $_POST['deskripsi'];
		$status 	= $_POST['status'];

		// menampung data file yang diupload
		$filename = $_FILES['gambar']['name'];
		$tmp_name = $_FILES['gambar']['tmp_name'];

		$type1 = explode('.', $filename);
		$type2 = $type1[1];

		$newname = 'produk'.time().'.'.$type2;

		// menampung data format file yang diizinkan
		$tipe_diizinkan = array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'HEIC', 'gif');

		// validasi format file
		if(!in_array($type2, $tipe_diizinkan)){
			// jika format file tidak ada di dalam tipe diizinkan
			echo '<script>alert("Format file tidak diizinkan")</scrtip>';

		}else{
			// jika format file sesuai dengan yang ada di dalam array tipe diizinkan
			// proses upload file sekaligus insert ke database
			move_uploaded_file($tmp_name, './produk/'.$newname);

			$insert = mysqli_query($this->getConnection(), "INSERT INTO tb_product VALUES (
						null,
						'".$kategori."',
						'".$nama."',
						'".$harga."',
						'".$deskripsi."',
						'".$newname."',
						'".$status."',
						null
							) ");

			if($insert){
				echo '<script>alert("Tambah data berhasil")</script>';
				echo '<script>window.location="list-product.php"</script>';
			}else{
				echo 'gagal '.mysqli_error($this->getConnection());
			}

		}
	}

	// FUNGSI UNTUK MENGAMBIL ID PRODUK
	public function get_idproduk(){
		$id = mysqli_query($this->getConnection(), "SELECT * FROM tb_product WHERE product_id = '".$_GET['id']."' ");
		return $id;
	}

	// FUNGSI UNTUK MENGEDIT PRODUK
	public function edit_produk(){
		$p = mysqli_fetch_object($this->get_idproduk());

		// data inputan dari form
		$kategori 	= $_POST['kategori'];
		$nama 		= $_POST['nama'];
		$harga 		= $_POST['harga'];
		$deskripsi 	= $_POST['deskripsi'];
		$status 	= $_POST['status'];
		$foto 	 	= $_POST['foto'];
		
		// data gambar yang baru
		$filename = $_FILES['gambar']['name'];
		$tmp_name = $_FILES['gambar']['tmp_name'];

		// jika admin ganti gambar
		if($filename != ''){
			$type1 = explode('.', $filename);
			$type2 = $type1[1];

			$newname = 'produk'.time().'.'.$type2;

			// menampung data format file yang diizinkan
			$tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif');

			// validasi format file
			if(!in_array($type2, $tipe_diizinkan)){
				// jika format file tidak ada di dalam tipe diizinkan
				echo '<script>alert("Format file tidak diizinkan")</scrtip>';

			}else{
				unlink('./produk/'.$foto);
				move_uploaded_file($tmp_name, './produk/'.$newname);
				$namagambar = $newname;
			}

		}else{
			// jika admin tidak ganti gambar
			$namagambar = $foto;
			
		}

		// query update data produk
		$update = mysqli_query($this->getConnection(), "UPDATE tb_product SET 
								category_id = '".$kategori."',
								product_name = '".$nama."',
								product_price = '".$harga."',
								product_description = '".$deskripsi."',
								product_images = '".$namagambar."',
								product_status = '".$status."'
								WHERE product_id = '".$p->product_id."'	");
		if($update){
			echo '<script>alert("Ubah data berhasil")</script>';
			echo '<script>window.location="list-product.php"</script>';
		}else{
			echo 'gagal '.mysqli_error($this->getConnection());
		}
		
	}

	// FUNGSI UNTUK MENGHAPUS PRODUK
	public function hapus_produk($idp){		
		// Mendapatkan nama gambar produk
		$produk = mysqli_query($this->getConnection(),"SELECT product_image FROM tb_product WHERE product_id = '$idp'");
		$p = mysqli_fetch_object($produk);
	
		// Menghapus gambar produk dari direktori
		unlink('./produk/'.$p->product_image);
	
		// Menghapus produk dari database
		$delete = mysqli_query($this->getConnection(),"DELETE FROM tb_product WHERE product_id = '$idp'");
			
		return $delete;
		
	}
}





// ###### Class Customer Interface (CHILD CLASS) ###### //
class Customer extends database{

	public function produk_beranda(){
		$tampil = mysqli_query($this->getConnection(), "SELECT * FROM tb_product WHERE product_status = 1 ORDER BY product_id DESC LIMIT 8");
		return $tampil;
	}

	public function all_produk(){
		$where = "AND product_name LIKE '%".$_GET['search']."%' AND category_id LIKE '%".$_GET['kat']."%' ";
		$produk = mysqli_query($this->getConnection(), "SELECT * FROM tb_product WHERE product_status = 1 $where ORDER BY product_id DESC");
		return $produk;
	}

}

$con = new database();
$conn = $con->getConnection();

?>