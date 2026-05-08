<?php 
error_reporting(0);
session_start();
include_once 'config.php';
unset($_SESSION['UserId']);
unset($_SESSION['Admin']);
unset($_SESSION['Roll']);
?>
<script language="javascript">
window.location.href="index.php";
</script>