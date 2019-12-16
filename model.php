<?php
// The Model represents the information on which the application operates--its business logic.
// Model/: Most of your code should be here, for example the Product and Customer class.

// echo "test model.php <br>";

// deze hier gezet om ik deze file het eerst aanroep
var_dump($_GET);
var_dump($_POST);
var_dump($_SESSION);
var_dump($_COOKIE);

$products_original = file_get_contents("products.json");
//var_dump($products_original);
// $products_cleaned = htmlspecialchars($products_original);
// var_dump($products_cleaned);
// $products_array = json_decode($products_cleaned);
// var_dump($products_array);
$products_original = file_get_contents("products.json");
$products_array = json_decode($products_original);
