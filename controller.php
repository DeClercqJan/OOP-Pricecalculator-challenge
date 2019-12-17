<?php
// The Controller responds to user actions and invokes changes on the model or view as appropriate.
// Controller/: has access to GET/POST vars, receives the Request
// ? vb www.mijndomein.nl/index.php?route=media&media_id=345 ? http://www.sitemasters.be/tutorials/1/1/509/PHP/MVC_pattern_uitgelegd
// precies andere uitleg: controller staat tussen de view en het model (data) https://code-boxx.com/simple-php-mvc-example/

// echo "test controller.php <br>";

$products_object = new Product_DB();
// var_dump($products);

if (isset($_GET["products_selected"])) {
    $products_selected = $_GET["products_selected"];

    foreach ($products_selected as $key => $value) {
        // moeilijk om aan te spreken
        // echo $key;
        echo "$value <br>";
        $product_selected = $products_object->select($value);
        var_dump($product_selected);
        // $test = array_search($value, $products_array->name);
        // var_dump($test);
        /* foreach ($products_array as $product) {
        // var_dump($product->name);
        if ($value == $product->name) {
            echo "hoera! <br>";
            var_dump($product);
        }
    } */
    }

    // $test = $products->select();
    // var_dump($test);
}
