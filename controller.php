<?php
$products_object = new Product_DB();

// let's make each product member of product class
$products_object_accessible = $products_object->get_all_products();
$products = [];
foreach ($products_object_accessible as $product_object) {
    $product_name = $product_object->name;
    $product_description = $product_object->description;
    $product_price = $product_object->price;
    $product = new Product($product_name, $product_description, $product_price);
    array_push($products, $product);
}

$groups_object = new Group_DB();
$groups_object_accessible = $groups_object->get_all_groups();
sort($groups_object_accessible);
$groups_multidimensional = [];
$departments = [];
$companies = [];
foreach ($groups_object_accessible as $group_object) {
    if (property_exists($group_object, "group_id")) {
        array_push($companies, $group_object);
    } else {
        array_push($departments, $group_object);
    }
}
array_push($groups_multidimensional, $departments);
array_push($groups_multidimensional, $companies);

// let's make each customer customer class + multiple other classes, using multiple databases
$customers_object = new Customer_DB();
$customer_object_accessible = $customers_object->get_all_customers();
$customers = [];
foreach ($customer_object_accessible as $customer_object) {
    $customer_name = $customer_object->name;
    $customer_department_id = $customer_object->group_id;
    $customer_department  = "";
    $company_id = null;
    $customer_company = "";
    $customer_department_discount_variable = null;
    $customer_department_discount_fixed = null;
    $customer_company_discount_variable = null;
    $customer_company_discount_fixed = null;
    // linking multiple databases to enrich customer class
    foreach ($groups_multidimensional[1] as $department) {
        $name = 'name';
        $id = 'id';
        $group_id = 'group_id';
        // received a tip: group id of customer would refer to id of department
        if ($customer_department_id == $department->$id) {
            $customer_department = $department->name;
            $company_id = $department->group_id;
            if (property_exists($department, "variable_discount")) {
                $customer_department_discount_variable = $department->variable_discount;
            } else if (property_exists($department, "fixed_discount")) {
                $customer_department_discount_fixed = $department->fixed_discount;
            }

            foreach ($groups_multidimensional[0] as $company) {
                if ($department->$group_id == $company->$id) {
                    $customer_company = $company->name;
                    if (property_exists($company, "variable_discount")) {
                        $customer_company_discount_variable = $company->variable_discount;
                    } else if (property_exists($company, "fixed_discount")) {
                        $customer_company_discount_fixed = $company->fixed_discount;
                    }
                }
            }
        }
    }
    $customer = new Customer($customer_name, $customer_department, $customer_department_id, $company_id, $customer_company, $customer_department_discount_variable, $customer_department_discount_fixed, $customer_company_discount_variable, $customer_company_discount_fixed);
    array_push($customers, $customer);
}

$result_array = [];

// note: bit of a weird validation. Did this at the end as an extra
if (isset($_GET["products_selected"]) && isset($_GET["customer_selected"])) {
    // var_dump($_GET["products_selected"]);
    // var_dump($_GET["products_selected"][0]);
    if (!empty($_GET["products_selected"]) && !empty($_GET["customer_selected"])) {
        $customer_found = "";
        $customer_selected = $_GET["customer_selected"];
        foreach ($customers as $customer) {
            if ($customer_selected == $customer->name) {
                $customer_found = $customer;
            }
        }
        $products_selected = $_GET["products_selected"];
        // var_dump($products_selected);
        // find corresponding product in array of class objects
        // note: think it takes less computing power to have the largest array (products) on the outside. CORRECT OR NOT?
        foreach ($products as $product) {
            $product_name = $product->get_product_name();
            foreach ($products_selected as $key => $value) {
                if ($product_name == $value) {
                    $result = calculate_best_price($product, $customer_found);
                    array_push($result_array, $result);
                }
            }
        }
    } else if (!empty($_GET["products_selected"]) && empty($_GET["customer_selected"])) {
        if ($_GET["products_selected"][0] == "") {
            echo "you need to select one or more products before clicking the button 2";
        } elseif (($_GET["products_selected"][0] !== "") && ($_GET["customer_selected"]) == "") {
            echo  "you need to select a customer before clicking the button";
        } else {
            echo  "you need to select a customer before clicking the button";
        }
    }
} elseif (!isset($_GET["products_selected"]) && isset($_GET["customer_selected"])) {
    if ($_GET["customer_selected"] == "") {
        echo "you need to select one or more products before clicking the button AND you need to select a customer before clicking the button";
    } else {
        echo "you need to select one or more products before clicking the button 1";
    }
}


/* elseif (!empty($_GET["products_selected"]) && $_GET["customer_selected"] !== "") {

    echo "you need to select a customer before clicking the button";
} elseif (empty($_GET["products_selected"]) && ($_GET["customer_selected"] == "")) {
    echo "you need to select one or more products before clicking the button";
} */

// var_dump($customer_found);

function calculate_best_price($product, $customer_found)
{
    // echo "the {$product->get_product_name()} kost {$product->get_product_price()} </br>";
    // For the customer group: In case of variable discounts look for highest discount of all the groups the user has.
    $discount_variable_array = [];
    // If some groups have fixed discounts, count them all up.
    $discount_fixed_sum = 0;
    foreach ($customer_found->discount as $discounts) {
        foreach ($discounts as $key => $value) {
            if ($value !== 0) {
                // echo "You can have a $key of $value </br>";
                // unclear requirment: First subtract fixed amounts, then percentages!
                if (strpos($key, "fixed") == true) {
                    // testcase voor sum van fixed: 'Bruna Levering
                    $discount = $value;
                    $price = $product->get_product_price();
                    $discount_fixed_sum += $discount;
                    $offer_discount_fixed_sum = $price - $discount_fixed_sum;
                } else if (strpos($key, "variable") == true) {
                    $discount_variable = $value / 100;
                    array_push($discount_variable_array, $discount_variable);
                }
            }
        }
    }

    $offer_variable = null;
    $offer_fixed = null;

    // note: no else_if here as otherwise it would not catch the case in which both are less than 0
    if (!empty($discount_variable_array)) {
        $discount_variable_highest = max($discount_variable_array);
        $price = $product->get_product_price();
        $offer_discount_variable_highest = $price - $price * $discount_variable_highest;
        $offer_variable = $offer_discount_variable_highest;
    }
    if ($discount_fixed_sum !== 0) {
        // var_dump($offer_fixed);
        // var_dump($offer_discount_fixed_sum);
        $offer_fixed = $offer_discount_fixed_sum;
    }

    // A price can never be negative.
    // note: no else_if here as otherwise it would not catch the case in which both are less than 0
    if ($offer_variable < 0) {
        $offer_variable = 0;
    }
    if ($offer_fixed < 0) {
        $offer_fixed = 0;
    }

    if ($offer_variable === 0 || $offer_fixed === 0) {
        //        echo "you can get the {$product->get_product_name()} (normal cost {$product->get_product_price()} euro) for free! Because the best variable offer is $offer_variable and the (sum of) fixed amount discount amount to a final price of $offer_fixed </br>";
        $result = "you can get the {$product->get_product_name()} (normal cost {$product->get_product_price()} euro) for free! Because the best variable offer is $offer_variable and the (sum of) fixed amount discount amount to a final price of $offer_fixed </br>";
    } elseif ($offer_variable < $offer_fixed) {
        // echo "the best offer for {$product->get_product_name()} (normal cost {$product->get_product_price()} euro) is $offer_variable euro, because one variable discount was better than any other variable discount or (sum of) fixed discount, which was $offer_fixed </br>";
        $result = "the best offer for {$product->get_product_name()} (normal cost {$product->get_product_price()} euro) is $offer_variable euro, because one variable discount was better than any other variable discount or (sum of) fixed discount, which was $offer_fixed </br>";
    } elseif ($offer_variable > $offer_fixed) {
        //        echo "the best offer for {$product->get_product_name()} (normal cost {$product->get_product_price()} euro) is $offer_fixed euro, because the (sum of) fixed discount(s) was better than the highest variable discount, which was $offer_variable</br>";

        $result = "the best offer for {$product->get_product_name()} (normal cost {$product->get_product_price()} euro) is $offer_fixed euro, because the (sum of) fixed discount(s) was better than the highest variable discount, which was $offer_variable</br>";
        var_dump($result);
    } elseif ($offer_variable == $offer_fixed) {
        //  echo "there are equally good offers for {$product->get_product_name()} (normal cost {$product->get_product_price()} euro): the highest variable discount amounts to a cost $offer_variable, the (sum of) fixed discount(s) is $offer_fixed </br>";
        $result = "there are equally good offers for {$product->get_product_name()} (normal cost {$product->get_product_price()} euro): the highest variable discount amounts to a cost $offer_variable, the (sum of) fixed discount(s) is $offer_fixed </br>";
    }
    return $result;
}
