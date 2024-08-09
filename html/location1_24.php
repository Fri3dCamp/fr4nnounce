<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified 
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 
header("Pragma: no-cache"); // HTTP/1.0 
//error_reporting(E_ALL); ini_set('display_errors', '1');
$json = file_get_contents('https://content.fri3d.be/fri3dcamp2024/schedule/export/schedule.json');
$json_data = json_decode($json,true,20);
$rooms = array("undefined","Hoofdpodium");  

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

$dagquery = mysqli_query($link,"SELECT * FROM dag ");
$dag = mysqli_fetch_array($dagquery);
print($dag);
if ($dag[0] == 0) {$dagnaam = "Vrijdag";} 
if ($dag[0] == 1) {$dagnaam = "Zaterdag";} 
if ($dag[0] == 2) {$dagnaam = "Zondag";} 
//print $dag[0];
?>
<!DOCTYPE html>
<html>
<head>

<?php include 'style.html';?>

</head>
<body style="background-color:black;" onload="startTime()">

<table style="width:100%">
  <tr>
     <img src="header.png">
  </tr>
  <tr>
    <td><?php print($dagnaam);?><div id="txt"></div></td>
  </tr>
  <tr>
    <td><?php
    
    foreach ($rooms as $room) {
print_r("<locatie>".$room."</locatie><br><br>");
$x = 0;

while(isset($json_data["schedule"]["conference"]["days"][$dag[0]]["rooms"][$room][$x])){
//$endtim = strtotime(($json_data["schedule"]["conference"]["days"][$dag[0]]["rooms"][$room][$x]["start"]) + strtotime($json_data["schedule"]["conference"]["days"][$dag[0]]["rooms"][$room][$x]["duration"]));
//print_r($dag[0]);
//$endtime = date_format($endtim, 'H:i');
print_r($json_data["schedule"]["conference"]["days"][$dag[0]]["rooms"][$room][$x]["start"]." : ");
print_r($json_data["schedule"]["conference"]["days"][$dag[0]]["rooms"][$room][$x]["title"]." met ");
//print_r($json_data["schedule"]["conference"]["days"][$dag[0]]["rooms"][$room][$x]["type"]." met ");
print_r($json_data["schedule"]["conference"]["days"][$dag[0]]["rooms"][$room][$x]["persons"][0]["public_name"]);
print_r("<br>");
$x++;
//print_r($x);
} ;                                                                       
print_r("<br>");
} ;

?>
</td>
  </tr>

</table>

 <script>
         setTimeout(function(){
            window.location.href = '<?php print $row[2]."?hostname=".$hostname; ?>';
         }, 10000);
         
         function startTime() {
  const today = new Date();
  let h = today.getHours();
  let m = today.getMinutes();
  let s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('txt').innerHTML =  h + ":" + m + ":" + s;
  setTimeout(startTime, 1000);
}

function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
      </script>
</body>
