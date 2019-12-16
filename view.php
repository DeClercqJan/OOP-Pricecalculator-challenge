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
        <?php
        foreach ($products_array as $product) {
            //var_dump($product);
            // moest $product->name tussen '' steken anders, deden space en quotes raar
            echo "<input type=checkbox name='products_selected[]' value='$product->name'>$product->name kost $product->price <br>";
        }
        ?>
        <input type=submit>
    </form>
</body>

</html>