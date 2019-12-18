<?php
// The Controller responds to user actions and invokes changes on the model or view as appropriate.
// Controller/: has access to GET/POST vars, receives the Request
// ? vb www.mijndomein.nl/index.php?route=media&media_id=345 ? http://www.sitemasters.be/tutorials/1/1/509/PHP/MVC_pattern_uitgelegd
// precies andere uitleg: controller staat tussen de view en het model (data) https://code-boxx.com/simple-php-mvc-example/

// echo "test controller.php <br>";

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
// var_dump($groups_object_accessible);
sort($groups_object_accessible);
// var_dump($groups_object_accessible);
$groups_multidimensional = [];
$departments = [];
$companies = [];
foreach ($groups_object_accessible as $group_object) {
    // var_dump($group_object);
    if (property_exists($group_object, "group_id")) {
        // echo "voor $group_object->name bestaat group_id";
        array_push($companies, $group_object);
    } else {
        // echo "voor $group_object->name bestaat group_id niet";
        array_push($departments, $group_object);
    }
}
array_push($groups_multidimensional, $departments);
array_push($groups_multidimensional, $companies);
// var_dump($groups_multidimensional);

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
    $customer_department_discount_variable = 0;
    $customer_department_discount_fixed = 0;
    $customer_company_discount_variable = 0;
    $customer_company_discount_fixed = 0;
    // linking multiple databases to enrich customer class
    // IMPORTANT NOTE! this below has been with a shared identifier, that appears to be NOT ALWAYS UNIQUE. Therefore a person who actually belongs to a certain department, can be wrongly categorized as belonging to another. 
    // Yet the assignement is very unclear and as this is an exercise, I will ignore this otherwise fatal error
    foreach ($groups_multidimensional[1] as $department) {
        // var_dump($department);
        $name = 'name';
        $id = 'id';
        $group_id = 'group_id';
        // if ($customer_group_id == $department->$group_id) {
        // received a tip: group id of customer would refer to id of department
        if ($customer_department_id == $department->$id) {

            // !
            $customer_department = $department->name;
            $company_id = $department->group_id;
            if (property_exists($department, "variable_discount")) {
                $customer_department_discount_variable = $department->variable_discount;
            } else if (property_exists($department, "fixed_discount")) {
                $customer_department_discount_fixed = $department->fixed_discount;
            }

            foreach ($groups_multidimensional[0] as $company) {
                // var_dump($company);
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
    // ($name, $company_id, $department, $department_id, $company, $department_discount_variable, $department_discount_fixed, $company_discount_variable, $company_discount_fixed)
    array_push($customers, $customer);
}
// var_dump($customers);
foreach ($customers as $customer) {
    var_dump($customer);
    // var_dump($customer->discount);
}

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
