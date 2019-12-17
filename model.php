<?php
// The Model represents the information on which the application operates--its business logic.
// Model/: Most of your code should be here, for example the Product and Customer class.

// echo "test model.php <br>";

// deze hier gezet om ik deze file het eerst aanroep
var_dump($_GET);
var_dump($_POST);
var_dump($_SESSION);
var_dump($_COOKIE);

// $products_original = file_get_contents("products.json");
// $products_array = json_decode($products_original);

class Product_DB
{
    public $products;

    function __construct()
    {
        $products_original = file_get_contents("products.json");
        $products_array = json_decode($products_original);
        $this->products = $products_array;
    }

    function __destruct()
    {
        // TO DO
    }

    function select($product_name)
    {
        foreach ($this->products as $product) {
            $name = 'name';
            if ($product->$name == $product_name) {
                return $product;
            }
        }
    }
}

class Customer_DB
{
}

class Group_DB
{
}
