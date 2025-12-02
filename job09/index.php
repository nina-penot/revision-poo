<?php

require_once "./job09/database.php";

date_default_timezone_set("CET");

abstract class MyFunctions
{
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
}

class Category extends MyFunctions
{
    private $id, $name, $description, $createdAt, $updatedAt;

    function __construct($id, $name, $description, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;

        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
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
        return $this->createdAt;
    }

    function get_lastupdated()
    {
        return $this->updatedAt;
    }

    function getProducts()
    {
        $query = "SELECT * FROM product WHERE category_id = ?";
        $data = db_select($query, [$this->id]);
        return $data;
    }
}

class Product extends MyFunctions
{
    private $id, $name, $photos = [], $price, $description, $quantity, $createdAt,
        $updatedAt, $category_id;

    function __construct(
        $name = null,
        $photos = null,
        $price = null,
        $description = null,
        $quantity = null,
        $category_id = null,
        $createdAt = null,
        $updatedAt = null
    ) {
        $this->name = $name;
        $this->photos[] = $photos;
        $this->photos = $photos;
        $this->price = $price;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->category_id = $category_id;
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

    function getCategory()
    {
        $query = "SELECT name FROM category WHERE id = ?";

        $data = db_select_one($query, [$this->category_id]);
        return $data["name"];
    }

    function findOneById(int $id)
    {
        $query = "SELECT * FROM product WHERE id = ?";
        $data = db_select_one($query, [$this->id]);
        if (empty($data)) {
            return false;
        } else {
            return $data;
        }
    }

    function findAll()
    {
        $query = "SELECT * FROM product";
        $data = db_select($query);

        $my_products = [];
        foreach ($data as $product) {
            $my_products[] = new Product(
                $product["id"],
                $product["name"],
                $product["photos"],
                $product["price"],
                $product["description"],
                $product["quantity"],
                $product["category_id"],
                $product["createdAt"],
                $product["updatedAt"]
            );
        }

        return $my_products;
    }

    function create()
    {
        $query = "INSERT INTO product (name, photos, price, description, quantity, category_id,
        createdAt, updatedAt)
        VALUES (:name, :photos, :price, :descr, :quant, :cat_id, :createdat, :updatedat)";

        $createdat_clean = date('Y-m-d H:i:s', $this->createdAt);
        $updatedat_clean = date('Y-m-d H:i:s', $this->updatedAt);
        $params = [
            "name" => $this->name,
            "photos" => $this->photos,
            "price" => $this->price,
            "descr" => $this->description,
            "quant" => $this->quantity,
            "cat_id" => $this->category_id,
            "createdat" => $createdat_clean,
            "updatedat" => $updatedat_clean
        ];

        if (db_execute($query, $params)) {
            return get_last_inserted();
        } else {
            return false;
        }

        // return db_execute($query, $params);
    }
}

$product = new Product(
    "Table en fer",
    "irontable.jpg",
    60,
    "Une table en fer, très résestante.",
    5,
    1,
    time(),
    time()
);

// print_r($product);
print_r($product->create());
