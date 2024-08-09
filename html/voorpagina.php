<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified 
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 
header("Pragma: no-cache"); // HTTP/1.0 
$host = gethostname();
// connect to the database
$username="flurk";
$password="flurk";
$database="fri3d";
$link = mysqli_connect("localhost",$username,$password,$database);
$hostname = $_GET["hostname"];
$current_page = basename($_SERVER['PHP_SELF']);

//print($current_page);

// select the database
//mysql_select_db($database) or die("Unable to select database");
$watchdog = "INSERT INTO watchdog (host, pagina) values ('".$hostname."','".$current_page."')";
mysqli_query($link,$watchdog);
$result = mysqli_query($link,"SELECT * FROM fri3dp where oorsprong = '".$current_page."'");
$row = mysqli_fetch_array($result);
if ($hostname == "fri3d1") {$row[2] = "bar1.php";}
//print $row[2];
//if ($hostname == "fri3d6") {$row[2] = "kapel.php";}
?>

<!DOCTYPE html>
<html>
<head>

<style>
body, html {
  height: 100%;
  margin: 0;
  overflow: hidden; /* Hide scrollbars */
}

.bg {
  /* The image used */
  background-image: url("site_img_2022.jpg");

  /* Full height */
  height: 100%; 

  /* Center and scale the image nicely */
  background-position: center top;
  background-repeat: no-repeat;
  background-size: cover;
  background-color: black;
}
</style>
</head>
   <body>
      <script>
         setTimeout(function(){
            window.location.href = '<?php print $row[2]."?hostname=".$hostname; ?>';
         }, 10000);
      </script>
     <div class="bg"></div>
   </body>
</html>
