<?php

// namespace myclasses;
require_once "./job13/myclasses/AbstractProduct.php";
// use myclasses\AbstractProduct;

class Electronic extends AbstractProduct
{
    private $brand, $waranty_fee;

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
        if ($this->create_base_product()) {
            $query2 = "INSERT INTO electronic (product_id, brand, waranty_fee)
            VALUES (:pid, :brand, :w_fee)";

            $params2 = [
                "pid" => $this->id,
                "brand" => $this->brand,
                "w_fee" => $this->waranty_fee
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

        $query2 = "UPDATE electronic 
        SET brand = :brand, waranty_fee = :w_fee 
        WHERE product_id = :id";

        $params2 = [
            "brand" => $this->brand,
            "w_fee" => $this->waranty_fee
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
