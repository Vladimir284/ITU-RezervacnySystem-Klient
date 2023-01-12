<?php
// Create connection
$conn = mysqli_connect("sql6.webzdarma.cz",  "iturezervacn9298", "ITUrezervacnisystem1-");
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error() . "<br> Call Vít to fix it.<br>");
};

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error(). "<br>";
  echo "Contact admin on email vithrbacek (at) email.cz";
  exit();
}
                    
?>