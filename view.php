<?php
// The View renders the model into a web page suitable for interaction with the user.
// hier enkel echos van variabelen tussen html

//echo "test view.php <br>";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form method="GET" action="index.php">
        <!-- note: deze hier eventjes gezet om gemakkelijker te werken -->
        <div>
            <input type=submit>
        </div>
        <div style="border: 1px solid black; float: left;">
            <?php
            foreach ($products as $product) {
                // moet dit hieronder tussen accolades zetten, anders wil hij het niet selecteren + moet die dan nog in 1 geval tussen quotes schrijven ...
                echo "<input type=checkbox name='products_selected[]' value='{$product->get_product_name()}'>{$product->get_product_name()} kost {$product->get_product_price()} <br>";
            }
            /* OLD WAY, NOT HAVING CREATED CLASS FIRST          
   foreach ($products_object as $product_array) {
                // var_dump($product_array);
                // var_dump(is_array($product_array));
                // count($product_array);
                for ($i = 0; $i < count($product_array); $i++) {
                    // kan niet zomaar aan name die een property is tussen haakjes
                    $name = 'name';
                    $price = 'price';
                    // moet dit hieronder tussen accolades zetten, anders wil hij het niet selecteren + moet die dan nog in 1 geval tussen quotes schrijven ...
                    echo "<input type=checkbox name='products_selected[]' value='{$product_array[$i]->$name}'>{$product_array[$i]->$name} kost {$product_array[$i]->$price} <br>";
                }
            } */
            ?>
        </div>
        <div style="border: 1px solid black; float: right;">
            <?php
            // dit ziet er al wat simpeler uit doordat boel op andere pagina's gebeurt, MVC-style
            foreach ($all_customers as $customer) {
                echo "<input type=radio name=customer_selected value='$customer->name'>$customer->name<br>";
            }
            ?>
        </div>
    </form>
</body>

</html>