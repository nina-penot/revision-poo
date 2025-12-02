<?php

require_once "./job13/database.php";
require_once "./job13/myclasses/Clothing.php";
require_once "./job13/myclasses/MyFunctions.php";
require_once "./job13/myclasses/AbstractProduct.php";


$jeans = new Clothing(
    "Cool jeans",
    "cooljeans.png",
    30,
    "Cool jeans!",
    100,
    null,
    time(),
    time(),
    "L",
    "bleu",
    "pantalon",
    0
);

print_r($jeans);
