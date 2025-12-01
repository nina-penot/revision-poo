<?php

date_default_timezone_set("CET");

use function PHPSTORM_META\type;

class Product
{
    private $id, $name, $photos = [], $price, $description, $quantity, $createdAt,
        $updatedAt;

    function __construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt)
    {
        if ($this->is_id_ok($id)) {
            $this->id = $id;
        } else {
            throw new Exception("Invalid ID. Must be integer that is not negative.");
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
            throw new Exception("Invalid price. Must be integer or float number that is not negative.");
        }

        $this->description = $description;

        if ($this->is_num_ok($quantity)) {
            $this->quantity = $quantity;
        } else {
            throw new Exception("Invalid quantity. Must be integer or float number that is not negative.");
        }

        //Il faut éviter d'instancier un objet dans un construct il faut donc mettre new Datetime
        //lors de l'instanciation de l'objet. :(
        if ($this->is_date_ok($createdAt) and $this->is_date_ok($updatedAt)) {
            $this->createdAt = $createdAt;
            $this->updatedAt = $updatedAt;
        } else {
            throw new Exception("Date invalid. Must be DateTime object.");
        }
    }

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

    function show_all()
    {
        foreach ($this as $key => $val) {
            if (gettype($val) == "array") {
                echo $key, " = ";
                print_r($val);
                echo "\n";
            } elseif (gettype($val) == "object") {
                echo $key, " = ", date('m/d/Y h:i:s a', $val->getTimestamp()), "\n";
            } else {
                echo $key, " = ", $val, "\n";
            }
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
}

// $date = new DateTime();
// $date->setTimestamp(time());
// echo $date->getTimestamp();


$test = new Product(1, "bbb", "photo", 2, "hs", 4, new DateTime, new DateTime());
$test->show_all();
$test->set_price(5.99);

echo $test->get_price();
