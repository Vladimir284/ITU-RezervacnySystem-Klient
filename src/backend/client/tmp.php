<?php
require "service.php";

$db = new PeopleService();

echo $db->getPeople();

?>