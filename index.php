<?php

include "Search.php";

$search = new Search();

$search->searchIn('coursdz.com');
$search->searchFor('رياضيات');
$result = $search->get();

header("Content-type:application/json");

echo json_encode($result);
