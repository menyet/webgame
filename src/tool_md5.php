<?php
echo '<form action="'.$_SERVER['PHP_SELF'].'" method="get">';
echo 'zadaj slovo: <input type="text" name="slovo"> ';
echo '<input type="submit"><hr>';
if (isset($_GET['slovo']) && $_GET['slovo'] != ""){
	echo 'md5 hash: '.md5($_GET['slovo']);
}

?>