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

    function __construct(
        $id = null,
        $name = null,
        $photos = null,
        $price = null,
        $description = null,
        $quantity = null,
        $createdAt = null,
        $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->photos[] = $photos;
        $this->photos = $photos;
        $this->price = $price;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    function set_name($name)
    {
        $this->name = $name;
    }

    function add_photo($img)
    {
        $this->photos[] = $img;
    }

    function set_price($num)
    {
        if ($this->is_num_ok($num)) {
            $this->price = $num;
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
        return $this->createdAt;
    }

    function get_lastupdated()
    {
        return $this->updatedAt;
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


try {
    $pdo = new PDO("mysql:host=localhost:3306;dbname=draft-shop", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Active les exceptions en cas d'erreur SQL
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retourne les résultats sous forme de tableau associatif
    ]);
    // $pdo = PDO::connect("mysql:host=localhost:3306;dbname=draft-shop", "root", "");
} catch (PDOException $e) {
    exit('Erreur de connexion BDD');
}

$stmt = $pdo->query(
    'SELECT * FROM product WHERE id = 7'
);

$data = $stmt->fetch();
print_r($data);

$poubelle = new Product(
    $data["id"],
    $data["name"],
    $data["photos"],
    $data["price"],
    $data["description"],
    $data["quantity"],
    $data["createdAt"],
    $data["updatedAt"]
);
echo $poubelle->get_creationdate();
