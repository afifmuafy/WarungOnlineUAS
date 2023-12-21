<?php
    session_start();
    include 'connect.php';
    if($_SESSION['status_login'] != true){
        header('location: login.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equif="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Quicksand:wght@500&display=swap" rel="stylesheet">
	<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <title>WarungOnline</title>
</head>
<body>
    <!-- HEADER -->
    <header>
        <div class="container">
            <h1><a href="">Warung Online</h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="list-category.php">Category List</a></li>
                <li><a href="list-product.php">Product List</a></li>
                <li><a href="logout.php">Sign Out</a></li>
            </ul>
        </div>
    </header>

    <!-- CONTENT -->
    <div class="section">
		<div class="container">
			<h3>Tambah Produk</h3>
			<div class="box">
				<form action="" method="POST" enctype="multipart/form-data">
					<select class="input-control" name="kategori" required>
						<option value="">--Pilih--</option>
						<?php 
							$k = new CategoryClass;
							$kategori = $k->tampil_kategori();
							while($r = mysqli_fetch_array($kategori)){
						?>
						<option value="<?php echo $r['category_id'] ?>"><?php echo $r['category_name'] ?></option>
						<?php } ?>
					</select>

					<input type="text" name="nama" class="input-control" placeholder="Nama Produk" required>
					<input type="text" name="harga" class="input-control" placeholder="Harga" required>
					<input type="file" name="gambar" class="input-control" required>
					<textarea class="input-control" name="deskripsi" placeholder="Deskripsi"></textarea><br>
					<select class="input-control" name="status">
						<option value="">--Pilih--</option>
						<option value="1">Aktif</option>
						<option value="0">Tidak Aktif</option>
					</select>
					<input type="submit" name="submit" value="Tambah" class="btn">
				</form>
				<?php 			
					if(isset($_POST['submit'])){						
						$insert = new ProductClass;
						$insert->tambah_produk();						
					}
				?>
			</div>
		</div>
	</div>
    <!-- FOOTER -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2023 - WarungOnline</small>
        </div>
    </footer>
	<script>
        CKEDITOR.replace( 'deskripsi' );
    </script>
</body>
</html>