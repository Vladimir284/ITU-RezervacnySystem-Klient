<?php
require "service.php";

$db = new PeopleService();

//print_r($db->getPeople());
//$id = 2;
//print_r($db->getPerson($id));
//$myArray = array("Petr Novotny","petr@pacient.cz",123456789,"2023-01-14","12:00","Magnetoterapie","Pavel Novotny");
//$myJson = json_encode($myArray);
//echo gettype($myArray);
//echo "\n";
//print_r($myArray);
//echo "\n";
//echo gettype($myJson);
//echo "\n";
//print_r($myJson);
//echo "\n";
//$db->addPerson($myArray);
//$db->deletePerson($id);
//$name = "Andrej Szelte";
//$db->updatePerson($name)
//$person = array("Petr Novotny", "2023-01-14","12:00");
//$id = $db->personGetId($person);
//$db->deletePerson($id);
$newData = array("Bartolomej Blahac", "bartolomej@pacient.sk",123456789,"Dara Rolins");
$id = $db->personGetId(array("Bartolomej Blahac"));
//$db->updatePerson($newData, $id);
$db->updatePersonAllStats("Bartolomej Blahac",$newData);
?>