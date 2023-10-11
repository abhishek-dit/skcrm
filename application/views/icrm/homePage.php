
<script>
	var SITE_URL = "<?php echo SITE_URL; ?>";
</script>
<?php
if(!isset($_SESSION['user_id']))
{
	echo "<script language='javascript'>";
	echo "window.location.href= SITE_URL + 'login'";
	echo "</script>";
}
else
{
	echo "<script language='javascript'>";
	echo "window.location.href= SITE_URL + 'home'";
	echo "</script>";
	//echo 1;
}
?>