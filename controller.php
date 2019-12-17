<?php
// The Controller responds to user actions and invokes changes on the model or view as appropriate.
// Controller/: has access to GET/POST vars, receives the Request
// ? vb www.mijndomein.nl/index.php?route=media&media_id=345 ? http://www.sitemasters.be/tutorials/1/1/509/PHP/MVC_pattern_uitgelegd
// precies andere uitleg: controller staat tussen de view en het model (data) https://code-boxx.com/simple-php-mvc-example/

// echo "test controller.php <br>";

$products_object = new Product_DB();
// let's make each product member of product class
foreach ($products_object as $product_array) {
    $products = [];
    for ($i = 0; $i < count($product_array); $i++) {
        $name = 'name';
        $description = 'description';
        $product_name = $product_array[$i]->$name;
        $product_description = $product_array[$i]->$description;
        $product_price = $product_array[$i]->price;
        $product = new Product($product_name, $product_description, $product_price);
        array_push($products, $product);
    }
}
$customers_object = new Customer_DB();
$all_customers = $customers_object->get_all_customers();

if (isset($_GET["products_selected"]) && isset($_GET["customer_selected"])) {
    $products_selected = $_GET["products_selected"];
    var_dump($products_selected);
    // find corresponding product in array of class objects
    // note: think it takes less computing power to have the largest array (products) on the outside. CORRECT OR NOT?
    foreach ($products as $product) {
        $product_name = $product->get_product_name();
        foreach ($products_selected as $key => $value) {
            if ($product_name == $value) {
                var_dump($product);
            }
        }
    }
    /*    $customer_selected = $_GET["customer_selected"];
    $customer = $customers_object->get_customer($customer_selected);
    var_dump($customer); */
}
