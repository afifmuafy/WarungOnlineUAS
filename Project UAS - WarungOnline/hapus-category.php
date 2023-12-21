<?php

    include 'connect.php';
    
    if (isset($_GET['idk'])) {
        $hapus = new CategoryClass();
        $result = $hapus->hapus_kategori($_GET['idk']);
        
        if ($result) {
            echo '<script>window.location="list-category.php"</script>';
        } else {
            echo "Gagal menghapus kategori";
        }
    }
?>