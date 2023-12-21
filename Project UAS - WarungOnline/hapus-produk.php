<?php 
	
	include 'connect.php';

	if (isset($_GET['idp'])) {
		$hapus = new ProductClass();
		$result = $hapus->hapus_produk($_GET['idp']);
		
		if ($result) {
			echo '<script>window.location="list-product.php"</script>';
		} else {
			echo "Gagal menghapus produk";
		}
	}

?>