<?php
$host = "localhost"; 
$user = "test"; 
$password = "test"; 
$dbname = "comments"; 


$con = mysqli_connect($host, $user, $password,$dbname);
$method = $_SERVER['REQUEST_METHOD'];
if (!$con) {
  die("Connection failed: " . mysqli_connect_error());
}
switch ($method) {
    case 'GET':
      $sql = "select * from inst"; 
      break;
    case 'POST':
	  $id = $_POST["id"];
      $sql="delete from inst where id='$id'";
      break;
}
$result = mysqli_query($con,$sql);
	if ($method == 'GET') {
		if (!$id) echo '[';
		for ($i=0 ; $i<mysqli_num_rows($result) ; $i++) {
		  echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
		}
		if (!$id) echo ']';
	  } elseif ($method == 'POST') {
		echo json_encode($result);
	  } else {
		echo mysqli_affected_rows($con);
	  }
	
	$con->close();