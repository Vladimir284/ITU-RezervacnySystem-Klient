<?php
require "service.php";

$db = new PeopleService();

//echo $db->getPeople();
$id = 1;
print_r($db->getPerson($id));

?>