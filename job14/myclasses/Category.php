<?php

// namespace myclasses;

class Category
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
