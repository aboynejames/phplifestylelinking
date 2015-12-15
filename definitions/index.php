
<?php include_once(dirname(dirname(__FILE__)). '/header.php'); ?>



<div class="mid-wrapper">

<div class="mid">

<?php
$getletter = $_GET['letid'];
letteratoz($getletter);

?>

</div>   <!-- closes mid -->
	
	<br />

</div>  <!-- closes mid wrapper-->

<?php include_once (dirname(dirname(__FILE__)). '/footer.php'); ?> 

