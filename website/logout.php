<?php
setcookie('authenticated', '', time() - 3600, '/', '', true, true); // Supprimer le cookie
header('Location: login.php');
exit;
?>
