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

    /*  DECDIDED TO DO THIS VIA CLASS THAT IS BASED ON THIS, BUT NOT DIRECTLY HERE - MORE SECURE, I THINK
function select($product_name)
    {
        foreach ($this->products as $product) {
            $name = 'name';
            if ($product->$name == $product_name) {
                return $product;
            }
        }
    } */
}

// NOTE: THERE ARE DIFFERENT FORMATS IN DATABASE GROUPS: DEPARTMENT, COMPANY
class Group_DB
{
}

// QUESTION: DOES CUSTUMER CLASS CORRECTLY EXTENDS CLASS GROUP; TRYING IT OUT NOW
class Customer_DB
{
    private $customers;

    function __construct()
    {
        $customers_original = file_get_contents("customers.json");
        $customers_array = json_decode($customers_original);
        $this->customers = $customers_array;
    }

    function __destruct()
    {
        // TO DO
    }

    function get_all_customers()
    {
        return $this->customers;
    }

    function get_customer($customer_name)
    {
        foreach ($this->customers as $customer) {
            $name = 'name';
            if ($customer->$name == $customer_name) {
                return $customer;
            }
        }

        // function set_new_customer() {}

    }
}
class Product
{
    // A product (product name, description, price)
    private $product_name = "productnaam";
    private $description = "beschrijving product";
    private $price = 0;
    public function __construct($product_name, $description, $price)
    {
        $this->product_name = $product_name;
        $this->description = $description;
        $this->price = $price;
    }
    public function get_product_name()
    {
        return $this->product_name;
    }
    public function get_product_price()
    {
        return $this->price;
    }
    public function get_product_description()
    {
        return $this->description;
    }
    // public function set_product_info ($product_name) {
    //     TO DO IF USEFUL
    // }
}

// TO DO: add discount somewhere. in an array? multiple variabeles? both in department, company and ceven customer level?
class Company
{
    public $company = "company";
    public $discount = 0;
}

class Department extends Company
{
    public $deparment_id = 0;
    public $department = "department";
    public $group_id = 0;
}

class Customer extends Department
{
    public $name = "personal name";
}

// neemt alles correct over, creert een customer object met nodige info
// $test_customer_class = new Customer();
// var_dump($test_customer_class);
