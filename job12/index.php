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

    function set_id($id)
    {
        $this->id = $id;
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
            $this->set_id(get_last_inserted());
            return get_last_inserted();
        } else {
            return false;
        }

        // return db_execute($query, $params);
    }

    function update()
    {
        $query = "UPDATE product
        SET name = :name,  photos = :photos, price = :price, description = :descr,
        quantity = :quant, category_id = :cat_id, updatedAt = :updatedat
        WHERE product.id = :id";

        $updatedat_clean = date('Y-m-d H:i:s', time());

        $params = [
            "name" => $this->name,
            "photos" => $this->photos,
            "price" => $this->price,
            "descr" => $this->description,
            "quant" => $this->quantity,
            "cat_id" => $this->category_id,
            "updatedat" => $updatedat_clean,
            "id" => $this->get_id()
        ];

        return db_execute($query, $params);
    }
}

class Clothing extends Product
{
    private $id, $size, $color, $type, $material_fee;

    function __construct(
        $name = null,
        $photos = null,
        $price = null,
        $description = null,
        $quantity = null,
        $category_id = null,
        $createdAt = null,
        $updatedAt = null,
        $size = null,
        $color = null,
        $type = null,
        $material_fee = null
    ) {
        parent::__construct($name, $photos, $price, $description, $quantity, $category_id, $createdAt, $updatedAt);
        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
        $this->material_fee = $material_fee;
    }

    function findOneById(int $id)
    {
        $data = parent::findOneById($id);
        if ($data["category_id"] == 3) {
            $query2 = "SELECT * FROM clothing where product_id = ?";
            $data2 = db_select_one($query2, [$id]);
            if (!empty($data2)) {
                foreach ($data2 as $d) {
                    $data[] = $d;
                }
            }
            return $data;
        } else {
            return false;
        }
    }

    function create()
    {
        if (parent::create()) {
            $this->id = parent::create();
            $query2 = "INSERT INTO clothing (product_id, size, color, type, material_fee)
            VALUES (:pid, :size, :color, :type, :mat_fee)";

            $params2 = [
                "pid" => $this->id,
                "size" => $this->size,
                "color" => $this->color,
                "type" => $this->type,
                "mat_fee" => $this->material_fee
            ];

            if (db_execute($query2, $params2)) {
                return $this->id;
            }
        } else {
            return false;
        }
    }

    function update()
    {
        parent::update();
        $query2 = "UPDATE clothing 
        SET size = :size, color = :color, type = :type, material_fee = :mat_fee 
        WHERE product_id = :id";

        $params2 = [
            "size" => $this->size,
            "color" => $this->color,
            "type" => $this->type,
            "mat_fee" => $this->material_fee,
            "id" => $this->id
        ];

        return db_execute($query2, $params2);
    }

    function findAll()
    {
        $query = "SELECT * FROM product 
        LEFT JOIN clothing on product.id = clothing.product_id
        WHERE category_id = 3";
        $data =  db_select($query);

        $my_electronics = [];
        foreach ($data as $e) {
            $my_electronics[] = new Clothing(
                $e["name"],
                $e["photos"],
                $e["price"],
                $e["description"],
                $e["quantity"],
                $e["category_id"],
                $e["createdAt"],
                $e["updatedAt"],
                $e["size"],
                $e["color"],
                $e["type"],
                $e["material_fee"]
            );
        }

        return $my_electronics;
    }
}

class Electronic extends Product
{
    private $id, $brand, $waranty_fee;

    function __construct(
        $name = null,
        $photos = null,
        $price = null,
        $description = null,
        $quantity = null,
        $category_id = null,
        $createdAt = null,
        $updatedAt = null,
        $brand = null,
        $waranty_fee = null
    ) {
        $this->brand = $brand;
        $this->waranty_fee = $waranty_fee;
        parent::__construct($name, $photos, $price, $description, $quantity, $category_id, $createdAt, $updatedAt);
    }

    function findOneById(int $id)
    {
        $data = parent::findOneById($id);
        if ($data["category_id"] == 2) {
            $query2 = "SELECT * FROM clothing where product_id = ?";
            $data2 = db_select_one($query2, [$id]);
            if (!empty($data2)) {
                foreach ($data2 as $d) {
                    $data[] = $d;
                }
            }
            return $data;
        } else {
            return false;
        }
    }

    function create()
    {
        if (parent::create()) {
            $this->id = parent::create();
            $query2 = "INSERT INTO electronic (product_id, brand, waranty_fee)
            VALUES (:pid, :brand, :w_fee)";

            $params2 = [
                "pid" => $this->id,
                "brand" => $this->brand,
                "w_fee" => $this->waranty_fee
            ];

            if (db_execute($query2, $params2)) {
                return $this->id;
            }
        } else {
            return false;
        }
    }

    function update()
    {
        parent::update();
        $query2 = "UPDATE electronic 
        SET brand = :brand, waranty_fee = :w_fee 
        WHERE product_id = :id";

        $params2 = [
            "brand" => $this->brand,
            "w_fee" => $this->waranty_fee
        ];

        return db_execute($query2, $params2);
    }

    function findAll()
    {
        $query = "SELECT * FROM product 
        LEFT JOIN electronic on product.id = electronic.product_id
        WHERE category_id = 2";
        $data = db_select($query);

        $my_electronics = [];
        foreach ($data as $e) {
            $my_electronics[] = new Electronic(
                $e["name"],
                $e["photos"],
                $e["price"],
                $e["description"],
                $e["quantity"],
                $e["category_id"],
                $e["createdAt"],
                $e["updatedAt"],
                $e["brand"],
                $e["warant_fee"]
            );
        }

        return $my_electronics;
    }
}

// $shirt = new Clothing(
//     "TShirt",
//     "shirt.png",
//     20,
//     "Un Tshirt tout ce qu'il y a de plus simple.",
//     100,
//     3,
//     time(),
//     time(),
//     "M",
//     "blanc",
//     "tshirt",
//     0
// );

// print_r($shirt);

// $shirt->create();

$pc = new Electronic(
    "Ordinateur Tour",
    "pcbig.png",
    500,
    "Un pc tour.",
    2,
    2,
    time(),
    time(),
    "HP",
    2
);

$pc->create();
