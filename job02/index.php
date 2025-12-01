<?php

date_default_timezone_set("CET");

abstract class MyFunctions
{
    public $test = "aa";

    function is_num_ok($num)
    {
        if ($num < 0 or !is_numeric($num)) {
            return false;
        } else {
            return true;
        }
    }

    function is_id_ok($num)
    {
        if ($num < 0 or gettype($num) != "integer") {
            return false;
        } else {
            return true;
        }
    }

    function is_date_ok($date)
    {
        if (gettype($date) == "object") {
            if (get_class($date) == "DateTime") {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function wrong_id()
    {
        throw new Exception("Wrong ID number. Must be integer and not negative.");
    }

    function wrong_number()
    {
        throw new Exception("Wrong value. Must be numerical and not negative.");
    }

    function wrong_date()
    {
        throw new Exception("Date invalid. Please use a new DateTime object.");
    }

    //check how to refer to child instead of the parent here
    // function show_all()
    // {
    //     foreach ($this as $key => $val) {
    //         if (gettype($val) == "array") {
    //             echo $key, " = ";
    //             print_r($val);
    //             echo "\n";
    //         } elseif (gettype($val) == "object") {
    //             if (get_class($val) == "DateTime") {
    //                 echo $key, " = ", date('m/d/Y h:i:s a', $val->getTimestamp()), "\n";
    //             }
    //         } else {
    //             echo $key, " = ", $val, "\n";
    //         }
    //     }
    // }
}

class Category extends MyFunctions
{
    private $id, $name, $description, $createdAt, $updatedAt;

    function __construct($id, $name, $description, $createdAt, $updatedAt)
    {
        if ($this->is_id_ok($id)) {
            $this->id = $id;
        } else {
            $this->wrong_id();
        }

        $this->name = $name;
        $this->description = $description;

        if ($this->is_date_ok($createdAt) and $this->is_date_ok($updatedAt)) {
            $this->createdAt = $createdAt;
            $this->updatedAt = $updatedAt;
        } else {
            $this->wrong_date();
        }
    }

    function get_id()
    {
        return $this->id;
    }

    function set_id($id)
    {
        $this->id = $id;
    }

    function get_name()
    {
        return $this->name;
    }

    function set_name($name)
    {
        $this->name = $name;
    }

    function get_description()
    {
        return $this->description;
    }

    function set_description($desc)
    {
        $this->description = $desc;
    }

    function get_creationdate()
    {
        return date('m/d/Y h:i:s a', $this->createdAt->getTimestamp());
    }

    function get_lastupdated()
    {
        return date('m/d/Y h:i:s a', $this->updatedAt->getTimestamp());
    }
}

class Product extends MyFunctions
{
    private $id, $name, $photos = [], $price, $description, $quantity, $createdAt,
        $updatedAt, $category_id;

    function __construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt)
    {
        if ($this->is_id_ok($id)) {
            $this->id = $id;
        } else {
            $this->wrong_id();
        }

        $this->name = $name;

        if (gettype($photos) != "array") {
            $this->photos[] = $photos;
        } else {
            $this->photos = $photos;
        }

        if ($this->is_num_ok($price)) {
            $this->price = $price;
        } else {
            $this->wrong_number();
        }

        $this->description = $description;

        if ($this->is_num_ok($quantity)) {
            $this->quantity = $quantity;
        } else {
            $this->wrong_number();
        }

        //Il faut éviter d'instancier un objet dans un construct il faut donc mettre new Datetime
        //lors de l'instanciation de l'objet. :(
        if ($this->is_date_ok($createdAt) and $this->is_date_ok($updatedAt)) {
            $this->createdAt = $createdAt;
            $this->updatedAt = $updatedAt;
        } else {
            $this->wrong_date();
        }
    }

    function set_name($name)
    {
        $this->name = $name;
        $this->updatedAt->setTimestamp(time());
    }

    function add_photo($img)
    {
        $this->photos[] = $img;
        $this->updatedAt->setTimestamp(time());
    }

    function set_price($num)
    {
        if ($this->is_num_ok($num)) {
            $this->price = $num;
            $this->updatedAt->setTimestamp(time());
        } else {
            echo "This price is invalid. Please try again.";
        }
    }

    function addto_price($num)
    {
        $this->price += $num;
    }

    function sub_price($num)
    {
        if ($this->price -= $num < 0) {
            echo "Substracting too much resulting in negative. Setting to 0 instead.";
            $this->price = 0;
        } else {
            $this->price -= $num;
        }
    }

    function set_description($desc)
    {
        $this->description = $desc;
        $this->updatedAt->setTimestamp(time());
    }

    function set_quantity($num)
    {
        if ($this->is_num_ok($num)) {
            $this->quantity = $num;
        } else {
            echo "Number is invalid please retry.";
        }
    }

    function addto_quantity($num)
    {
        $this->quantity += $num;
    }

    function sub_quantity($num)
    {
        if ($this->quantity -= $num < 0) {
            echo "Substracting too much resulting in negative. Setting to 0 instead.";
            $this->quantity = 0;
        } else {
            $this->quantity -= $num;
        }
    }

    function get_id()
    {
        return $this->id;
    }

    function get_name()
    {
        return $this->name;
    }

    function get_photos()
    {
        return $this->photos;
    }

    function get_price()
    {
        return $this->price;
    }

    function get_description()
    {
        return $this->description;
    }

    function get_quantity()
    {
        return $this->quantity;
    }

    function get_creationdate()
    {
        return date('m/d/Y h:i:s a', $this->createdAt->getTimestamp());
    }

    function get_lastupdated()
    {
        return date('m/d/Y h:i:s a', $this->updatedAt->getTimestamp());
    }

    function get_category_id()
    {
        if (empty($this->category_id)) {
            echo "There is no category yet.";
        } else {
            return $this->category_id;
        }
    }

    function set_category_id($id)
    {
        $this->category_id = $id;
    }
}

// $date = new DateTime();
// $date->setTimestamp(time());
// echo $date->getTimestamp();


$test = new Product(1, "bbb", "photo", 2, "hs", 4, new DateTime, new DateTime());
$test->show_all();
// $test->set_price(5.99);

// echo $test->get_price();
$ttt = new Category(1, "nn", "aaaa", new DateTime(), new DateTime());
// echo $ttt->show_all();
