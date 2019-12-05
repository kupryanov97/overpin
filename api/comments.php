<?php
$host = "localhost"; 
$user = "test"; 
$password = "test"; 
$dbname = "comments"; 
$id = '';


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
      $name = $_POST["name"];
      $surname = $_POST["surname"];
      $comment = $_POST["comment"];
      $email = $_POST["email"];
      $time = $_POST["time"];
      $image = $_POST["image"];
      $sql = "insert into inst (name,surname, comment, email, time, image) values ('$name','$surname','$comment', '$email', '$time', '$image')"; 
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