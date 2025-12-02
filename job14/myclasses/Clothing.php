<?php

// namespace myclasses;

require_once "./job13/myclasses/AbstractProduct.php";
require_once "./job13/myclasses/StockableInterface.php";

// use myclasses\AbstractProduct;

class Clothing extends AbstractProduct implements StockableInterface
{
    private $size, $color, $type, $material_fee;

    function __construct(
        $name = null,
        $photos = null,
        $price = null,
        $description = null,
        $quantity = null,
        $createdAt = null,
        $updatedAt = null,
        $size = null,
        $color = null,
        $type = null,
        $material_fee = null
    ) {
        $category_id = 3;
        parent::__construct($name, $photos, $price, $description, $quantity, $category_id, $createdAt, $updatedAt);
        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
        $this->material_fee = $material_fee;
    }

    function addStock(int $stock)
    {
        $this->quantity += $stock;
    }

    function removeStock(int $stock)
    {
        $this->quantity -= $stock;
    }

    function findOneById(int $id)
    {
        $query = "SELECT * FROM product 
        LEFT JOIN clothing ON clothing.product_id = product.id 
        WHERE id = ? and category_id = ?";

        $data = db_select_one($query, [$id, $this->category_id]);
        if (!empty($data)) {
            return $data;
        } else {
            return false;
        }
    }

    function create()
    {
        if ($this->create_base_product()) {
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
                if (empty($this->id)) {
                    $this->id = get_last_inserted();
                }
                return $this->id;
            }
        } else {
            return false;
        }
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

        $query2 = "UPDATE clothing 
        SET size = :size, color = :color, type = :type, material_fee = :mat_fee 
        WHERE product_id = :pid";

        $params2 = [
            "size" => $this->size,
            "color" => $this->color,
            "type" => $this->type,
            "mat_fee" => $this->material_fee,
            "pid" => $this->id
        ];

        $array = [
            ["query" => $query, "params" => $params],
            ["query" => $query2, "params" => $params2]
        ];

        return db_transaction($array);
    }

    function findAll()
    {
        $query = "SELECT * FROM product 
        LEFT JOIN clothing on product.id = clothing.product_id
        WHERE category_id = 3";
        $data =  db_select($query);

        $my_electronics = [];
        foreach ($data as $e) {
            $my_clothings[] = new Clothing(
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

        return $my_clothings;
    }
}
