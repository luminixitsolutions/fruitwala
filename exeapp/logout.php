<?php 
error_reporting(0);
session_start();
include_once 'config.php';
unset($_SESSION['User']);
?>
<script language="javascript">
window.location.href="index.php";
</script>