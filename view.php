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
    <title>OOP-Pricecalculator-challenge</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <header>
        <h1>Pay less! </h1>
    </header>
    <article>
        <h2>Get the best combination of discounts for a range of products</h2>
        <form method="GET" action="index.php">
            <div>
                <label for="products">Select one or more products (hold shift to do so)</label>
                <select id="products" name="products_selected[]" multiple>

                    <?php foreach ($products as $product) {
                    ?>
                        <option value=<?php echo "'{$product->get_product_name()}'"; ?>>
                            <?php echo "{$product->get_product_name()} ({$product->get_product_price()} euro) <br>
                            description: {$product->get_product_description()}"; ?>
                        </option>
                    <?php

                                    } ?>

                </select>
            </div>
            <div>
                <label for="customers">Select client</label>
                <input list="customers" placeholder="Start typing..." name="customer_selected">
                <datalist id="customers">
                    <?php foreach ($customers as $customer) {
                    ?>
                        <option value="<?php echo $customer->get_customer_name(); ?>">
                        <?php
                                    } ?>
                </datalist>

            </div>
            <div style="">
                <input type=submit value="Get the best deal"></input>
            </div>
    </article>
    </form>
    <div id="results">
        <?php foreach ($result_array as $result) {
        ?><p><?php echo $result; ?></p>
            <!-- TO DO: category page -->
            <a href="test">See product info</a>
        <?php
                                    }
        ?>
    </div>
</body>

</html>